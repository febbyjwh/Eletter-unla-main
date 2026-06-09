<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

class GoogleController extends Controller
{
    private function scopes(): array
    {
        return [
            'openid',
            'email',
            'profile',
            'https://www.googleapis.com/auth/drive.file',
        ];
    }

    public function redirectLogin()
    {
        session(['auth_mode' => 'login']);

        return Socialite::driver('google')
            ->scopes($this->scopes())
            ->with(['access_type' => 'offline'])
            ->redirect();
    }

    public function redirectRegister()
    {
        session(['auth_mode' => 'register_user']);

        return Socialite::driver('google')
            ->scopes($this->scopes())
            ->with([
                'access_type' => 'offline',
                // 'prompt' => 'consent',
            ])
            ->redirect();
    }

    public function redirectUnit()
    {
        session(['auth_mode' => 'register_unit']);
        session()->save();

        return Socialite::driver('google')
            ->scopes($this->scopes())
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent',
            ])
            ->redirect();
    }

    public function callback(Request $request)
    {
        $socialUser = Socialite::driver('google')->user();
        $mode = session('auth_mode');
        session()->forget('auth_mode');

        $email = $socialUser->email;

        // token data
        $tokenData = [
            'google_access_token' => json_encode([
                'access_token'  => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken,
                'expires_in'    => $socialUser->expiresIn ?? 3600,
                'created'       => time(),
            ]),
            'google_refresh_token' => $socialUser->refreshToken,
            'google_token_expires_at' => now()->addSeconds($socialUser->expiresIn ?? 3600),
        ];

        if ($mode === 'register_unit') {
            return $this->registerUnit($email, $tokenData);
        }

        if ($mode === 'register_user') {
            return $this->registerUser($socialUser, $tokenData);
        }

        return $this->loginUser($email, $socialUser->refreshToken, $tokenData);
    }

    private function registerUnit(string $email, array $tokenData)
    {
        $namaUnit = session()->pull('pending_nama_unit');
        if (!$namaUnit) return back()->with('error', 'Nama unit tidak ditemukan. Silakan isi nama unit sebelum melanjutkan.');

        $unit = Unit::create([
            'kode_unit' => 'UNIT-' . strtoupper(Str::random(6)),
            'nama_unit' => $namaUnit,
            'email' => $email,
            'status' => 0,
            ...$tokenData,
        ]);

        User::create([
            'name' => $namaUnit,
            'email' => $email,
            'password' => bcrypt(Str::random(16)),
            'unit_id' => $unit->unit_id,
            'role_id' => 3,
            'status' => 0,
            ...$tokenData,
        ]);

        return redirect('/')->with('success', 'Unit anda berhasil didaftarkan. Silahkan tunggu verifikasi dari administrator.');
    }

    private function registerUser($socialUser, array $tokenData)
    {
        if (User::where('email', $socialUser->email)->exists()) {
            return back()->with('error', 'Alamat email ini sudah terdaftar pada sistem. Silakan login atau gunakan email lain.');
        }

        User::create([
            'name' => $socialUser->name,
            'email' => $socialUser->email,
            'password' => bcrypt(Str::random(16)),
            'role_id' => 2,
            'status' => 0,
            ...$tokenData,
        ]);

        return redirect('/')->with('success', 'User berhasil register. Silahkan tunggu verifikasi dari administrator');
    }

    private function loginUser(string $email, ?string $refreshToken, array $tokenData)
    {
        $user = User::where('email', $email)->first();
        if (!$user) return redirect('/register')->with('error', 'Akun dengan email ini belum terdaftar. Silakan melakukan registrasi terlebih dahulu.');
        if ($user->status != 1) return back()->with('error', 'Akun Anda belum aktif. Silakan hubungi administrator untuk proses aktivasi.');

        $user->update(array_merge(
            $tokenData,
            [
                'google_refresh_token' => $refreshToken ?? $user->google_refresh_token
            ]
        ));
        Auth::login($user);

        return redirect('/dashboard');
    }

    public static function uploadFileToDrive($file, string $jenisSurat, string $tanggal)
    {
        $user = auth()->user();
        $unit = $user->unit;

        if (!$unit) {
            throw new \Exception('Akun Anda belum terhubung dengan unit. Silakan hubungi administrator untuk proses aktivasi unit.');
        }

        if (!$unit->google_access_token) {
            throw new \Exception('Token unit Google tidak ditemukan');
        }

        $client = self::getClient($unit);
        $drive  = new Drive($client);

        $rootId = $unit->google_drive_folder_id;

        if (!$rootId) {
            throw new \Exception('Folder unit belum ada');
        }

        $date = \Carbon\Carbon::parse($tanggal);

        $tahun = $date->year;

        $bulan = $date->translatedFormat('F');
        // Januari, Februari, dst

        $folderJenis = strtolower($jenisSurat) === 'masuk'
            ? 'Surat Masuk'
            : 'Surat Keluar';

        // Tahun
        $yearId = self::getOrCreateFolder(
            $drive,
            (string) $tahun,
            $rootId
        );

        // Bulan
        $monthId = self::getOrCreateFolder(
            $drive,
            $bulan,
            $yearId
        );

        // Surat Masuk / Surat Keluar
        $targetFolderId = self::getOrCreateFolder(
            $drive,
            $folderJenis,
            $monthId
        );

        $fileMeta = new DriveFile([
            'name'    => $file->getClientOriginalName(),
            'parents' => [$targetFolderId],
        ]);

        $uploaded = $drive->files->create($fileMeta, [
            'data'       => file_get_contents($file->getRealPath()),
            'mimeType'   => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields'     => 'id, webViewLink',
        ]);

        $permission = new Permission([
            'type' => 'anyone',
            'role' => 'reader',
        ]);

        $drive->permissions->create(
            $uploaded->id,
            $permission
        );

        return [
            'file_id' => $uploaded->id,
            'url'     => $uploaded->webViewLink,
        ];
    }

    private static function getClient($unit)
    {
        $client = new \Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        $token = json_decode($unit->google_access_token, true);
        $client->setAccessToken($token);

        if ($client->isAccessTokenExpired()) {
            if (!$unit->google_refresh_token) {
                throw new \Exception('Refresh token unit tidak ada, login ulang dulu');
            }
            $client->fetchAccessTokenWithRefreshToken($unit->google_refresh_token);
            $newToken = $client->getAccessToken();
            $unit->update([
                'google_access_token' => json_encode($newToken),
                'google_token_expires_at' => now()->addSeconds($newToken['expires_in']),
            ]);
        }

        return $client;
    }

    private static function getOrCreateFolder(
        Drive $drive,
        string $folderName,
        string $parentId
    ) {
        $query =
            "mimeType='application/vnd.google-apps.folder'
        and name='{$folderName}'
        and '{$parentId}' in parents
        and trashed=false";

        $folders = $drive->files->listFiles([
            'q' => $query,
            'fields' => 'files(id,name)',
        ]);

        if (count($folders->files) > 0) {
            return $folders->files[0]->id;
        }

        $folder = $drive->files->create(
            new DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$parentId],
            ]),
            [
                'fields' => 'id',
            ]
        );

        return $folder->id;
    }
}

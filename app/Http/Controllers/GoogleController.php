<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use Google_Client;
use Google_Service_Drive;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes([
                'openid',
                'email',
                'profile',
                'https://www.googleapis.com/auth/drive.file',
            ])
            ->with([
                'access_type' => 'offline',
            ])
            ->redirect();
    }

    public function redirectLogin()
    {
        Session::forget('selected_role');

        return Socialite::driver('google')
            ->scopes([
                'openid',
                'email',
                'profile',
                'https://www.googleapis.com/auth/drive.file',
            ])
            ->with([
                'access_type' => 'offline',
                // 'prompt'      => 'consent',
            ])
            ->redirect();
    }

    public function redirectRegister($role)
    {
        session(['selected_role' => $role]);

        return Socialite::driver('google')
            ->scopes([
                'openid',
                'email',
                'profile',
                'https://www.googleapis.com/auth/drive.file',
            ])
            ->with([
                'access_type' => 'offline',
                'prompt'      => 'consent',
            ])
            ->redirect();
    }

    // buat folder "E-Letter UNLA" di drive user, return folder id
    private function createDriveFolder($accessToken)
    {
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessToken($accessToken);

        $service = new Google_Service_Drive($client);

        $folderMetadata = new \Google_Service_Drive_DriveFile([
            'name'     => 'E-Letter UNLA',
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);

        $folder = $service->files->create($folderMetadata, [
            'fields' => 'id',
        ]);

        return $folder->id;
    }

    public function callback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Autentikasi Google gagal. Silakan coba lagi.');
        }

        $user = User::where('email', $socialUser->email)->first();
        $role = session('selected_role');

        // $tokenData = [
        //     'google_access_token'     => json_encode($socialUser->token),
        //     'google_refresh_token'    => $socialUser->refreshToken,
        //     'google_token_expires_at' => now()->addSeconds($socialUser->expiresIn ?? 3600),
        // ];

        $updateData = [
            'google_access_token'     => json_encode($socialUser->token),
            'google_token_expires_at' => now()->addSeconds($socialUser->expiresIn ?? 3600),
        ];

        if (!empty($socialUser->refreshToken)) {
            $updateData['google_refresh_token'] = $socialUser->refreshToken;
        }

         $tokenData = $updateData;

        // dari register
        if ($role) {
            if ($user) {
                session()->forget('selected_role');

                if ($user->status == 1) {
                    return redirect('/')->with('error', 'Akun dengan email ini sudah terdaftar dan terverifikasi. Silakan login.');
                }

                return redirect('/')->with('error', 'Akun Anda sudah terdaftar namun belum diverifikasi admin. Silakan tunggu konfirmasi.');
            }

            // buat folder drive untuk user baru
            $folderId = $this->createDriveFolder(json_decode($tokenData['google_access_token'], true));

            User::create([
                'name'                    => $socialUser->name,
                'email'                   => $socialUser->email,
                'role_id'                 => $role,
                'status'                  => 0,
                'password'                => bcrypt(str()->random(16)),
                'google_drive_folder_id'  => $folderId,
                ...$tokenData,
            ]);

            session()->forget('selected_role');

            return redirect('/')->with('success', 'Registrasi berhasil. Menunggu verifikasi admin.');
        }

        // dari login
        if (!$user) {
            return redirect('/register')->with('error', 'Akun tidak ditemukan. Silakan daftar terlebih dahulu.');
        }

        if ($user->status != 1) {
            return redirect('/')->with('error', 'Akun Anda sudah terdaftar admin. Silakan tunggu konfirmasi.');
        }

        // kalau belum punya folder drive, buatkan sekarang
        $folderId = $user->google_drive_folder_id;
        if (!$folderId) {
            $folderId = $this->createDriveFolder(json_decode($tokenData['google_access_token'], true));
        }

        $user->update([
            ...$tokenData,
            'google_refresh_token'   => $socialUser->refreshToken ?? $user->google_refresh_token,
            'google_drive_folder_id' => $folderId,
        ]);

        Auth::login($user);
        return redirect('/dashboard');
    }

    public static function uploadFileToDrive($uploadedFile)
    {
        $user = auth()->user();

        if (!$user->google_access_token) {
            throw new \Exception('Token Google tidak ditemukan. Silakan login ulang dengan Google.');
        }

        // refresh token exp
        if (
            $user->google_token_expires_at &&
            now()->greaterThan($user->google_token_expires_at)
        ) {
            if (!$user->google_refresh_token) {
                throw new \Exception('Refresh token tidak ditemukan. Silakan login ulang dengan Google.');
            }

            $client = new Google_Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));

            $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
            $newToken = $client->getAccessToken();

            $user->update([
                'google_access_token'     => json_encode($newToken),
                'google_token_expires_at' => now()->addSeconds($newToken['expires_in']),
            ]);

            $user->refresh();
        }

        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        $token = json_decode($user->google_access_token, true);
        $client->setAccessToken($token);

        $service  = new Google_Service_Drive($client);
        $folderId = $user->google_drive_folder_id;

        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name'    => $uploadedFile->getClientOriginalName(),
            'parents' => [$folderId],
        ]);

        $content = file_get_contents($uploadedFile->getRealPath());

        $file = $service->files->create($fileMetadata, [
            'data'       => $content,
            'mimeType'   => $uploadedFile->getMimeType(),
            'uploadType' => 'multipart',
            'fields'     => 'id',
        ]);

        return [
            'file_id' => $file->id,
            'url'     => 'https://drive.google.com/file/d/' . $file->id . '/view',
        ];
    }
}

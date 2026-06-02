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
    private static function makeGoogleToken($accessToken, $expiresIn = null): array
    {
        $token = [
            'access_token' => $accessToken,
        ];

        if ($expiresIn) {
            $token['expires_in'] = $expiresIn;
            $token['created'] = time();
        }

        return $token;
    }

    private static function decodeGoogleToken($storedToken): array
    {
        $token = json_decode($storedToken, true);

        if (is_string($token)) {
            return self::makeGoogleToken($token);
        }

        if (is_array($token) && isset($token['access_token'])) {
            return $token;
        }

        if (is_string($storedToken) && $storedToken !== '') {
            return self::makeGoogleToken($storedToken);
        }

        throw new \Exception('Format token Google tidak valid. Silakan login ulang dengan Google.');
    }

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
        session(['auth_type' => 'login']);

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

    public function redirectRegister()
    {
        session(['auth_type' => 'register']);

        return Socialite::driver('google')
            ->scopes([
                'openid',
                'email',
                'profile',
                'https://www.googleapis.com/auth/drive.file',
            ])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent',
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
            return redirect('/')
                ->with('error', 'Autentikasi Google gagal. Silakan coba lagi.');
        }

        $authType = session('auth_type');
        $user = User::where('email', $socialUser->email)->first();

        $tokenData = [
            'google_access_token' => json_encode($socialUser->token),
            'google_token_expires_at' => now()->addSeconds(
                $socialUser->expiresIn ?? 3600
            ),
        ];

        if (!empty($socialUser->refreshToken)) {
            $tokenData['google_refresh_token'] =
                $socialUser->refreshToken;
        }

        if ($authType === 'register') {

            if ($user) {
                session()->forget('auth_type');

                return redirect('/')
                    ->with(
                        'error',
                        'Akun sudah terdaftar. Silakan login.'
                    );
            }

            $folderId = $this->createDriveFolder(
                json_decode(
                    $tokenData['google_access_token'],
                    true
                )
            );

            User::create([
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'password' => bcrypt(str()->random(16)),

                // default role USER
                'role_id' => 2,

                // menunggu approval
                'status' => 0,

                'google_drive_folder_id' => $folderId,

                ...$tokenData,
            ]);

            session()->forget('auth_type');

            return redirect('/')
                ->with(
                    'success',
                    'Registrasi berhasil. Menunggu verifikasi admin.'
                );
        }

        if (!$user) {
            session()->forget('auth_type');

            return redirect('/register')
                ->with(
                    'error',
                    'Akun tidak ditemukan. Silakan daftar terlebih dahulu.'
                );
        }

        if ($user->status != 1) {
            session()->forget('auth_type');

            return redirect('/')
                ->with(
                    'error',
                    'Akun Anda belum diverifikasi admin.'
                );
        }

        // kalau folder drive belum ada
        $folderId = $user->google_drive_folder_id;

        if (!$folderId) {
            $folderId = $this->createDriveFolder(
                json_decode(
                    $tokenData['google_access_token'],
                    true
                )
            );
        }

        $user->update([
            ...$tokenData,

            'google_refresh_token' =>
            $socialUser->refreshToken
                ?? $user->google_refresh_token,

            'google_drive_folder_id' => $folderId,
        ]);

        session()->forget('auth_type');

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

        $token = self::decodeGoogleToken($user->google_access_token);
        $client->setAccessToken($token);

        $service  = new Google_Service_Drive($client);
        $folderId = $user->google_drive_folder_id;

        if (!$folderId) {
            throw new \Exception('Folder Google Drive tidak ditemukan. Silakan login ulang dengan Google.');
        }

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

        $permission = new \Google_Service_Drive_Permission([
            'type' => 'anyone',
            'role' => 'reader',
        ]);

        $service->permissions->create(
            $file->id,
            $permission
        );

        return [
            'file_id' => $file->id,
            'url'     => 'https://drive.google.com/file/d/' . $file->id . '/view',
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Google_Client;
use Google_Service_Drive;

class GoogleController extends Controller
{
    // REDIRECT LOGIN GOOGLE
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
                // 'prompt' => 'consent',
            ])
            ->redirect();
    }

    public function redirectLogin()
    {
        session()->forget('selected_role');

        return Socialite::driver('google')
            ->scopes([
                'openid',
                'email',
                'profile',
                'https://www.googleapis.com/auth/drive.file',
            ])
            ->with([
                'access_type' => 'offline',
                // 'prompt' => 'consent',
            ])
            ->redirect();
    }

    public function redirectRegister($role)
    {
        session([
            'selected_role' => $role
        ]);

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

    // CALLBACK GOOGLE
    public function callback()
    {
        $googleUser = Socialite::driver('google')
            ->user();

        // cek user berdasarkan email
        $user = User::where(
            'email',
            $googleUser->email
        )->first();

        // ambil role dari session register
        $selectedRole = session(
            'selected_role'
        );

        // user baru
        if (!$user) {

            // kalau ga ada role berarti dari LOGIN
            if (!$selectedRole) {

                return redirect('/')
                    ->with(
                        'error',
                        'Akun belum terdaftar. Silakan register terlebih dahulu.'
                    );
            }

            // kalau ada role berarti dari REGISTER
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,

                // role dari dropdown
                'role_id' => $selectedRole,

                // pending approval
                'status' => 0,

                'password' => bcrypt(
                    str()->random(16)
                ),

                'google_access_token' =>
                json_encode([
                    'access_token' =>
                    $googleUser->token,

                    'expires_in' =>
                    $googleUser->expiresIn ?? 3600,

                    'created' => time(),
                ]),

                'google_token_expires_at' =>
                now()->addSeconds(
                    $googleUser->expiresIn ?? 3600
                ),

                'google_refresh_token' =>
                $googleUser->refreshToken,
            ]);

            // hapus session role
            session()->forget(
                'selected_role'
            );

            return redirect('/')
                ->with(
                    'error',
                    'Registrasi berhasil. Akun menunggu verifikasi admin.'
                );
        }

        // update token
        $user->update([
            'google_access_token' =>
            json_encode([
                'access_token' =>
                $googleUser->token,

                'expires_in' =>
                $googleUser->expiresIn ?? 3600,

                'created' => time(),
            ]),

            'google_token_expires_at' =>
            now()->addSeconds(
                $googleUser->expiresIn ?? 3600
            ),
        ]);

        // simpan refresh token kalau ada
        if ($googleUser->refreshToken) {

            $user->update([
                'google_refresh_token' =>
                $googleUser->refreshToken
            ]);
        }

        // belum diverifikasi admin
        if ($user->status != 1) {

            return redirect('/')
                ->with(
                    'error',
                    'Akun masih menunggu verifikasi admin.'
                );
        }

        Auth::login($user);

        // buat folder driver untuk masing masing user kalau belum ada
        $client = new Google_Client();
        $client->setClientId(
            config(
                'services.google.client_id'
            )
        );

        $client->setClientSecret(
            config(
                'services.google.client_secret'
            )
        );

        $token = json_decode(
            $user->google_access_token,
            true
        );

        $client->setAccessToken(
            $token
        );

        $service =
            new Google_Service_Drive(
                $client
            );

        if (
            !$user->google_drive_folder_id
        ) {

            $folderMetadata =
                new \Google_Service_Drive_DriveFile([
                    'name' =>
                    'E-Letter UNLA',

                    'mimeType' =>
                    'application/vnd.google-apps.folder',
                ]);

            $folder =
                $service->files->create(
                    $folderMetadata,
                    [
                        'fields' => 'id'
                    ]
                );

            $user->update([
                'google_drive_folder_id' =>
                $folder->id
            ]);
        }

        return redirect('/dashboard');
    }

    // UPLOAD KE GOOGLE DRIVE
    public static function uploadFileToDrive($uploadedFile)
    {
        $user = auth()->user();

        if (
            $user->google_token_expires_at &&
            now()->greaterThan($user->google_token_expires_at)
        ) {

            $client = new Google_Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));

            $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
            $newToken = $client->getAccessToken();

            $user->update([
                'google_access_token' => json_encode($newToken),
                'google_token_expires_at' => now()->addSeconds($newToken['expires_in']),
            ]);
        }

        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        $token = json_decode($user->google_access_token, true);
        $client->setAccessToken($token);

        $service = new Google_Service_Drive($client);

        $folderId = $user->google_drive_folder_id;

        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name' => $uploadedFile->getClientOriginalName(),
            'parents' => [$folderId],
        ]);

        $content = file_get_contents($uploadedFile->getRealPath());

        $file = $service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $uploadedFile->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id',
        ]);

        return [
            'file_id' => $file->id,
            'url' => 'https://drive.google.com/file/d/' . $file->id . '/view',
        ];
    }
}

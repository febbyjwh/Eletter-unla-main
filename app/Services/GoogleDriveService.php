<?php
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

class GoogleDriveService
{
    public function upload($file, $user)
    {
        $token = json_decode($user->google_access_token, true);

        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessToken($token);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);

            $newToken = $client->getAccessToken();

            $user->update([
                'google_access_token' => json_encode($newToken),
            ]);
        }

        $drive = new Drive($client);

        $folderId = $user->unit?->google_drive_folder_id;

        if (!$folderId) {
            throw new \Exception("Folder unit belum ada");
        }

        $fileMeta = new DriveFile([
            'name' => $file->getClientOriginalName(),
            'parents' => [$folderId],
        ]);

        $uploaded = $drive->files->create($fileMeta, [
            'data' => file_get_contents($file->getRealPath()),
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id, webViewLink'
        ]);

        $drive->permissions->create($uploaded->id, new Permission([
            'type' => 'anyone',
            'role' => 'reader'
        ]));

        return [
            'id' => $uploaded->id,
            'url' => $uploaded->webViewLink
        ];
    }
}
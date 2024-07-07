<?php

namespace app\service;
use Exception;
use Google\Client;
use Google\Service\Drive;

class SyncFileService
{

    public function uploadTasks($tasks): string
    {
            //AIzaSyBSegqyF1MVhgZHb07WeADeNlfBAoc3ZUs
        try {
            $client = new Client();
            $client->useApplicationDefaultCredentials();
            $client->addScope(Drive::DRIVE);
            $driveService = new Drive($client);
            $fileMetadata = new Drive\DriveFile(array(
                'name' => 'photo.jpg'));
            $content = file_get_contents('@app/image.jpg');
            $file = $driveService->files->create($fileMetadata, array(
                'data' => $content,
                'mimeType' => 'image/jpeg',
                'uploadType' => 'multipart',
                'fields' => 'id'));
            printf("File ID: %s\n", $file->id);
            return $file->id;
        } catch(Exception $e) {
            echo "Error Message: ".$e;
        }
    }


}
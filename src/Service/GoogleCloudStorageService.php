<?php

namespace App\Service;

use Google\Cloud\Storage\StorageClient;

class GoogleCloudStorageService
{
    private $storageClient;
    private $bucketName;

    public function __construct(string $projectId, string $keyFilePath)
    {
        $this->storageClient = new StorageClient([
            'projectId' => $projectId,
            'keyFilePath' => $keyFilePath,
        ]);

        // bucket name here
        $this->bucketName = 'adictizphotos';
    }


    /**
     * @return array
     * function qui retourne les images stockées dans google cloud storage
     */
    public function getAllImages(): array
    {
        $bucket = $this->storageClient->bucket($this->bucketName);
        $objects = $bucket->objects();

        $images = [];
        foreach ($objects as $object) {
            $images[] = $object->signedUrl(new \DateTime('tomorrow'));
        }

        return $images;
    }

    /**
     * function pour importer une image dans google cloud storage
     * @param string $filePath
     * @param string $destination
     * @return void
     */
    public function uploadImages(string $filePath, string $destination)
    {
        $bucket = $this->storageClient->bucket($this->bucketName);
        $bucket->upload(
            fopen($filePath, 'r'),
            [
                'name' => $destination
            ]
        );
    }

}
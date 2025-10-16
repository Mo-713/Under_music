<?php

namespace App\Service;

use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class S3UploaderService
{
    private $s3Client;
    private $bucket;

    public function __construct(string $accessKeyId, string $secretAccessKey, string $region, string $bucket)
    {
        $this->bucket = $bucket;

        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $accessKeyId,
                'secret' => $secretAccessKey,
            ],
            ]);
    }

    public function upload (UploadedFile $file, string $prefix= ''): string
    {
        //Définir le nom du fichier
        $fileName =uniqid() . '.' .$file->getClientOriginalExtension();

        //Définir le chemin de destination
        $destinationPath = $prefix . '/' . $fileName;

        //Stocker le fichier sur s3
        $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $destinationPath,
            'SourceFile' => $file->getPathname(),
            'ACL' => 'public-read' //Optionnel: Défini les autorisations d'accès
        ]);

        //Retourner l'URL du fichier
        return $this->s3Client->getObjectUrl($this->bucket, $destinationPath);
    }
}
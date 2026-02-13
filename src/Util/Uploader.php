<?php

namespace App\Util;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    public function __construct(private readonly string $directory)
    {
    }

    public function upload(UploadedFile $imageFile): string
    {
        $newFilename = uniqid() . '.' . $imageFile->guessExtension();
        // On déplace le fichier uploadé dans un répertoire présent dans "public"
        try {
            $imageFile->move($this->directory, $newFilename);
            return $newFilename;
        } catch (FileException $e) {
            throw new \Exception("Erreur lors de l'upload du fichier.");
        }
    }

}

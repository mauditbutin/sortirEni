<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    public function uploadFile(
        UploadedFile $file,
        string $directory,
        string $name='',
    )
    {
        $newFileName = ($name ? str_replace(' ', '-', $name) . '-' : '') . uniqid() . '.' . $file->guessExtension();
        $file->move($directory, $newFileName);
        return $newFileName;
    }

    public function deleteFile(string $fileName, string $directory)
    {
        return unlink($directory . DIRECTORY_SEPARATOR . $fileName);
    }

}

<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{

    public function __construct(
        private SluggerInterface $slugger,
    ) {

    }

    public function uploadFile(
        UploadedFile $file,
        string $directory,
        string $name='',
    ): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($name ?: $originalFilename);
        $newFileName = ($safeFilename . '-' . uniqid() . '.' . $file->guessExtension());

        try {
            $file->move($directory, $newFileName);
        } catch (FileException $e) {
            throw new \RuntimeException('Could not upload the file');
        }

        return $newFileName;
    }

    public function deleteFile(string $fileName, string $directory): void
    {
        $path = $directory . DIRECTORY_SEPARATOR . $fileName;
        if (is_file($path)) {
            unlink($path);
        }
    }

}

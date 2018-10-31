<?php

namespace App\Service ;

use Symfony\Component\HttpFoundation\File\Exception\FileException ;
use Symfony\Component\HttpFoundation\File\UploadedFile ;

class FileUploader
{
    private $targetDirectory ;

    public function __construct ( $targetDirectory )
    {
        $this -> targetDirectory = $targetDirectory ;
    }

    public function upload ( UploadedFile $file )
    {
        $fileName = md5 ( uniqid ()) . '.' . $file -> guessExtension ();
        try {
            $file -> move ( $this -> targetDirectory, $fileName );
        } catch ( FileException $e ) {
            var_dump($e->getMessage());
            exit;
        }

        return $fileName ;
    }

    public function getTargetDirectory ()
    {
        return $this -> targetDirectory ;
    }
}
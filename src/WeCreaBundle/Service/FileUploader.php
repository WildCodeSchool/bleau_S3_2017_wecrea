<?php
// src/AppBundle/Service/FileUploader.php
namespace WeCreaBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use WeCreaBundle\Entity\Carrousel;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file, Carrousel $carrousel)
    {
        $fileName = uniqid().'.'.$file->guessExtension();

        $carrousel->getImages()->setUrl($fileName);

        $file->move($this->targetDir, $fileName);
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}
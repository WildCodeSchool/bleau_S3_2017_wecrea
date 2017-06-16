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

    public function upload(UploadedFile $file, $object)
    {
        $fileName = uniqid().'.'.$file->guessExtension();

        $object->getImages()->setUrl($fileName);

        $file->move($this->targetDir, $fileName);
    }

    public function uploadImg(UploadedFile $file, $image)
    {
        $fileName = uniqid().'.'.$file->guessExtension();

        $image->setUrl($fileName);

        $file->move($this->targetDir, $fileName);
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}
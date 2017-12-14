<?php
// src/AppBundle/Service/FileUploader.php
namespace WeCreaBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

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

        if ($object->getImages()->getUrl() != null)
        {
	        $path = $this->getTargetDir() . '/' . $object->getImages()->getUrl();

	        if (file_exists($path)) {
		        unlink($path);
	        }
        }

        $object->getImages()->setUrl($fileName);
		//$object->getImages()->setAlt(str_replace('.' . $file->guessExtension(), '', $file->getClientOriginalName()));

        $file->move($this->targetDir, $fileName);

    }

    public function uploadImg(UploadedFile $file, $image)
    {
        $fileName = uniqid().'.'.$file->guessExtension();

        $image->setUrl($fileName);
		//$image->setAlt(str_replace($file->guessExtension(), '', $file->getClientOriginalName()));

        $file->move($this->targetDir, $fileName);
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }

    public function deleteImg($img){
        $path = $this->getTargetDir() . '/' . $img->getUrl();

        if (file_exists($path)) {
            unlink($path);
        }
    }
}
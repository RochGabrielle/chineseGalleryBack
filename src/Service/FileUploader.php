<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory, EntityManagerInterface $em )
    {
        $this->targetDirectory = $targetDirectory;
        $this->em = $em;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function uploadImage(Object $entity, $file, $size) {
  if( null !== $file) {
        $dir = './images';
        $imageName = str_replace(' ', '', $entity->getTitle());
        $fileName = $imageName.$size.'.'.$file->guessClientExtension();
        $setter = 'set'.$size.'picturename';
         try {
            $file->move($dir, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        $entity->$setter($fileName);
      }
}
}
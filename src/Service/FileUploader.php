<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Jeremytubbs\Deepzoom\Deepzoom;
use Jeremytubbs\Deepzoom\DeepzoomFactory;

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

    public function uploadImage(Object $entity, $file, $name_extension) {
  if( null !== $file) {
        $dir = './images';
        $imageName = str_replace(' ', '', $entity->getTitle());
        $fileName = $imageName.'_'.$entity->getId().'_'.$name_extension.'.'.$file->guessClientExtension();
        $setter = 'set'.ucfirst($name_extension);
         try {
            $file->move($dir, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        $entity->$setter($fileName);
        $this->createDZIFile($file);
      }
}

    public function uploadArtistImage(Object $entity, $file, $name_extension) {
  if( null !== $file) {
        $dir = './images/artist';
        $imageName = str_replace(' ', '', $entity->getName());
        $fileName = $imageName.'_'.$entity->getId().'_'.$name_extension.'.'.$file->guessClientExtension();
        $setter = 'set'.ucfirst($name_extension);
         try {
            $file->move($dir, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        $entity->$setter($fileName);
      }
}

 public function uploadBlogImage(Object $entity, $file, $name_extension) {
  if( null !== $file) {
        $dir = './images/blog';
        $imageName = str_replace(' ', '', $entity->getCreationDate()->format('d-m-Y')).'_'. str_replace(' ', '', $entity->getTitle());
        $fileName = $imageName.'_'.$entity->getId().'_'.$name_extension.'.'.$file->guessClientExtension();
        $setter = 'set'.ucfirst($name_extension);
         try {
            $file->move($dir, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        $entity->$setter($fileName);
      }
}

/**
 * Use deepzoom plugin to create zoomable DZI file for seadragon plugin
 */
public function createDZIFile($file)
    {
        $deepzoom = DeepzoomFactory::create([
            'path' => 'images', // Export path for tiles
            'driver' => 'imagick', // Choose between gd and imagick support.
            'format' => 'jpg',
        ]);
        // folder, file are optional and will default to filename
        $response = $deepzoom->makeTiles($file, 'file', 'folder');
    }
}
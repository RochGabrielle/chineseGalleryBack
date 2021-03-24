<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Jeremytubbs\Deepzoom\Deepzoom;
use Jeremytubbs\Deepzoom\DeepzoomFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileUploader
{
    private $targetDirectory;
    private $params;

    public function __construct($targetDirectory, EntityManagerInterface $em, ParameterBagInterface $params )
    {
        $this->targetDirectory = $targetDirectory;
        $this->em = $em;
        $this->params = $params;
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
        $folderName = $imageName.'_'.$entity->getId().'_'.$name_extension;
       //$fileName = $imageName.'_'.$entity->getId().'_'.$name_extension;
       var_dump('before file is moving?');
        $setter = 'set'.ucfirst($name_extension);
         try {
            $file->move($dir, $fileName);
            var_dump('file is moving?');
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            var_dump($e);
        }
        $entity->$setter($folderName);
        $this->params->get('images_dir');
        var_dump($this->params->get('images_dir').$fileName);
        $this->createDZIFile($this->params->get('images_dir').$fileName, $folderName);
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
public function createDZIFile($file, $folder)
    {
        $deepzoom = DeepzoomFactory::create([
            'path' => 'images', // Export path for tiles
            'driver' => 'imagick', // Choose between gd and imagick support.
            'format' => 'jpg',
        ]);
        // folder, file are optional and will default to filename
        var_dump($file);
        //$file = 'C:\chineseFineArtGallery\chineseGalleryBack\public\images\maralago_13_big.jpeg';
        //$filee = $file;
        $response = $deepzoom->makeTiles($file, $folder, $folder);
    }
}
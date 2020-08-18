<?php
// src/Service/FileUploader.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;


class EntityUpdater
{

  public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    
    /* Update the object $entity to update with the content if the content is different from the
    existing one or none existant
    */
    public function updateEntity( Object $entityToUpdate, string $entityName, string $content) {
  $getter = 'get'.ucfirst($entityName);
  $setter = 'set'.ucfirst($entityName);
  if((null == $entityToUpdate->$getter()) || ($entityToUpdate->$getter() !== $content)) {
   $entityToUpdate->$setter($content);
 }
}

public function updateEntityWithEntity( Object $entityToUpdate, string $entityName, string $content) {

  $entityClass = 'App\Entity\\'.ucfirst($entityName);
  $entity = $this->em->getRepository($entityClass)->findOneById($content);
  $getter = 'get'.ucfirst($entityName);
  $setter = 'set'.ucfirst($entityName);
  if((null == $entityToUpdate->$getter()) || ($entityToUpdate->$getter() !== $entity)) {
    $entityToUpdate->$setter($entity);
  }
}

public function updateEntityArrayWithEntity( Object $entityToUpdate, string $entityName, string $content) {

  $entityClass = 'App\Entity\\'.ucfirst($entityName);
  $entity = $this->em->getRepository($entityClass)->findOneById($content);
  $getter = 'get'.ucfirst($entityName);
  $setter = 'add'.ucfirst($entityName);
  if((null == $entityToUpdate->$getter()) ) {
    $entityToUpdate->$setter($entity);
  }
}

public function updateEntityWithSizeSizeCategory( Object $entityToUpdate, $sizes) {

  $sizeClass = 'App\Entity\Size';
  $sizecategoryClass = 'App\Entity\Sizecategory';
  $articleSizes = $entityToUpdate->getSizes();
  $sizes = json_decode ($sizes, true);
  if( null == $articleSizes) {
    foreach($sizes as $size) {
      $s = new $sizeClass();
      $s->setLength($size["length"]);
      $s->setWidth($size["width"]);
      $s->setSizecategory($this->em->getRepository($sizecategoryClass)->findOneById($size["sizecategoryId"]));
      $this->em->persist($s);
      $this->em->addSize($s);

    }
  } else {
    $articleSizeId = array();
    $sizeId = array();
    foreach($sizes as $size) {
      $sizeId[] = $size["sizecategoryId"];
    }
    foreach($articleSizes as $as) {
      if(!in_array($as->getId(),$sizeId)) {
        $entityToUpdate->removeSize($as);
      } else { 
        foreach($sizes as $s ) {
          if(isset($s['sizeId']) && $s['sizeId'] == $as->getId()){
            $as->setLength($s['length']);
            $as->setWidth($s['width']);
            $as->setSizecategory($this->em->getRepository($sizecategoryClass)->findOneById($s["sizecategoryId"]));
          }
        }

      }
      $articleSizeId[] = $as->getId();
    }
    foreach($sizes as $size) {
      if( !in_array($size['sizeId'], $articleSizeId) || $size['sizeId'] == 0) {
        $s = new $sizeClass();
        $s->setLength($size["length"]);
        $s->setWidth($size["width"]);
        $s->setSizecategory($this->em->getRepository($sizecategoryClass)->findOneById($size["sizecategoryId"]));
        $this->em->persist($s);
        $entityToUpdate->addSize($s);
      }
    }

  }

}

public function updateEntityWithField( Object $entityToUpdate, Object $translations, $languagesArray, $field) {
  $getter = "get".ucfirst($field);
  $setter = "set".ucfirst($field);
  foreach($languagesArray as $lang) {
   if(empty($entityToUpdate->translate($lang)->$getter()) || ($entityToUpdate->translate($lang)->$getter() != $translations->get($lang) )) {
    $entityToUpdate->translate($lang)->$setter($translations->get($lang));
    $entityToUpdate->mergeNewTranslations();
  }
}
}

public function updateArticleWithTitle( Object $entityToUpdate, Object $translations, $languagesArray) {
foreach($languagesArray as $lang) {
  $entityToUpdate->translate($lang)->setTitle($translations->get("title_".$lang));
}
  $entityToUpdate->mergeNewTranslations();
}



public function getSimpleElement( Object $entity, Array $entityInfo, Array $returnedInfoList) {
  foreach($entityInfo as $item) {
    $getter = "get".ucfirst($item);
    $returnedInfoList[$item][] = array("id" => $entity->$getter()->getId(), "placeholder" => $entity->$getter()->getPlaceholder());
  }
  return $returnedInfoList;
  
}


}
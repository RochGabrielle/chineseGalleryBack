<?php
// src/Service/FileUploader.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;


class EntityUpdater
{

  public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    
    /* Update the object $entity to update with the field content if the content is different from the
    existing one or none existant
    */
    public function updateEntity( Object $entityToUpdate, string $fieldName, string $content) {
  $getter = 'get'.ucfirst($fieldName);
  $setter = 'set'.ucfirst($fieldName);
  if((null == $entityToUpdate->$getter()) || ($entityToUpdate->$getter() !== $content)) {
   $entityToUpdate->$setter($content);
 }
}

public function updateEntityWithEntity( Object $entityToUpdate, string $entityName, string $content) {

  $entityClass = 'App\Entity\\'.ucfirst($entityName);
  $entity = $this->em->getRepository($entityClass)->findOneById($content);
  $getter = 'get'.ucfirst($entityName);
  $setter = 'set'.ucfirst($entityName);
  if( null !== $entity) {
  if((null == $entityToUpdate->$getter()) || ($entityToUpdate->$getter() !== $entity)) {
    $entityToUpdate->$setter($entity);
  }
}
}

public function updateEntityArrayWithEntity( Object $entityToUpdate, string $entityName, Array $contents) {

  $entityClass = 'App\Entity\\'.ucfirst($entityName);
  $getter = 'get'.ucfirst($entityName);
  $adder = 'add'.ucfirst($entityName);
  $remover = 'remove'.ucfirst($entityName);

  foreach ($contents as $content) {

  $entity = $this->em->getRepository($entityClass)->findOneById($content);
  if( null !== $entity) {
    $entityToUpdate->$adder($entity);
    //$entity->addArticle($entityToUpdate);
    //$this->em->persist($entity);
  }
    
    
  }  //dd('rrrzr');
    $ee = $entityToUpdate->$getter();
    $eeId = array();
    if ( null !== $ee) {
      foreach ($ee as $e) {
      $eeId[] = $e->getId();
    }
    // check if existing element in article are present in the new content, if not remove it
    foreach ($eeId as $e) {
     if( !in_array($e, $contents)) {
      $entityToUpdate->$remover($this->em->getRepository($entityClass)->findOneById($e));
     }
    }
    }  
    $this->em->persist($entityToUpdate);
  }

public function updateEntityWithSizeSizeCategory( Object $entityToUpdate, $sizes) {

  $sizeClass = 'App\Entity\Size';
  $sizecategoryClass = 'App\Entity\Sizecategory';
  $sizeRepository = $this->em->getRepository($sizeClass);
  $articleSizes = $entityToUpdate->getSizes();
  $formSizes = json_decode ($sizes, true);
  $nullParameterValues = array("0", "0.0");
  //dd($articleSizes);
  // if no size exist for the article and length and width are not null create new size and add it to article
  if( null == $articleSizes) {
    foreach($formSizes as $size) {
      if(!in_array($size["length"],$nullParameterValues) && !in_array($size["width"],$nullParameterValues)) {
      $s = new $sizeClass();
      $s->setLength($size["length"]);
      $s->setWidth($size["width"]);
      if( isset($size["sizecategoryId"]) && $size["sizecategoryId"] !== null && $size["sizecategoryId"] != 0) {
        $s->setSizecategory($this->em->getRepository($sizecategoryClass)->findOneById($size["sizecategoryId"]));
      }  
      $s->setArticle($entityToUpdate);
      $this->em->persist($s);
      $entityToUpdate->addSize($s);
}
    }
  } else {
    // for sizes that already exist in article
    $articleSizeId = array();
    $formSizeIds = array();
    // Removing existing sizes that are not in the form
    foreach($formSizes as $size) {
      if( isset($size["sizeId"]) && $size["sizeId"] !== null && $size["sizeId"] != 0) {
      $formSizeIds[] = $size["sizeId"];
    }
    }

    $i = 0;
    foreach($articleSizes as $as) {
      if(!in_array($as->getId(), $formSizeIds) ) {
        $this->em->remove($as);

        $i++;
      }
    } $this->em->persist($entityToUpdate);
    // adding or updating size for article
    foreach($formSizes as $s ) {
      // check if it is an existing size field
      if(isset($s['sizeId']) && $s['sizeId'] !== 0) {
        $existingSize = $sizeRepository->findOneById($s['sizeId']);
       if(!in_array($s["length"],$nullParameterValues) && !in_array($s["width"],$nullParameterValues)) {
         if(null !== $existingSize) {
            $existingSize = $sizeRepository->findOneById($s['sizeId']);
            $existingSize->setLength($s['length']);
            $existingSize->setWidth($s['width']);
            $existingSize->setSizecategory($this->em->getRepository($sizecategoryClass)->findOneById($s["sizecategoryId"]));
            $this->em->persist($existingSize);
          }
          } else {
            // remove size in form with length or width set to 0
            if(null !== $existingSize) {
             
            $this->em->remove($existingSize);
            }
          }
        } else {
       // It's a new size field, create it and add it to article 
       if(!in_array($s["length"],$nullParameterValues) && !in_array($s["width"],$nullParameterValues)) {
      $s = new $sizeClass();
      $s->setLength($size["length"]);
      $s->setWidth($size["width"]);
      if( isset($size["sizecategoryId"]) && $size["sizecategoryId"] !== null && $size["sizecategoryId"] != 0) {
        $s->setSizecategory($this->em->getRepository($sizecategoryClass)->findOneById($size["sizecategoryId"]));
      }  
      $s->setArticle($entityToUpdate);
      $this->em->persist($s);
      $entityToUpdate->addSize($s);
      $this->em->persist($entityToUpdate);
        }
        }
      }

  }
  $this->em->flush();
//dd('end of size');
}

/**
 * Add translation to fields in array of $field from a formdata
 **/
public function updateEntityWithField( Object $entityToUpdate, Object $translations, $languagesArray, Array $fields) {
  foreach($fields as $field ) {
  $getter = "get".ucfirst($field);
  $setter = "set".ucfirst($field);
  foreach($languagesArray as $lang) {
   if(empty($entityToUpdate->translate($lang)->$getter()) || ($entityToUpdate->translate($lang)->$getter() != $translations->get($lang) )) {
    if(null !== $translations->get($field."_".$lang)) {
    $entityToUpdate->translate($lang)->$setter($translations->get($field."_".$lang));
  }
  }
if($lang == 'cn_cn' && $field == 'description') {
  $entityToUpdate->translate('cn_cn')->$setter('');
}
}
}
$entityToUpdate->mergeNewTranslations();
}

/**
 * Add translation to fields in array of $field from a json
 **/
public function updateEntityWithJsonField( Object $entityToUpdate, Array $translations, $languagesArray, Array $fields) {
  foreach($fields as $field ) {
  $getter = "get".ucfirst($field);
  $setter = "set".ucfirst($field);
  foreach($languagesArray as $lang) {
   if(empty($entityToUpdate->translate($lang)->$getter()) || ($entityToUpdate->translate($lang)->$getter() != $translations[$field."_".$lang] )) {
    if(isset($translations[$field."_".$lang]) && $translations[$field."_".$lang] !== '') {
    $entityToUpdate->translate($lang)->$setter($translations[$field."_".$lang]);
  } 
  }
}
}
$entityToUpdate->mergeNewTranslations();
}




public function updateArticleWithTitle( Object $entityToUpdate, Object $translations, $languagesArray) {
foreach($languagesArray as $lang) {
  $entityToUpdate->translate($lang)->setTitle($translations->get("title_".$lang));
}
  $entityToUpdate->mergeNewTranslations();
}

/**
 * Add translation $field_$lang to field with name $field
 **/
public function updateEntityWithFieldTranslation( Object $entityToUpdate, Object $translationContent, $languagesArray, $field) {
  $getter = "get".ucfirst($field);
  $setter = "set".ucfirst($field);
  foreach($languagesArray as $lang) {
   if(empty($entityToUpdate->translate($lang)->$getter()) || ($entityToUpdate->translate($lang)->$getter() != $translationContent->get($lang) )) {
    $entityToUpdate->translate($lang)->$setter($translationContent->get($field.'_'.$lang));
    
  }
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
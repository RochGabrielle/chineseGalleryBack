<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;
use App\Entity\Navigation;
use App\Entity\Material;
use App\Entity\Category;
use App\Entity\Theme;
use App\Entity\Sizecategory;
use App\Entity\Article;
use App\Entity\Size;


class ApiArticleController extends Controller
{
 protected $languages = array("fr_fr", "en_gb");

    /**
     * @Route("/api/add_article", name="add_article", methods={"POST"})
     */
    public function addArticleAction( Request $request)
    {
      $content = $request->request;
$toto = "avant la boucle";
      if ((null !==$content->get('title')) && (null !==$content->get("birth")) && (null !==$content->get('price')) && (null !==$content->get("category"))) {
        $entityClass = 'App\Entity\Article';

        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->getRepository($entityClass)->findOneByTitle($content->get("title"));

        if (null == $entity) {
          $entity = new $entityClass();
          $entity->setTitle($content->get('title'));
          $entity->setReference($content->get('title'));
        }
        $this->updateEntity($entity, "birth", $content->get("birth"));
        $toto = $this->updateEntity($entity, "price", $content->get("price"));
        $this->updateEntityWithEntity($entity, "category", $content->get("category"), $entityManager);
        $this->updateEntityWithEntity($entity, "material", $content->get("material"), $entityManager); 
        $this->updateEntityWithEntity($entity, "artist", $content->get("artist"), $entityManager); 
        $this->updateEntityWithEntity($entity, "dynasty", $content->get("dynasty"), $entityManager); 
        $this->updateEntityWithEntity($entity, "discount", $content->get("discount"), $entityManager); 
        $this->updateEntityWithSizeSizeCategory($entity, $content->get("sizes"), $entityManager);
        $this->updateEntityWithDescription($entity, $content, $entityManager);

        $this->uploadImage($entity, $request->files->get('smallImage'),$entityManager,'small');
        $this->uploadImage($entity, $request->files->get('bigImage'),$entityManager,'big');

        $entityManager->persist($entity);
        $entityManager->flush();        
        $responseMessage = "the article has been added";
      } else {
        $responseMessage = "The Json is invalid";
      }
      $data = $this->get('jms_serializer')->serialize($responseMessage, 'json');

      $data = $this->get('jms_serializer')->serialize($responseMessage, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }


    /**
     * @Route("/api/articlelist", name="get_article_list", methods={"GET"})
     */
    public function getArticleListAction()
    {
      $entityClass = 'App\Entity\Article';

      if (class_exists($entityClass)) {
        $entityManager = $this->getDoctrine()->getManager();

        $articles = $entityManager->getRepository($entityClass)->findAll();

        $articleList = array();
        if( !empty($articles)) {
          $i =0;
          foreach ($articles as $element){
           $article = array();
           $article["title"] = $element->getTitle();
           $article["birth"] = $element->getBirth();
           $article["price"] = $element->getPrice();
           $article["smallimage"] = $element->getSmallpicturename();
           $article["bigimage"] = $element->getBigpicturename();
           foreach ($this->languages as $lang) {
            $article[$lang] = $element->translate($lang)->getDescription();
          }
          $simpleElementToReturn = array("category", "material","discount");

          foreach($simpleElementToReturn as $item) {
            $getter = "get".ucfirst($item);
            if( null !== $element->$getter()) {
            $article[$item][] = array("id" => $element->$getter()->getId(), "placeholder" => $element->$getter()->getPlaceholder());
          }
          }
          $simpleArtistDynastyToReturn = array("artist", "dynasty");
          foreach($simpleArtistDynastyToReturn as $item) {
            $getter = "get".ucfirst($item);
            if( null !== $element->$getter()) {
            $article[$item][] = array("id" => $element->$getter()->getId(), "name" => $element->$getter()->getName());
          }
          }


          $sizes = $element->getSizes();
          foreach($sizes as $s) {
            $article["sizes"][] = array("id" => $s->getId(), 
              "width" => $s->getWidth(),
              "length" => $s->getLength(),
              "sizecategory" => array("id" => $s->getSizecategory()->getId(),
                "placeholder" =>  $s->getSizecategory()->getPlaceholder()));
          }


          $articleList[] = $article;
        }
      }

           //$data = $this->get('jms_serializer')->serialize($translations, 'json');
          // $data = json_encode($translationList) ;
      $data = $this->get('jms_serializer')->serialize($articleList, 'json');
    } else { $data = "enity ".$element." doesn't exist.";}
    $response = new Response($data);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }


 /**
     * @Route("/api/articleGalleryList/{lang}", name="get_gallery_list", methods={"GET"})
     */
public function getSimpleArticleListAction( string $lang ) 
{
 $entityClass = 'App\Entity\Article';

 if (class_exists($entityClass)) {
  $entityManager = $this->getDoctrine()->getManager();

  $elements = $entityManager->getRepository($entityClass)->findAll();
  $elementList  = array();
  $i = 0;
  if($elements) {
   foreach($elements as $element) {
     $elem = array(
      "id" =>$element->getId(),
      "name" => $element->getName());
     $elementList[$i] = $elem;
     $i++;
   }
 }

 $data = $this->get('jms_serializer')->serialize($elementList, 'json');
} else {
  $data = "this element doesn't exist";
}
$response = new Response($data);
$response->headers->set('Content-Type', 'application/json');
return $response;
}

    /**
     * @Route("/api/uploadFile", name="upload_one_file", methods={"POST"})
     */
public function uploadOneFile (Request $request)
    {
        

        $article_id = $request->get('article_id');
        var_dump($article_id);
        die();
        $file = $request->files->get("filetest");
        $dir = './images';
        $name = "toto.jpg";
        $file->move($dir, $name);
        //$data = $this->get('jms_serializer')->serialize($data, 'json');
        $response = new Response("file uploaded");
$response->headers->set('Content-Type', 'application/json');
return $response;
    }


    

public function updateEntity( Object $entityToUpdate, string $entityName, string $content) {
  $getter = 'get'.ucfirst($entityName);
  $setter = 'set'.ucfirst($entityName);
  $toto = " je suis dans la boucle ".$entityToUpdate->$getter()." ".$content;
  if((null == $entityToUpdate->$getter()) || ($entityToUpdate->$getter() !== $content)) {
   $entityToUpdate->$setter($content);
 }
 return $toto;
}

public function updateEntityWithEntity( Object $entityToUpdate, string $entityName, string $content, Object $entityManager) {

  $entityClass = 'App\Entity\\'.ucfirst($entityName);
  $entity = $entityManager->getRepository($entityClass)->findOneById($content);
  $getter = 'get'.ucfirst($entityName);
  $setter = 'set'.ucfirst($entityName);
  if((null == $entityToUpdate->$getter()) || ($entityToUpdate->$getter() !== $entity)) {
    $entityToUpdate->$setter($entity);
  }
}

public function updateEntityWithSizeSizeCategory( Object $entityToUpdate, $sizes, Object $entityManager) {

  $sizeClass = 'App\Entity\Size';
  $sizecategoryClass = 'App\Entity\Sizecategory';
  $articleSizes = $entityToUpdate->getSizes();
  $sizes = json_decode ($sizes, true);
  if( null == $articleSizes) {
    foreach($sizes as $size) {
      $s = new $sizeClass();
      $s->setLength($size["length"]);
      $s->setWidth($size["width"]);
      $s->setSizecategory($entityManager->getRepository($sizecategoryClass)->findOneById($size["sizecategoryId"]));
      $entityManager->persist($s);
      $entityToUpdate->addSize($s);

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
            $as->setSizecategory($entityManager->getRepository($sizecategoryClass)->findOneById($s["sizecategoryId"]));
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
        $s->setSizecategory($entityManager->getRepository($sizecategoryClass)->findOneById($size["sizecategoryId"]));
        $entityManager->persist($s);
        $entityToUpdate->addSize($s);
      }
    }

  }

}

public function updateEntityWithDescription( Object $entityToUpdate, Object $translations, Object $entityManager) {
  foreach($this->languages as $lang) {
   if(empty($entityToUpdate->translate($lang)->getDescription()) || ($entityToUpdate->translate($lang)->getDescription() != $translations->get($lang) )) {
    $entityToUpdate->translate($lang)->setDescription($translations->get($lang));
    $entityToUpdate->mergeNewTranslations();
  }
}
}



public function getSimpleElement( Object $entity, Array $entityInfo, Array $returnedInfoList) {
  foreach($entityInfo as $item) {
    $getter = "get".ucfirst($item);
    $returnedInfoList[$item][] = array("id" => $entity->$getter()->getId(), "placeholder" => $entity->$getter()->getPlaceholder());
  }
  return $returnedInfoList;
  
}

public function uploadImage(Object $entity, $file, $entityManager, $size) {
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
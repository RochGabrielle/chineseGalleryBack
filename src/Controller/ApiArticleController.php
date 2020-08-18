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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\EntityUpdater;
use App\Service\StatusUpdater;



class ApiArticleController extends Controller
{
  public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/api/admin/add_article", name="add_article", methods={"POST"})
     */
    public function addArticleAction( Request $request, EntityUpdater $entityUpdater, FileUploader $fileUploader)
    {
      $content = $request->request;

      if ((null !==$content->get('title')) ) {
        $entityClass = 'App\Entity\Article';

        
        $entity = $this->em->getRepository($entityClass)->findOneByTitle($content->get("title"));
        if (null == $entity) {
          $entity = new $entityClass();
          $entity->setTitle($content->get('title'));
          $entity->setReference($content->get('title'));
          $entity->setStatus('1');
        }
        $entityUpdater->updateEntity($entity, "birth", $content->get("birth"));
        $entityUpdater->updateEntity($entity, "price", $content->get("price"));

        $entitytoUpdate = array("category", "material", "dynasty", "discount", "museum");
        foreach ($entitytoUpdate as $etu) {
          $entityUpdater->updateEntityWithEntity($entity, $etu, $content->get($etu));
        }
       // $entityUpdater->updateEntityArrayWithEntity($entity, "theme", $content->get("theme"), $entityManager);
               
        $entityUpdater->updateEntityWithSizeSizeCategory($entity, $content->get("sizes"));

        $translationToUpdate = array("description");
        foreach($translationToUpdate as $ttu) {
          $entityUpdater->updateEntityWithField($entity, $content, $this->getParameter('languages'), $ttu);
        }
        $entityUpdater->updateArticleWithTitle($entity, $content, $this->getParameter('languages'));
        if(!empty($request->files->get('smallImage'))) {
          $fileUploader->uploadImage($entity, $request->files->get('smallImage'),'small');
        }
        if(!empty($request->files->get('bigImage'))) {
        $fileUploader->uploadImage($entity, $request->files->get('bigImage'),'big');
      }
        $this->em->persist($entity);
        $this->em->flush();        
        $responseMessage = "the article has been added";
      } else {
        $responseMessage = "The Json is invalid";
      }

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

        $articles = $this->em->getRepository($entityClass)->findAll();

        $articleList = array();
        if( !empty($articles)) {
          $i =0;
          foreach ($articles as $element){
           $article = array();
           $article["id"] = $element->getId();
           $article["title"] = $element->getTitle();
           $article["birth"] = $element->getBirth();
           $article["price"] = $element->getPrice();
           $article["status"] = $element->getStatus();
           $article["smallimage"] = $element->getSmallpicturename();
           $article["bigimage"] = $element->getBigpicturename();
           foreach ($this->getParameter('languages') as $lang) {
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
     * @Route("/api/gallery/{type}/{lang}", name="get_gallery", methods={"GET"})
     */
    public function getArticleGalleryAction($type, $lang)
    {
      $entityClass = 'App\Entity\Article';
      $page = '';
      if($type == "gallery") {
        $page = 1;
      } elseif ($type == "main"){
        $page = 2;
      }

        $articles = $this->em->getRepository($entityClass)->findByStatus($page);

        $articleList = array();
        if( !empty($articles)) {
          $i =0;
          foreach ($articles as $element){
           $article = array();
           $article["id"] = $element->getId();
           $article["title"] = $element->getTitle();
           $article["birth"] = $element->getBirth();
           $article["price"] = $element->getPrice();
           $article["status"] = $element->getStatus();
           $article["smallimage"] = $element->getSmallpicturename();
           $article["bigimage"] = $element->getBigpicturename();
           
          $article[$lang] = $element->translate($lang)->getDescription();
          $article["title_cn"] = $element->translate("cn_cn")->getTitle();
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
      $data = $this->get('jms_serializer')->serialize($articleList, 'json');
   
    $response = new Response($data);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }


    /**
     * @Route("/api/admin/uploadFile", name="upload_one_file", methods={"POST"})
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

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/api/admin/status", name="update_status", methods={"POST"})
     */
    public function updateStatusAction( Request $request, StatusUpdater $statusUpdater)
    {
      $statusUpdater->updateStatus($request);
      $response = new Response("status has been updated");
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }


}
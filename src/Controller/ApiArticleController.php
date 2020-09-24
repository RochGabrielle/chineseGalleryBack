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

      if ((null !==$content->get('title'))) {
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

        $entitytoUpdate = array("category", "material", "dynasty", "artist","discount", "museum");
        foreach ($entitytoUpdate as $etu) {
          $entityUpdater->updateEntityWithEntity($entity, $etu, $content->get($etu));
        }
        if( null !== $content->get("themes")) {
          $t = explode(",", $content->get("themes"));
        $entityUpdater->updateEntityArrayWithEntity($entity, "theme", $t);
        }
   
        $entityUpdater->updateEntityWithSizeSizeCategory($entity, $content->get("sizes"));

        $translationToUpdate = array("description", "title");
          $entityUpdater->updateEntityWithField($entity, $content, $this->getParameter('languages'), $translationToUpdate);
        
        //$entityUpdater->updateArticleWithTitle($entity, $content, $this->getParameter('languages'));
        if(!empty($request->files->get('small'))) {
          $fileUploader->uploadImage($entity, $request->files->get('small'),'small');
        }
        if(!empty($request->files->get('big'))) {
        $fileUploader->uploadImage($entity, $request->files->get('big'),'big');
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
           $article["smallimage"] = $element->getSmall();
           $article["bigimage"] = $element->getBig();
           $traductionList = array("description", "title");
           foreach ($traductionList as $tl) {
            $getter = "get".ucfirst($tl);
             foreach ($this->getParameter('languages') as $lang) {
            $article[$tl."_".$lang] = $element->translate($lang)->$getter();
          }
           }
           
          $simpleElementToReturn = array("category", "material","discount", "museum");

          foreach($simpleElementToReturn as $item) {
            $getter = "get".ucfirst($item);
            if( null !== $element->$getter()) {
            $article[$item][] = array("id" => $element->$getter()->getId(), "placeholder" => $element->$getter()->getPlaceholder());
          } else {
            $article[$item][] = array("id" => 0, "placeholder" => "field undefined");
          }
          }
          $simpleArtistDynastyToReturn = array("artist", "dynasty");
          foreach($simpleArtistDynastyToReturn as $item) {
            $getter = "get".ucfirst($item);
            if( null !== $element->$getter()) {
            $article[$item][] = array("id" => $element->$getter()->getId(), "name" => $element->$getter()->getName());
          } else {
            $article[$item][] = array("id" => 0, "placeholder" => "field undefined");
          }
          }


          $sizes = $element->getSizes();
          foreach($sizes as $s) {
            $article["sizes"][] = array("id" => $s->getId(), 
              "width" => $s->getWidth(),
              "length" => $s->getLength(),
              "sizecategoryId" => null == $s->getSizecategory() ?  0: $s->getSizecategory()->getId(),
              "sizecategory" =>  null == $s->getSizecategory() ? "undefined":$s->getSizecategory()->getPlaceholder()
            );
          }

          $article["media"] = array("id" => 0,
                          "placeholder" => '');
            $article["theme"][] = array( "id" => 0,
                           "placehoder" => '',
                           "media" => '',
                           "mediaId" => 0);
          if(null !== $element->getTheme()  && !empty($element->getTheme())) {

            foreach($element->getTheme() as $t) {
$article["theme"][] = array( "id" => null == $t->getId()? '0': $t->getId(),
                           "placehoder" => null == $t->getPlaceholder()? '': $t->getPlaceholder(),
                           "media" => null == $t->getMedia()? '':$t->getMedia()->getPlaceholder(),
                           "mediaId" => null == $t->getMedia()? 0:$t->getMedia()->getId());
$article["media"] = array("id" => null == $t->getMedia()? '':$t->getMedia()->getId(),
                          "placeholder" => null == $t->getMedia()? 0:$t->getMedia()->getPlaceholder());
            }
          } 
          if(null == $element->getTheme() || (null !== $element->getTheme()  && empty($element->getTheme()))) {
            dd('in the condition');
                       
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
           $article["smallimage"] = $element->getSmall();
           $article["bigimage"] = $element->getBig();
           $themes = $element->getTheme();
           $theme = '';
           foreach ($themes as $t) {
             $theme = $theme . ' '.$t->getPlaceholder();
             $article["media"] = null == $t->getMedia()? '': $t->getMedia()->getPlaceholder();
           }
           $article["theme"] = $theme;

           
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
              "sizecategory" => array("id" => null == $s->getSizecategory()? 0: $s->getSizecategory()->getId(),
                                      "placeholder" =>  null == $s->getSizecategory()? '': $s->getSizecategory()->getPlaceholder()
                                    )
            );
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
      $responseMessage ="Status has been updated";
      $data = $this->get('jms_serializer')->serialize($responseMessage, 'json');

$response = new Response($data);

$response->headers->set('Content-Type', 'application/json');
return $response;
    }

}
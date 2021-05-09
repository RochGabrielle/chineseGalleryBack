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
use App\Entity\product;
use App\Entity\Theme;
use App\Entity\Sizecategory;
use App\Entity\Article;
use App\Entity\Size;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\EntityUpdater;
use App\Service\StatusUpdater;
use App\Service\ListGetter;



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
        // Check if this article already exist if not create it
        $entity = null;
        if( null !== $content->get('id') && 0 !== $content->get('id') && null !== $this->em->getRepository($entityClass)->findOneById($content->get('id'))) 
        {
          $entity = $this->em->getRepository($entityClass)->findOneById($content->get('id'));
        } elseif ( (null !== $content->get("title") && $content->get("title") !== '') && (null !== $content->get("artist") && $content->get("artist") !== '')
          && null !== $this->em->getRepository($entityClass)->findOneBy([
            "title" => $content->get("title"),
            "artist" => $content->get("artist")
          ])
        )
        {
          $entity = $this->em->getRepository($entityClass)->findOneBy([
            "title" => $content->get("title"),
            "artist" => $content->get("artist")
          ]);
        } elseif ( (null !== $content->get("title") && $content->get("title") !== '') && null !== $this->em->getRepository($entityClass)->findOneByTitle(
          $content->get("title")) )
          {
            $entity = $this->em->getRepository($entityClass)->findOneByTitle($content->get("title"));
          }
        
        if (null == $entity) {
          $entity = new $entityClass();
          $entity->setTitle($content->get('title'));
          $entity->setReference($content->get('title'));
        }

        $entityToUpdate = ['birth', 'price', 'size', 'highlight', 'status'];

        foreach($entityToUpdate as $etu )
        {
          if (null !== $content->get($etu))
          {
            $entityUpdater->updateEntity($entity, $etu, $content->get($etu));
          } else 
          {
            if( $etu == "price")
            {
              $entityUpdater->updateEntity($entity, $etu, "not for sale at the moment");
            } elseif ($etu == "highlight" || $etu == "status" )
            {
              $entityUpdater->updateEntity($entity, $etu, "0");
            } else
            {
              $entityUpdater->updateEntity($entity, $etu, "unknown");
            }    
          }
        }

        
        $entitytoUpdate = array("product", "material","form", "dynasty", "artist","discount", "museum", "theme");
        foreach ($entitytoUpdate as $etu) {
          if( null !== $content->get($etu))
          {
            $entityUpdater->updateEntityWithEntity($entity, $etu, null !== $content->get($etu) ? $content->get($etu) : "1");
          } else 
          {
            $entityUpdater->updateEntityWithEntity($entity, $etu, "1"); // set to unknown
          }
        }
        /*
        if( null !== $content->get("themes")) {
          $t = explode(",", $content->get("themes"));
        $entityUpdater->updateEntityArrayWithEntity($entity, "theme", $t);
        }
        if( null !== $content->get("sizes"))
        {
          $entityUpdater->updateEntityWithSizeSizeCategory($entity, $content->get("sizes"));
        }
        */

        $translationToUpdate = array("description", "title");
          $entityUpdater->updateEntityWithField($entity, $content, $this->getParameter('languages'), $translationToUpdate);
        
        //$entityUpdater->updateArticleWithTitle($entity, $content, $this->getParameter('languages'));
        if(!empty($request->files->get('small'))) {
          $fileUploader->uploadSmallImage($entity, $request->files->get('small'),'small');
        }
        if(!empty($request->files->get('big'))) {
        $fileUploader->uploadBigImage($entity, $request->files->get('big'),'big');
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
     * @Route("/api/admin/articlelist", name="get_article_list", methods={"GET"})
     */
    public function getArticleListAction(ListGetter $listGetter)
    {
      $entityClass = 'App\Entity\Article';

      if (class_exists($entityClass)) {

        $articles = $this->em->getRepository($entityClass)->findAll();

        $articleList = array();
        if( !empty($articles)) {
          $i =0;
          foreach ($articles as $element)
          {
            $articleList[] = $listGetter->getAllArticleInfoForEdit($element);
          }   
        }
           //$data = $this->get('jms_serializer')->serialize($translations, 'json');
          // $data = json_encode($translationList) ;
      $data = $this->get('jms_serializer')->serialize($articleList, 'json');
    } else { $data = "entity ".$entityClass." doesn't exist.";}
    $response = new Response($data);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }


    /**
     * @Route("/api/gallery/{type}/{lang}", name="get_gallery", methods={"GET"})
     */
    public function getArticleGalleryAction($type, $lang, ListGetter $listGetter)
    {
      $entityClass = 'App\Entity\Article';
      $artistClass = 'App\Entity\Artist';
      $page = '';
      if($type == "gallery") {
        $page = 1;
        $articles = $this->em->getRepository($entityClass)->findByStatus($page);
      } elseif ($type == "main"){
        $page = 2;
        $articles = $this->em->getRepository($entityClass)->findByStatus($page);
      } elseif ($type == "highlight"){
        $articles = $this->em->getRepository($entityClass)->findByHighlight('1'); // higlighted set to 1
      } elseif (is_numeric($type)){
        $artist = $this->em->getRepository($artistClass)->findOneById($type);
        if(null !==$artist)
        $articles = $this->em->getRepository($entityClass)->findByArtist($artist);
      }

        $articleList = array();
        if( !empty($articles)) {
          $i =0;
          foreach ($articles as $element){

          $articleList[] = $listGetter->getArticleInfoForGallery($element, $lang);
        }
      }
      $data = $this->get('jms_serializer')->serialize($articleList, 'json');
   
    $response = new Response($data);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

  /**
     * @Route("/api/gallery/art/{id}/{lang}", name="get_display_article", methods={"GET"})
     */
    public function getArticleForDisplayAction($id, $lang, ListGetter $listGetter)
    {
      $entityClass = 'App\Entity\Article';
      $artistClass = 'App\Entity\Artist';
      
      if(!is_numeric($id)) 
      {
         $data = "No correct id was provided";
      } else 
      {
        $article = $this->em->getRepository($entityClass)->findOneById($id);
        if(null == $article) 
        {
          $data = "No article has been found";
        } else 
        {
          $data = $listGetter->getArticleInfo($article, $lang);
        }     
     }
      $data = $this->get('jms_serializer')->serialize($data, 'json');
   
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

    /**
    *  set hightlight status of article  
     * is equal to 1 if the article is a highlight
     * @IsGranted("ROLE_ADMIN")
     * @Route("/api/admin/highlight", name="update_highlight", methods={"POST"})
     */
    public function updateHighlightAction( Request $request, StatusUpdater $statusUpdater)
    {
      $statusUpdater->updateHighlight($request);
      $responseMessage ="Highlight has been updated";
      $data = $this->get('jms_serializer')->serialize($responseMessage, 'json');

      $response = new Response($data);

      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }

     /**
     * @Route("/api/articleListOfArtist/{artistId}/{lang}", name="get_list_of_painting_of_artist", methods={"GET"})
     */
    public function getPaintingListOfArtistAction(int $artistId, string $lang, ListGetter $listGetter)
    {
      $entityClass = 'App\Entity\Article';
      $artistEntity = 'App\Entity\Artist';
      $artist = $this->em->getRepository($artistEntity)->findOneById($artistId);

      if( null !== $artist) {
        $paintingList = $this->em->getRepository($entityClass)->findByArtist($artist);
        $data = array();
        if( null !== $paintingList) {
          foreach( $paintingList as $painting) {
           
            $data[] = $listGetter->getArticleInfoForGallery($painting, $lang);
          }
        }
        
        $data = $this->get('jms_serializer')->serialize($data, 'json');



      } else { $data ="This artist doesn't exist in database";}

      $response = new Response($data);

      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }

    

}
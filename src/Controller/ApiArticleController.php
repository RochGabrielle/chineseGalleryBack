<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Navigation;
use App\Entity\Material;
use App\Entity\Category;
use App\Entity\Theme;
use App\Entity\Sizecategory;
use App\Entity\Article;


class ApiArticleController extends Controller
{
   protected $languages = array("fr_fr", "en_gb");

 /**
     * @Route("/api/add_article", name="add_article", methods={"POST"})
     */
 public function addArticleAction( Request $request)
 {
    $content = json_decode($request->getContent(), true);
    if (isset($content["title"]) && isset($content["birth"]) && isset($content["price"]) && isset($content["category"])) {
        $entityClass = 'App\Entity\Article';

            $entityManager = $this->getDoctrine()->getManager();
            $entity = $entityManager->getRepository($entityClass)->findOneByTitle($content["title"]);

            if (null == $entity) {
                $entity = new $entityClass();
                $entity->setTitle($content["title"]);
                $entity->setReference($content["title"]);
            }
                $this->updateEntity($entity, "birth", $content["birth"]);
                $this->updateEntity($entity, "price", $content["price"]);
                $this->updateEntityWithEntity($entity, "category", $content["category"], $entityManager);
                $this->updateEntityWithEntity($entity, "material", $content["material"], $entityManager);   

            $entityManager->persist($entity);
            $entityManager->flush();        
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
                   $article["category"][] = array("id" => $element->getCategory()->getId(), "placeholder" => $element->getCategory()->getPlaceholder());
                   $article["material"][] = array("id" => $element->getMaterial()->getId(), "placeholder" => $element->getMaterial()->getPlaceholder());
                   
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
     * @Route("/api/simpleArticleList/{entity}", name="get_simple_article_list", methods={"GET"})
     */
public function getSimpleElementListAction( string $entity )
{
   $entityClass = 'App\Entity\\'.ucfirst($entity);

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

public function updateEntity( Object $entityToUpdate, string $entityName, string $content) {
    $getter = 'get'.ucfirst($entityName);
    $setter = 'set'.ucfirst($entityName);
    if((null == $entityToUpdate->$getter()) || ($entityToUpdate->$getter() == $content)) {
                   $entityToUpdate->$setter($content);
                }
}

public function updateEntityWithEntity( Object $entityToUpdate, string $entityName, string $content, Object $entityManager) {

    $entityClass = 'App\Entity\\'.ucfirst($entityName);
    $entity = $entityManager->getRepository($entityClass)->findOneById($content);
    $getter = 'get'.ucfirst($entityName);
    $setter = 'set'.ucfirst($entityName);
    if((null == $entityToUpdate->$getter()) || ($entityToUpdate->$getter() == $entity)) {
                    $entityToUpdate->$setter($entity);
                }
}

}
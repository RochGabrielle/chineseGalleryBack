<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Navigation;
use App\Entity\Material;
use App\Entity\Artist;
use App\Entity\Category;
use App\Entity\Dynasty;
use App\Entity\Theme;
use App\Entity\Article;


class ApiController extends Controller
{

    /**
     * @Route("/api/translation_add", name="add_translation", methods={"POST"})
     */
    public function addTranslationAction( Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if (isset($content["placeholder"]) && isset($content["translation"]) && isset($content["entity"]) && isset($content["lang"])) {
            $entityClass = 'App\Entity\\'.ucfirst($content["entity"]);
            if (class_exists($entityClass)) {

            $entityManager = $this->getDoctrine()->getManager();
            $entity = $entityManager->getRepository($entityClass)->findOneByPlaceholder($content["placeholder"]);

            if ($entity) {
                $entity->translate($content["lang"])->setName($content["translation"]);
                $entity->mergeNewTranslations();
                $content = $content["lang"];              
            } else {
                $entity = new $entityClass();
                $entity->setPlaceholder($content["placeholder"]);
                $entity->translate($content["lang"])->setName($content["translation"]);
                $entity->mergeNewTranslations();
                $content = "new ".$content["entity"]." created.";
            }       
            
            $entityManager->persist($entity);
            $entityManager->flush();
        
            } else {
                $content = "the ".$entity." entity doesn't exist.";
            }
        } else {
            $content = "le json est invalide";
        }
        $data = $this->get('jms_serializer')->serialize($content, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/translation/{lang}/{entity}/{placeholder}", name="get_translation", methods={"GET"})
     */
    public function getTranslationAction (string $lang, string $entity, string $placeholder ='all') {
        $entityClass = 'App\Entity\\'.ucfirst($entity);
        if (class_exists($entityClass)) {
            $entityManager = $this->getDoctrine()->getManager();

        
        $placeholders = $entityManager->getRepository($entityClass)->findAll();
        $translations = array();
        if( !empty($placeholders)) {
            foreach($placeholders as $placeholder){
                $translations[$placeholder->getPlaceholder()] = $placeholder->translate($lang)->getName();
            }

           //$data = $this->get('jms_serializer')->serialize($translations, 'json');
           $data = json_encode($translations) ;
       } else {
        $data = $this->get('jms_serializer')->serialize($placeholder, 'json'); 
       }
   } else {
    $data = "this class doesn't exist";
   }

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

      /**
     * @Route("/api/article_add", name="add_article", methods={"POST"})
     */
    public function addArticleAction( Request $request)
    {
        $content = json_decode($request->getContent(), true);
      
      if($content) {
        //
        //$navigationMenu->translate('en')->setMenuName();

        
        //$entityManager->flush();   
        } else {
            $content = "le json est invalide";
        }
        $data = $this->get('jms_serializer')->serialize($content, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/add_element", name="add_element", methods={"POST"})
     */
    public function addElementAction( Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if (isset($content["name"]) && isset($content["birth"]) && isset($content["death"]) && isset($content["description"]), isset($content["lang"]), isset($content["entity"])) {
            $entityClass = 'App\Entity\\'.ucfirst($content["entity"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entity = $entityManager->getRepository($entityClass)->findOneByName($content["name"]);

            if ($entity) {
                if($entity->getBirth() != $content["birth"]) {
                    $entity->setBirth($content["birth"]);
                }
                if($entity->getDeath($content["death"])!=  $content["death"] ) {
                    $entity->setDeath($content["death"]);
                }
                if((!empty($entity->translate($content["lang"])->getDescription()) && $entity->translate($content["lang"])->getDescription() != $content["description"]) || empty($entity->translate($content["lang"])->getDescription())) {
                       $entity->translate($content["lang"])->setDescription() = $content["description"];
                       $entity->mergeNewTranslations();
                } 

                $content = "update dynasty";              
            } else {
                $entity = new $entityClass();
                $entity->setName($content["name"]);
                $entity->setBirth($content["birth"]);
                $entity->setDeath($content["death"]);
                $entity->translate($content["lang"])->setDescription($content["description"]);
                $entity->mergeNewTranslations();
                $content = "new ".$content["entity"]." created.";
            }       
            
            $entityManager->persist($entity);
            $entityManager->flush();
        
            } else {
                $content = "the ".$entity." entity doesn't exist.";
            }
        } else {
            $content = "le json est invalide";
        }
        $data = $this->get('jms_serializer')->serialize($content, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/add_discount", name="add_discount", methods={"POST"})
     */
    public function addDiscountAction( Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if (isset($content["name"]) && isset($content["value"]) ) {
            $entityClass = 'App\Entity\Discount');

            $entityManager = $this->getDoctrine()->getManager();
            $entity = $entityManager->getRepository($entityClass)->findOneByName($content["name"]);

            if ($entity) {
                if($entity->getDiscountValue() != $content["value"]) {
                    $entity->setDiscountValue($content["value"]);
                }

                $content = "update discount";              
            } else {
                $entity = new $entityClass();
                $entity->setName($content["name"]);
                $entity->setDiscountValue($content["value"]);
            }       
            
            $entityManager->persist($entity);
            $entityManager->flush();
        
            } else {
                $content = "the ".$entity." entity doesn't exist.";
            }
        } else {
            $content = "le json est invalide";
        }
        $data = $this->get('jms_serializer')->serialize($content, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/add_size_category", name="add_size_category", methods={"POST"})
     */
    public function addSizeCategoryAction( Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if (isset($content["name"]))  {
            $entityClass = 'App\Entity\SizeCategory');

            $entityManager = $this->getDoctrine()->getManager();
            $entity = $entityManager->getRepository($entityClass)->findOneByName($content["name"]);

            if (!$entity) {
                $entity = new $entityClass();
                $entity->setName($content["name"]);
                $entityManager->persist($entity);
                $entityManager->flush();
                }      
        } else {
            $content = "le json est invalide";
        }
        $data = $this->get('jms_serializer')->serialize($content, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/get_size_category", name="get_size_category", methods={"POST"})
     */
    public function getSizeCategoryAction( )
    {
            $entityClass = 'App\Entity\SizeCategory');

            $entityManager = $this->getDoctrine()->getManager();
            $sizeCategories = $entityManager->getRepository($entityClass)->findAll();
        $sizeCategoryList = array();
        if( !empty($sizeCategories)) {
            foreach($sizeCategories as $sizeCategory){
                array_push($sizeCategoryList, $sizeCategory->getCategoryName());
            }

           //$data = $this->get('jms_serializer')->serialize($translations, 'json');
           $data = json_encode($sizeCategoryList) ;
        $data = $this->get('jms_serializer')->serialize($content, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/getArtistsList", name="get_size_category", methods={"GET"})
     */
    public function getSizeCategoryAction( )
    {
            $entityClass = 'App\Entity\SizeCategory');

            $entityManager = $this->getDoctrine()->getManager();
            $sizeCategories = $entityManager->getRepository($entityClass)->findAll();
        $sizeCategoryList = array();
        if( !empty($sizeCategories)) {
            foreach($sizeCategories as $sizeCategory){
                array_push($sizeCategoryList, $sizeCategory->getCategoryName());
            }

           //$data = $this->get('jms_serializer')->serialize($translations, 'json');
           $data = json_encode($sizeCategoryList) ;
        $data = $this->get('jms_serializer')->serialize($content, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/getElementList/{element}", name="get_size_category", methods={"GET"})
     */
    public function getSizeCategoryAction( string $element )
    {
            $entityClass = 'App\Entity\\'.ucfirst($element));

 if (class_exists($entityClass)) {
            $entityManager = $this->getDoctrine()->getManager();
     
        $elements = $entityManager->getRepository($entityClass)->findAll();

        $elementList = array();
        if( !empty($elements)) {
            foreach($elements as $element){
                array_push($sizeCategoryList, $element->getName());
            }

           //$data = $this->get('jms_serializer')->serialize($translations, 'json');
           $data = json_encode($sizeCategoryList) ;
        $data = $this->get('jms_serializer')->serialize($content, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/getOneElement/{entity}/{lang}/{name}", name="get_one_element", methods={"GET"})
     */
    public function getOneElementAction( string $entity, string $lang, string $name )
    {
            $entityClass = 'App\Entity\\'.ucfirst($entity));

        if (class_exists($entityClass)) {
            $entityManager = $this->getDoctrine()->getManager();
     
        $element = $entityManager->getRepository($entityClass)->findOneByName($name);
        $elementDescriptionTranslation = $element->translate($lang)->getDescription();

        $data = $this->get('jms_serializer')->serialize($element, 'json');
        } else {
            $data = "this element doesn't exist";
        }
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}

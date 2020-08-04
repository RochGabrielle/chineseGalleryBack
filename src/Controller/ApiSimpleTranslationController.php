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


class ApiSimpleTranslationController extends Controller
{
 protected $languages = array("fr_fr", "en_gb");

 /**
     * @Route("/api/translation_add", name="add_translation", methods={"POST"})
     */
    public function addTranslationAction( Request $request)
    {
        $content = json_decode($request->getContent(), true);
        if (isset($content["placeholder"]) && isset($content["fr_fr"]) && isset($content["en_gb"]) && isset($content["entity"])) {
            $entityClass = 'App\Entity\\'.ucfirst($content["entity"]);
            if (class_exists($entityClass)) {

                $entityManager = $this->getDoctrine()->getManager();
                $entity = $entityManager->getRepository($entityClass)->findOneByPlaceholder($content["placeholder"]);
                $langs =array_values($this->languages);

                if ($entity) {
                    $i = 0;
                    while(isset($langs[$i])) {
                        if(empty($entity->translate($langs[$i])->getName()) || ($entity->translate($langs[$i])->getName() != $content[$langs[$i]]) ) {
                            $entity->translate($langs[$i])->setName($content[$langs[$i]]);
                            $entity->mergeNewTranslations();
                            $responseMsessage = "update of ". $content["placeholder"] . " in " .$content["entity"];   
                        }
                        $i++;
                    }
                } else {
                    $entity = new $entityClass();
                    $entity->setPlaceholder($content["placeholder"]);
                    foreach($this->languages as $lang) {
                        $entity->translate($lang)->setName($content[$lang]);
                        $entity->mergeNewTranslations();
                        $responseMsessage = "new ".$content["entity"]." created.";
                    }       
                }
                $entityManager->persist($entity);
                $entityManager->flush();

            } else {
                $responseMsessage = "the ".$entity." entity doesn't exist.";
            }
        } else {
            $responseMsessage = "le json est invalide";
        }
        $data = $this->get('jms_serializer')->serialize($responseMsessage, 'json');

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
     * @Route("/api/translationlist/{entity}", name="get_translation_list", methods={"GET"})
     */
    public function getTranslationListAction( string $entity )
    {
        $entityClass = 'App\Entity\\'.ucfirst($entity);

        if (class_exists($entityClass)) {
            $entityManager = $this->getDoctrine()->getManager();

            $placeholders = $entityManager->getRepository($entityClass)->findAll();

            $translationList = array();
            if( !empty($placeholders)) {
                $i =0;
                foreach ($placeholders as $element){
                 $translations = array();
                 $translations["placeholder"] = $element->getPlaceholder();
                 $translations["id"] = $element->getId();
                 foreach ($this->languages as $lang) {
                    $translations[$lang] = $element->translate($lang)->getName();
                }

                $translationList[$i] = $translations;
                $i++;
            }
        }

           //$data = $this->get('jms_serializer')->serialize($translations, 'json');
          // $data = json_encode($translationList) ;
        $data = $this->get('jms_serializer')->serialize($translationList, 'json');
    } else { $data = "enity ".$element." doesn't exist.";}
    $response = new Response($data);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
}

/**
     * @Route("/api/simpleElementList/{entity}", name="get_simple_element_list", methods={"GET"})
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

}
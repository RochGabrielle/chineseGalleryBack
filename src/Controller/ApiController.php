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
use App\Entity\Sizecategory;


class ApiController extends Controller
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

        if (isset($content["name"]) && 
            isset($content["birth"]) && 
            isset($content["death"]) && 
            isset($content["description"]) &&
            isset($content["lang"]) &&
            isset($content["entity"])) {
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
             $entity->translate($content["lang"])->setDescription($content["description"]);
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

        if (isset($content["placeholder"]) && 
            isset($content["discountrate"]) && 
            isset($content["en_gb"]) && 
            isset($content["fr_fr"]) ) {
            $entityClass = 'App\Entity\Discount';

        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->getRepository($entityClass)->findOneByPlaceholder($content["placeholder"]);

        if ($entity) {
            if($entity->getDiscountrate() != $content["discountrate"]) {
                $entity->setDiscountrate($content["discountrate"]);
            }
            foreach($this->languages as $lang) {
                if(empty($entity->translate($lang)->getName()) || ($entity->translate($lang)->getName() != $content[$lang]) ) {
                    $entity->translate($lang)->setName($content[$lang]);
                    
                    $responseMsessage = "update of ". $content["placeholder"];   
                }
            }
            $entity->mergeNewTranslations();
        } else {
            $entity = new $entityClass();
            $entity->setPlaceholder($content["placeholder"]);
            $entity->setDiscountrate($content["discountrate"]);
            foreach($this->languages as $lang) {
                $entity->translate($lang)->setName($content[$lang]);
                $entity->mergeNewTranslations();
                $responseMsessage = "new discount created.";
            }       
        }
        $entityManager->persist($entity);
        $entityManager->flush();  

    } else {
     $responseMsessage = "le json est invalide";
 }
 $data = $this->get('jms_serializer')->serialize($responseMsessage, 'json');

 $response = new Response($data);
 $response->headers->set('Content-Type', 'application/json');
 return $response;
}

    /**
     * @Route("/api/discountlist", name="discountlist", methods={"GET"})
     */
    public function discountListAction( Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $entityClass = 'App\Entity\Discount';

        $entityManager = $this->getDoctrine()->getManager();
        $allDiscount = $entityManager->getRepository($entityClass)->findAll();

        if ($allDiscount) {
            $discountList = array();
            $i = 0;
            foreach ($allDiscount as $discount) {
                $discountList[$i] = array( 
                    "placeholder" => $discount->getPlaceholder(),
                    "discountrate" => $discount->getDiscountrate());
                foreach($this->languages as $lang) {
                    $discountList[$i][$lang] = $discount->translate($lang)->getName();
                }                       
                $i++;
            }
        } else {
            $content = "le json est invalide";
        }
        $data = $this->get('jms_serializer')->serialize($discountList, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }



    

    /**
     * @Route("/api/getElementList/{element}", name="get_element_list", methods={"GET"})
     */
    public function getElementListAction( string $element )
    {
        $entityClass = 'App\Entity\\'.ucfirst($element);

        if (class_exists($entityClass)) {
            $entityManager = $this->getDoctrine()->getManager();

            $elements = $entityManager->getRepository($entityClass)->findAll();

            $elementList = array();
            if( !empty($elements)) {
                foreach($elements as $element){
                    array_push($sizeCategoryList, $element->getName());
                }
            }

           //$data = $this->get('jms_serializer')->serialize($translations, 'json');
            $data = json_encode($sizeCategoryList) ;
            $data = $this->get('jms_serializer')->serialize($content, 'json');
        } else { $data = "enity ".$element." doesn't exist.";}
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/getOneElement/{entity}/{lang}/{name}", name="get_one_element", methods={"GET"})
     */
    public function getOneElementAction( string $entity, string $lang, string $name )
    {
        $entityClass = 'App\Entity\\'.ucfirst($entity);

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

}

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
use App\Service\ListGetter;


class ApiSimpleTranslationController extends Controller
{

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

 /**
     * @Route("/api/admin/translation_add", name="add_translation", methods={"POST"})
     */
    public function addTranslationAction( Request $request)
    {
        $content = json_decode($request->getContent(), true);
        if (isset($content["placeholder"]) && isset($content["entity"])) {
            $entityClass = 'App\Entity\\'.ucfirst($content["entity"]);
            if (class_exists($entityClass)) {
                $entity = $this->em->getRepository($entityClass)->findOneByPlaceholder($content["placeholder"]);
                $langs =$this->getParameter('languages');

                if ($entity) {
                    $i = 0;
                    while(isset($langs[$i])) {
                        if( isset($content["name_".$langs[$i]]) && (empty($entity->translate($langs[$i])->getName()) || ( $entity->translate($langs[$i])->getName() != $content["name_".$langs[$i]])  )) {
                            $entity->translate($langs[$i])->setName($content["name_".$langs[$i]]);
                            $entity->mergeNewTranslations();
                            $responseMsessage = "update of ". $content["placeholder"] . " in " .$content["entity"];   
                        }
                        $i++;

                        if($content["entity"] == 'theme' && isset($content["mediaId"]) && $content["mediaId"] !== 0) {
                            $media = $this->em->getRepository('App\Entity\Media')->findOneById($content["mediaId"]);
                            if( null !== $media) {
                                $entity->setMedia($media);
                            }                        
                        }
                    }

                    if($content["entity"] == 'discount' && isset($content["discountrate"]) && $content["discountrate"] !== 0) {
                           
                                $entity->setDiscountrate($content["discountrate"]);                      
                        }
                } else {
                    $entity = new $entityClass();
                    $entity->setPlaceholder($content["placeholder"]);
                    foreach($this->getParameter('languages') as $lang) {
                        if( isset($content["name_".$lang])) {
                        $entity->translate($lang)->setName($content["name_".$lang]);
                        $entity->mergeNewTranslations();
                        $responseMsessage = "new ".$content["entity"]." created.";
                    }
                    }  
                    if($content["entity"] == 'theme' && isset($content["mediaId"]) && $content["mediaId"] !== 0) {
                            $media = $this->em->getRepository('App\Entity\Media')->findOneById($content["mediaId"]);
                            if( null !== $media) {
                                $entity->setMedia($media);
                            }                        
                        }

                    if($content["entity"] == 'discount' && isset($content["discountrate"]) && $content["discountrate"] !== 0) {
                           
                                $entity->setDiscountrate($content["discountrate"]);                      
                        }


                }
                $this->em->persist($entity);
                $this->em->flush();
                $responseMessage = "the  entity has been updated.";

            } else {
                $responseMessage = "the ".$entity." entity doesn't exist.";
            }
        } else {
            $responseMessage = "le json est invalide";
        }
        $data = $this->get('jms_serializer')->serialize($responseMessage, 'json');

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


            $placeholders = $this->em->getRepository($entityClass)->findAll();
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
     * @Route("/api/admin/translationlist/{entity}/{fullList}", name="get_translation_list", methods={"GET"})
     */
    public function getTranslationListAction( string $entity, string $fullList, ListGetter $listGetter )
    {
        $entityClass = 'App\Entity\\'.ucfirst($entity);

        if (class_exists($entityClass)) {

            $placeholders = $this->em->getRepository($entityClass)->findAll();
            $cond = trim($fullList);

        $data = $this->get('jms_serializer')->serialize($listGetter->getList($placeholders, $this->getParameter('languages'), $cond, $entity ), 'json');
    } else { $data = "enity ".$element." doesn't exist.";}
    $response = new Response($data);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
}   

}
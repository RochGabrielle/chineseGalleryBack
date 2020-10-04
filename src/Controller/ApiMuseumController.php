<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MuseumRepository;
use App\Service\EntityUpdater;
use App\Service\ListGetter;



class ApiMuseumController extends Controller
{
 private $museumRepo;


      public function __construct(MuseumRepository $museumRepo, EntityManagerInterface $em)
      {
           $this->museumRepo = $museumRepo;
           $this->em = $em;

       }
        

    /**
     * @Route("/api/admin/add_museum", name="add_museum", methods={"POST"})
     */
    public function addMuseumAction( Request $request, EntityUpdater $entityUpdater)
    {
        $content = json_decode($request->getContent(), true);
        if (isset($content["placeholder"]) && isset($content["name_en_gb"]) && isset($content["name_fr_fr"]) && isset($content["name_cn_cn"]) && isset($content["link"]) && isset($content["linkname"])) {
            $entityClass = 'App\Entity\Museum';
            if (class_exists($entityClass)) {

                $langs =$this->getParameter('languages');

                if (!isset($content["id"]) || $content["id"] == 0 || null == $this->museumRepo->findOneById($content["id"])) {
                    $entity = new $entityClass(); 
                } else {
                    $entity = $entity = $this->museumRepo->findOneById($content["id"]);
                }
                    
                    $i = 0;
                    while(isset($langs[$i])) {
                        if(empty($entity->translate($langs[$i])->getName()) || ($entity->translate($langs[$i])->getName() != $content['name_'.$langs[$i]]) ) {
                            $entity->translate($langs[$i])->setName($content['name_'.$langs[$i]]);
                            $entity->mergeNewTranslations();
                            $responseMsessage = "update of ". $content["placeholder"] . " in museum";   
                        }
                        $i++;
                    }
                    $dataToUpdate = array("placeholder", "link", "linkname");
                    foreach( $dataToUpdate as $dtu) {
                        $entityUpdater->updateEntity( $entity, $dtu, $content[$dtu]);
                    }
                    
                $this->em->persist($entity);
                $this->em->flush();

            } else {
                $responseMsessage = "the ".$entity." entity doesn't exist.";
            }
        } else {
            $responseMsessage = "le json est invalide";
        }
        $data = $this->get('jms_serializer')->serialize("museum added", 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    /**
     * @Route("/api/admin/museumlist", name="museum_list", methods={"GET"})
     */
    public function getMuseumListAction () {
       
            $fieldsToFetch = ["id", "placeholder", "link", "linkname"];

            $museums = $this->museumRepo->findAll();
            $museumList = array();
            if( !empty($museums)) { 
                foreach($museums as $museum){
                    $m = array();
                    foreach($fieldsToFetch as $ftf) {
                        $getter = "get".ucfirst($ftf);
                        $m[$ftf] = $museum->$getter();
                    }
                    foreach($this->getParameter('languages') as $lang) {
                        $getter = "get".ucfirst($ftf);
                        $m["name_".$lang] = $museum->translate($lang)->getName();
                    }


                    $museumList[] = $m; 
                }

           //$data = $this->get('jms_serializer')->serialize($translations, 'json');
                $data = json_encode($museumList) ;
            } else {
                $data = $this->get('jms_serializer')->serialize("no museum", 'json');
            }

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }





}
<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Artist;
use App\Entity\Dynasty;


class ApiElementController extends Controller
{
	protected $languages = array("fr_fr", "en_gb");

  /**
     * @Route("/api/add_element", name="add_element", methods={"POST"})
     */
  public function addElementAction( Request $request)
  {
  	$content = json_decode($request->getContent(), true);
    $dynastyEntity = 'App\Entity\Dynasty';

    if (isset($content["name"]) && 
      isset($content["birth"]) && 
      isset($content["death"]) && 
      isset($content["en_gb"]) &&
      isset($content["fr_fr"]) &&
      isset($content["entity"]) &&
      ($content["entity"] == "dynasty" ||
       ($content["entity"] == "artist" && isset($content["dynasty"]))
     )) {
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
     foreach($this->languages as $lang) {
       if(empty($entity->translate($lang)->getDescription()) || ($entity->translate($lang)->getDescription() != $content[$lang]) ) {
        $entity->translate($lang)->setDescription($content[$lang]);
        $entity->mergeNewTranslations();
        $responseMsessage = "update of ". $content["name"] . " in " .$content["entity"];   
      }
    }
    if($content["entity"] == "artist"){
      $dynastys = $entity->getDynasty();
      $dynastyIdList = array();
      foreach($dynastys as $dynasty){
        $dynastyIdList[] = $dynasty->getId();
        if( !in_array($dynasty->getId(), $content["dynasty"])) {
          $entity->removeDynasty($dynasty);
        }
      }
      foreach($content["dynasty"] as $dyn) {
        if(!in_array($entityManager->getRepository($dynastyEntity)->findOneById($dyn)->getId(), $dynastyIdList)){
          $entity->addDynasty($entityManager->getRepository($dynastyEntity)->findOneById($dyn));
        }
        
      }
    }
    $content = "update ". $content["entity"];

  } else {
    $entity = new $entityClass();
    $entity->setName($content["name"]);
    $entity->setBirth($content["birth"]);
    $entity->setDeath($content["death"]);
    foreach($this->languages as $lang) {
     $entity->translate($lang)->setDescription($content[$lang]);
   }
   $entity->mergeNewTranslations();
   if($content["entity"] == "artist"){
    foreach($content["dynasty"] as $dyn) {
      $entity->addDynasty($entityManager->getRepository($dynastyEntity)->findOneById($dyn));
    }
  }
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
     * @Route("/api/getElementList/{entity}", name="get_element_list", methods={"GET"})
     */
    public function getElementListAction( string $entity )
    {
    	$entityClass = 'App\Entity\\'.ucfirst($entity);

    	if (class_exists($entityClass)) {
    		$entityManager = $this->getDoctrine()->getManager();

    		$elements = $entityManager->getRepository($entityClass)->findAll();

    		$elementList = array();
    		if( !empty($elements)) {
    			$i =0;
    			foreach ($elements as $element){
    				$description = array();
    				$description["name"] = $element->getName();
    				$description["birth"] = $element->getBirth();
    				$description["death"] = $element->getDeath();
    				foreach ($this->languages as $lang) {
    					$description[$lang] = $element->translate($lang)->getDescription();
    				}

         
            if($entity == "artist") {
              $dynasty = $element->getDynasty();
              $dyn = array();
              $dynas =array();
              foreach ($dynasty as $d) {
                $dyn[] = array("id" => $d->getId(), "dyn" => $d->getName());
              }
              $description["dynasty"] = $dyn;
            }
    				$elementList[$i] = $description;
    				$i++;
    			}
    		}

    		$data = $this->get('jms_serializer')->serialize($elementList, 'json');
      
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
}
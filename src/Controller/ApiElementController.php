<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Artist;
use App\Entity\Dynasty;
use App\Service\ListGetter;
use App\Service\EntityUpdater;


class ApiElementController extends Controller
{
  public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

  /**
     * @Route("/api/admin/add_element", name="add_element", methods={"POST"})
     */
  public function addElementAction( Request $request, EntityUpdater $EntityUpdater)
  {
  	$content = json_decode($request->getContent(), true);
    $dynastyEntity = 'App\Entity\Dynasty';
    $fields = array("name", "description");

    if (isset($content["name"]) && 
      isset($content["name_cn_cn"]) && 
      isset($content["birth"]) && 
      isset($content["death"]) && 
      isset($content["description_en_gb"]) &&
      isset($content["description_fr_fr"]) &&
      isset($content["entity"]) &&
      ($content["entity"] == "dynasty" ||
       ($content["entity"] == "artist" && isset($content["dynasty"]))
     )) {
      $entityClass = 'App\Entity\\'.ucfirst($content["entity"]);

    $entityManager = $this->getDoctrine()->getManager();
    $entity = $this->em->getRepository($entityClass)->findOneByName($content["name"]);

    if ($entity) {
      if($entity->getBirth() != $content["birth"]) {
       $entity->setBirth($content["birth"]);
     }
     if($entity->getDeath($content["death"])!=  $content["death"] ) {
       $entity->setDeath($content["death"]);
     }

     $EntityUpdater->updateEntityWithJsonField($entity, $content, $this->getParameter('languages'), $fields);
     
    if($content["entity"] == "artist"){
      if( isset($content["dynasty"]) && null !== $content["dynasty"]) {
        $dynastys = $entity->getDynasty();
      $dynastyIdList = array();
      foreach($dynastys as $dynasty){
        $dynastyIdList[] = $dynasty->getId();
        if( !in_array($dynasty->getId(), $content["dynasty"])) {
          $entity->removeDynasty($dynasty);
        }
      }
      foreach($content["dynasty"] as $dyn) {
        if(!in_array($this->em->getRepository($dynastyEntity)->findOneById($dyn)->getId(), $dynastyIdList)){
          $entity->addDynasty($this->em->getRepository($dynastyEntity)->findOneById($dyn));
        }
        
      }
      }

    }
    $content = "update ". $content["entity"];

  } else {
    $entity = new $entityClass();
    $entity->setName($content["name"]);
    $entity->setBirth($content["birth"]);
    $entity->setDeath($content["death"]);
    
     $EntityUpdater->updateEntityWithJsonField($entity, $content, $this->getParameter('languages'), $fields);
   $entity->translate('cn_cn')->setDescription($content["name_cn_cn"]);
   $entity->mergeNewTranslations();
   if($content["entity"] == "artist"){
    if( isset($content["dynasty"]) && null !== $content["dynasty"]) {
foreach($content["dynasty"] as $dyn) {
      $entity->addDynasty($this->em->getRepository($dynastyEntity)->findOneById($dyn));
    }
  }
  }
  $content = "new ".$content["entity"]." created.";
}       

$this->em->persist($entity);
$this->em->flush();
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

    		$elements = $this->em->getRepository($entityClass)->findAll();

    		$elementList = array();
    		if( !empty($elements)) {
    			$i =0;
    			foreach ($elements as $element){
    				$description = array();
    				$description["name"] = $element->getName();
    				$description["birth"] = $element->getBirth();
    				$description["death"] = $element->getDeath();
    				foreach ($this->getParameter('languages') as $lang) {
    					$description['description_'.$lang] = $element->translate($lang)->getDescription();
    				}
            $description["name_cn_cn"] = $element->translate("cn_cn")->getName();

         
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
      
    	} else { $data = "entity ".$entity." doesn't exist.";}
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

    		$element = $this->em->getRepository($entityClass)->findOneByName($name);
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
     * @Route("/api/simpleElementList/{entity}", name="get_simple_element_list", methods={"GET"})
     */
    public function getSimpleElementListAction( string $entity, ListGetter $listGetter )
    {
      $entityClass = 'App\Entity\\'.ucfirst($entity);

      if (class_exists($entityClass)) {

        $elements = $this->em->getRepository($entityClass)->findAll();
       $data = $listGetter->getList($elements);
     } else {
      $data = "this element doesn't exist";
    }
    $response = new Response($data);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }
}
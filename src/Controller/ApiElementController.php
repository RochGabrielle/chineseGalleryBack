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
use App\Service\FileUploader;


class ApiElementController extends Controller
{
  public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

  /**
     * @Route("/api/admin/add_element", name="add_element", methods={"POST"})
     */
  public function addElementAction( Request $request, EntityUpdater $EntityUpdater, FileUploader $fileUploader)
  {
  	$content =  $request->request;
    $dynastyEntity = 'App\Entity\Dynasty';
    $fields = array("name", "description","introduction");
    $basicFieldList = array("name","birth", "death");
    var_dump($content);
    $result = "element succesfully added";
   

    if (null !== $content->get("id") &&
      null !== $content->get("name_en_gb") && 
      null !== $content->get("name_fr_fr") && 
      null !== $content->get("name_cn_cn") && 
      null !== $content->get("birth") && 
      null !== $content->get("death") && 
      null !== $content->get("description_en_gb") &&
      null !== $content->get("description_fr_fr") &&
      null !== $content->get("introduction_en_gb") &&
      null !== $content->get("introduction_fr_fr") &&
      null !== $content->get("entity") &&
      ($content->get("entity") == "dynasty" ||
       ($content->get("entity") == "artist" && null !== $content->get("dynasty")
     ))) {
      $entityClass = 'App\Entity\\'.ucfirst($content->get("entity"));
// the default name is the english name
      if($content->get("id") !== '' &&  $content->get("id") !== '0')
      {
        $entity = $this->em->getRepository($entityClass)->findOneById($content->get("id"));
      } else 
      {
        $entity = $this->em->getRepository($entityClass)->findOneByName($content->get("name_en_gb"));
      }
    
    $content->set("name", $content->get("name_en_gb"));


    if (null == $entity) {
      // if entity doesn't exist create it
        $entity = new $entityClass();
      }
      // Update all field if necessary

      foreach ($basicFieldList as $bf) {
        $EntityUpdater->updateEntity($entity,$bf, $content->get($bf));
      }

     $EntityUpdater->updateEntityWithField($entity, $content, $this->getParameter('languages'), $fields);

     
    if($content->get("entity") == "artist"){
      if( null !== $content->get("dynasty")) {
        $dynastys = $entity->getDynasty();
      $dynastyIdList = array();
      $selectedDynastys = explode( ',', $content->get("dynasty"));
      foreach($dynastys as $dynasty){
        $dynastyIdList[] = $dynasty->getId();
        if( !in_array($dynasty->getId(), $selectedDynastys)) {
          $entity->removeDynasty($dynasty);
        }
      }
      foreach ($selectedDynastys as $dyn) {
        if(null !== $this->em->getRepository($dynastyEntity)->findOneById($dyn) && !in_array($this->em->getRepository($dynastyEntity)->findOneById($dyn)->getId(), $dynastyIdList)){
          $entity->addDynasty($this->em->getRepository($dynastyEntity)->findOneById($dyn));
        }
        
      }
      }
// Upload of the pictures
       if(!empty($request->files->get('small'))) {
          $fileUploader->uploadArtistImage($entity, $request->files->get('small'),'small');
        }
        if(!empty($request->files->get('big'))) {
        $fileUploader->uploadArtistImage($entity, $request->files->get('big'),'big');
      }

    }
    $result = "update ". $content->get("entity");
     

$this->em->persist($entity);
$this->em->flush();
} else {
  $result =  "bad argument in the request";

}
$data = $this->get('jms_serializer')->serialize($content, 'json');

$response = new Response($data);
$response->headers->set('Content-Type', 'application/json');
return $response;
}


    /**
     * @Route("/api/admin/getElementList/{entity}", name="get_element_list", methods={"GET"})
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
            $description["id"] = $element->getId();
    				$description["name"] = $element->getName();
    				$description["birth"] = $element->getBirth();
    				$description["death"] = $element->getDeath();
            $description["small"] = $element->getSmall();
            $description["big"] = $element->getBig();
    				foreach ($this->getParameter('languages') as $lang) {
    					$description['description_'.$lang] = $element->translate($lang)->getDescription();
              $description['introduction_'.$lang] = $element->translate($lang)->getIntroduction();
              $description['name_'.$lang] = $element->translate($lang)->getName();
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
      
    	} else { $data = "entity ".$entity." doesn't exist.";}
    	$response = new Response($data);
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }

    /**
     * @Route("/api/getOneElement/{entity}/{lang}/{id}", name="get_one_element", methods={"GET"})
     */
    public function getOneElementAction( string $entity, string $lang, string $id )
    {
    	$entityClass = 'App\Entity\\'.ucfirst($entity);

    	if (class_exists($entityClass)) {

    		$element = $this->em->getRepository($entityClass)->findOneById($id);

        if( null !== $element) {
          $elementInfo = array();
          $elementInfo['name'] = $element->getName();
          $elementInfo['description'] = $element->translate($lang)->getDescription();
          $elementInfo['big'] = $element->getBig();  // profile picture
          $elementInfo['birth'] = $element->getBirth();
          $elementInfo['death'] = $element->getDeath(); 
          $data = $this->get('jms_serializer')->serialize($elementInfo, 'json');  
        } else {
          $data = "this element doesn't exist in database";
        }

    		
    	} else {
    		$data = "this entity doesn't exist";
    	}
    	$response = new Response($data);
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }

    /**
     * @Route("/api/simpleElementList/{entity}/{lang}", name="get_simple_element_list", methods={"GET"})
     */
    public function getSimpleElementListAction (string $entity, string $lang)
    {
        $entityClass = 'App\Entity\\'.ucfirst($entity);

      if (class_exists($entityClass)) {

        $elements = $this->em->getRepository($entityClass)->findAll();

        $elementList = array();
        if( !empty($elements)) {
          $i =0;
          foreach ($elements as $element){
            $description = array();
            $description['id'] = $element->getId();
            $description['name'] = $element->getName();
            $description['birth'] = $element->getBirth();
            $description['death'] = $element->getDeath();
            if ($entity == "artist")
            {
              $description['small'] = $element->getSmall();
              $description['introduction'] = $element->translate($lang)->getIntroduction();
            }
            if ($entity == "dynasty")
            {
              $description["description"] = $element->translate($lang)->getDescription();
              $description["open"] = 0;
              $description["fullDescription"] = false;
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

// this function get id for any type of element
  /** 
     * @Route("/api/getOneElementId/{entity}/{param}", name="get_one_element_by_name", methods={"GET"})
     */
    public function getOneElemenIdAction (string $entity, string $param)
    {
      $return = '';
      $entityClass = 'App\Entity\\'.ucfirst($entity);
      if (in_array($entity, ['artist', 'dynasty']))
      {
        $element = $this->em->getRepository($entityClass)->findOneByName($param);
      } elseif (in_array($entity, ['museum', 'category','material', 'discount','product']))
      {
        $element = $this->em->getRepository($entityClass)->findOneByPlaceholder($param);
      } else 
      {
        $return = $entity. ' is not a valid entity';
      }
      
      if($element == null) 
      {
        $return = "none";
      } else
      {
        $return = $element->getId();
      }
      $response = new Response(json_encode($return));
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }
}
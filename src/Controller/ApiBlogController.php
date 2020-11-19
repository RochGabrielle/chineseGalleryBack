<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ListGetter;
use App\Service\EntityUpdater;
use App\Service\FileUploader;


class ApiBlogController extends Controller
{
  public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

  /**
     * @Route("/api/admin/add_blog", name="add_blog", methods={"POST"})
     */
  public function addBlogAction( Request $request, EntityUpdater $EntityUpdater, FileUploader $fileUploader)
  {
  	$content =  $content = $request->request;
    $fields = array("title", "text","introduction");
    $basicFieldList = array("link", "linkname","heading");

    if (null !== $content->get("id") &&
      null !== $content->get("title_en_gb") && 
      null !== $content->get("title_fr_fr") && 
      null !== $content->get("title_cn_cn") && 
      null !== $content->get("text_en_gb") && 
      null !== $content->get("text_fr_fr") && 
      null !== $content->get("text_cn_cn") &&
      null !== $content->get("introduction_en_gb") && 
      null !== $content->get("introduction_fr_fr") && 
      null !== $content->get("introduction_cn_cn") &&
      null !== $content->get("link") &&
      null !== $content->get("linkname") &&
      null !== $content->get("heading")
     ) {
      $entityClass = 'App\Entity\Blog';

    $entity = $this->em->getRepository($entityClass)->findOneById($content->get("id"));

    if (null == $entity) {
      // create the blog if it doesn't exist yet
      $entity = new $entityClass(); 
      $entity->setCreationDate(new \DateTime());
    }
    // update fields if necessary.

      foreach ($basicFieldList as $bf) {
        $EntityUpdater->updateEntity($entity,$bf, $content->get($bf));
      }

     $EntityUpdater->updateEntityWithField($entity, $content, $this->getParameter('languages'), $fields);


// Upload of the pictures
       if(!empty($request->files->get('small'))) {
          $fileUploader->uploadBlogImage($entity, $request->files->get('small'),'small');
        }
        if(!empty($request->files->get('big'))) {
        $fileUploader->uploadBlogImage($entity, $request->files->get('big'),'big');
      }    

 $content = "Blog entry has been created.";
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
     * @Route("/api/admin/getBlogList/{genre}", name="get_blog_list", methods={"GET"})
     */
    public function getBlogListAction( string $genre )
    {
    	$entityClass = 'App\Entity\Blog';

    	if (class_exists($entityClass)) {

    		$elements = $this->em->getRepository($entityClass)->findAll();

    		$elementList = array();
    		if( !empty($elements)) {
    			$i =0;
    			foreach ($elements as $element){
    				$description = array();
            $description["id"] = $element->getId();
    				$description["link"] = $element->getLink();
    				$description["linkname"] = $element->getLinkname();
            $description["small"] = $element->getSmall();
            $description["big"] = $element->getBig();
            $description["heading"] = $element->getHeading();
    				foreach ($this->getParameter('languages') as $lang) {
    					$description['text_'.$lang] = $element->translate($lang)->getText();
              $description['introduction_'.$lang] = $element->translate($lang)->getIntroduction();
              $description['title_'.$lang] = $element->translate($lang)->getTitle();
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
     * @Route("/api/getOneBlog/{lang}/{id}", name="get_one_blog", methods={"GET"})
     */
    public function getOneBlogAction(string $lang, string $id )
    {
    	$entityClass = 'App\Entity\Blog';

    	if (class_exists($entityClass)) {

    		$element = $this->em->getRepository($entityClass)->findOneById($id);

        if( null !== $element) {
          $elementInfo = array();
          $elementInfo['id'] = $element->getId();
          $elementInfo['title'] = $element->translate($lang)->getTitle();
          $elementInfo['text'] = $element->translate($lang)->getText();
          $elementInfo['big'] = $element->getBig();  // profile picture
          $elementInfo['link'] = $element->getLink();
          $elementInfo['linkname'] = $element->getLinkname(); 
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
     * @Route("/api/blogListByHeading/{heading}/{lang}", name="get_blog_list_by_heading", methods={"GET"})
     */
    public function getBlogListByHeadingAction( string $heading, string $lang, ListGetter $listGetter )
    {
      $entityClass = 'App\Entity\Blog';

        $elements = $this->em->getRepository($entityClass)->findByHeading($heading);

        $elementList = array();
        if( !empty($elements)) {
          $i =0;
          foreach ($elements as $element){
            $description = array();
            $description["id"] = $element->getId();
            $description["title"] = $element->translate($lang)->getTitle();
             $description["introduction"] = $element->translate($lang)->getIntroduction();
            $description["small"] = $element->getSmall();
           
            $elementList[$i] = $description;
            $i++;
          }
        }

        $data = $this->get('jms_serializer')->serialize($elementList, 'json');
          
      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }

}
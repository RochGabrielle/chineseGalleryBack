<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SlideshowRepository;
use App\Service\FileUploader;
use App\Service\EntityUpdater;


class ApiSlideshowController extends Controller
{
	private $slideRepository;

	public function __construct(EntityManagerInterface $em, SlideshowRepository $slideRepository) {
        $this->em = $em;
        $this->slideRepository = $slideRepository;
    }

	/**
     * @Route("/api/admin/add_slide", name="add_slide", methods={"POST"})
     */
  public function addSlideAction( Request $request, EntityUpdater $entityUpdater, FileUploader $fileUploader)
  {
  	$content = $request->request;
    $entityClass = 'App\Entity\Slideshow';
    $entity = null;

    if ((null !==$content->get('title'))) {

        
        $entity = $this->slideRepository->findOneByTitle($content->get("title"));
      
        if (null == $entity) {
          $entity = new $entityClass();
          $entity->setTitle($content->get('title'));
          $entity->setStatus('1');
        }

      $fields = ["title", "subtitle"];

      foreach($fields as $f) {
        $entityUpdater->updateEntityWithFieldTranslation( $entity, $content, $this->getParameter('languages'), $f);
      }
 	
  $imageList = ["desktop", "tablet", "mobile"];
$this->em->persist($entity);
$this->em->flush();
  foreach( $imageList as $il) {
    if(!empty($request->files->get($il))) {
        $fileUploader->uploadImage($entity, $request->files->get($il),$il);
      }
  }
   $this->em->persist($entity);
   $this->em->flush();
  $data = "slide added";

} else {
  $data = "title is missing";
}
$data = $this->get('jms_serializer')->serialize($data, 'json');
$response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');
      return $response;
}


    /**
     * @Route("/api/slideList", name="get_slide_list", methods={"GET"})
     */
  public function getSlideListAction() {
    

        $slides = $this->slideRepository->findAll();

        $slideList = array();
        if( !empty($slides)) {
          $i =0;
          foreach ($slides as $s){
           $slide = array();
           $slide["id"] = $s->getId();
           $slide["title"] = $s->getTitle();
           $slide["status"] = $s->getStatus();
           $slide["desktop"] = $s->getDesktop();
           $slide["tablet"] = $s->getTablet();
           $slide["mobile"] = $s->getMobile();

           foreach ($this->getParameter('languages') as $lang) {
            $slide["title_".$lang] = ($s->translate($lang)->getTitle() == null)?'' : $s->translate($lang)->getTitle();
            $slide["subtitle_".$lang] = ($s->translate($lang)->getSubtitle() == null)?'' : $s->translate($lang)->getSubtitle();
          }
          $slideList[] = $slide;
        }
      }
      $data = $this->get('jms_serializer')->serialize($slideList, 'json');
    $response = new Response($data);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

/**
     * @Route("/api/slideshow/{lang}", name="get_slideshow", methods={"GET"})
     */
  public function getSlideshowAction($lang) {
    

        $slides = $this->slideRepository->findAll();

        $slideshow = array();
        if( !empty($slides)) {
          $i =0;
          foreach ($slides as $s){
            if($s->getStatus() == 1 ) {
              $slide = array();
              $slide["title"] = $s->translate($lang)->getTitle();
              $slide["subtitle"] = $s->translate($lang)->getSubtitle();
              $slide["desktop"] = $s->getDesktop();
              $slide["tablet"] = $s->getTablet();
              $slide["mobile"] = $s->getMobile();
          $slideshow[] = $slide;
        }
        }
      }
      $data = $this->get('jms_serializer')->serialize($slideshow, 'json');
    $response = new Response($data);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

}


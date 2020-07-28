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

}

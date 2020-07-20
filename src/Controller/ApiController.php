<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Navigation;

class ApiController extends Controller
{
    /**
     * @Route("/api/test", name="article_show")
     */
    public function showAction()
    {
        $article = 'word';
        $data = $this->get('jms_serializer')->serialize($article, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/translation_add/navigation", name="add_translation_menu", methods={"POST"})
     */
    public function addTranslationMenuAction( Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if (isset($content["menu"])) {

            $entityManager = $this->getDoctrine()->getManager();
            $navigationMenu = $entityManager->getRepository(Navigation::class)->findOneByPlaceholder($content["menu"]);

            if ($navigationMenu) {
                $navigationMenu->translate($content["lang"])->setName($content["translation"]);
                $content = $content["lang"];              
            } else {
                $navigationMenu = new Navigation();
                $navigationMenu->setPlaceholder($content["menu"]);
                $navigationMenu->translate($content["lang"])->setName($content["translation"]);
                $content = "nouvelle trad";
            }       
            
            $entityManager->persist($navigationMenu);
            $navigationMenu->mergeNewTranslations();
            $entityManager->flush();
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
     * @Route("/api/translation/{lang}/{component}/{placeholder}", name="get_translation_menu", methods={"GET"})
     */
    public function getTranslation (string $lang, string $component, string $placeholder) {

        $component = ucfirst($component);
        $entityManager = $this->getDoctrine()->getManager();
        $translation = $entityManager->getRepository(Navigation::class)->findOneByPlaceholder($placeholder);

        if( !empty($translation)) {
           $data = $this->get('jms_serializer')->serialize($translation->translate($lang)->getName(), 'json'); 
       } else {
        $data = $this->get('jms_serializer')->serialize($placeholder, 'json'); 
       }

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
 use Symfony\Component\Security\Core\User\UserInterface;

class SecurityController extends Controller
{

    private $passwordEncoder;

      public function __construct(UserPasswordEncoderInterface $passwordEncoder)
      {
           $this->passwordEncoder = $passwordEncoder;
       }

    /**
     * @Route("/api/auth/signin", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $response = new Response("signing in");
            $response->headers->set('Content-Type', 'application/json');
            return $response;
            
        // get the login error if there is one
    //    $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
   //     $lastUsername = $authenticationUtils->getLastUsername();
    }

    /**
     * @Route("api/logout", name="app_logout")
     */
    public function logout()
    {
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    $response = new Response("logged out");
    $response->headers->set('Content-Type', 'application/json');
    return $response;
    }

     /**
     * @Route("/api/auth/signup", name="create_user_account", methods={"POST"})
     */
    public function createUserAction( Request $request)
    {
      $content = json_decode($request->getContent(), true);

      $message = "json invalid";
      if (isset($content["email"]) && isset($content["username"]) && isset($content["password"])) {

        $entityClass = 'App\Entity\User';
        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->getRepository($entityClass)->findOneByEmail($content["email"]);
        if( null == $entity ) {
          $newUser = new $entityClass($content["email"]);
          $newUser->setPassword($this->passwordEncoder->encodePassword(
           $newUser,
           $content["password"]
         ));
          $newUser->setRoles((isset($content['role']))?[$content['role']]:['ROLE_USER']);
          $newUser->setPseudo($content["username"]);
          $entityManager->persist($newUser);
          $entityManager->flush($newUser);
          $message = "user updated";
        } else {
          $message = "An user with this email already exists";
          return  new JsonResponse([
            'err' => 'An user with this email already exists'
        ], 200);
        }       
      } 
      $data = $this->get('jms_serializer')->serialize($message, 'json');

      $response = new Response($data);
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }
}

<?php
namespace App\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use App\Repository\UserRepository;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\ApiToken;
use Doctrine\ORM\EntityManagerInterface;


class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    private $userRepository;
    private $router;
    private $passwordEncoder;
    private $entityManager;
    public function __construct(UserRepository $userRepository, RouterInterface $router, UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $entityManager )
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }
    public function supports(Request $request)
    {
        // returns true if the authentication is called from route app_login
         return $request->attributes->get('_route') === 'app_login'
         && $request->isMethod('POST');
    }
    public function getCredentials(Request $request)
    {
        //dd($request->request->get('email'));
        // data to be sent to getuser
         return [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
        ];
    }
     /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // get user using the user repository
        return $this->userRepository->findOneBy(['email' => $credentials['email']]);
    }
    public function checkCredentials($credentials, UserInterface $user)
    {
        // todo
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        dd($exception->getMessageKey());
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // add new token to user when user logs in
        $user = $token->getUser();
        $apiToken = new ApiToken($user);
        $this->entityManager->persist($apiToken);
        $this->entityManager->flush();

       $response = new Response("authentication success");
    $response->headers->set('Content-Type', 'application/json');

    return $response;
    }
    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }
    public function supportsRememberMe()
    {
        // todo
    }

    protected function getLoginUrl()
    {
       // dd('dans la redirection');
        // TODO: Implement getLoginUrl() method.
        return $this->router->generate('app_login');
    }
}
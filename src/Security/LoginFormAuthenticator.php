<?php
namespace App\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use App\Repository\UserRepository;
use Symfony\Component\Routing\RouterInterface;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $userRepository;
    private $router;
    public function __construct(UserRepository $userRepository, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }
    public function supports(Request $request)
    {
        // returns true if the authentication is called from route app_login
         return $request->attributes->get('_route') === 'app_login'
         && $request->isMethod('POST');
         dd('aa');
    }
    public function getCredentials(Request $request)
    {
        // data to be sent to getuser
         return [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
        ];
        dd($request->request->get('email'));
    }
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // get user using the user repository
        return $this->userRepository->findOneBy(['email' => $credentials['email']]);
    }
    public function checkCredentials($credentials, UserInterface $user)
    {
        // todo
        dd($credentials);
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // todo
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // todo
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
        // TODO: Implement getLoginUrl() method.
         return $this->router->generate('app_login');
    }
}
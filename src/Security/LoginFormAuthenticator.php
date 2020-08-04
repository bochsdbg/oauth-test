<?php

namespace App\Security;

use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /** @required */
    public EntityManagerInterface $em;
    /** @required */
    public RouterInterface $router;
    /** @required */
    public CsrfTokenManagerInterface $csrfTokenManager;
    /** @required */
    public UserPasswordEncoderInterface $passwordEncoder;

    public function supports(Request $request)
    {
        return 'backend' === $request->attributes->get('_route')
            && 'auth' === $request->attributes->get('page')
            && 'POST' === $request->getMethod();
    }

    public function getCredentials(Request $request)
    {
        $form = $request->request->get('form');

        $credentials = [
            'username' => trim($form['username']),
            'password' => $form['password'],
//            'csrf_token' => $form['_token'],
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $user_provider)
    {
//        $csrf_token = new CsrfToken('authenticate', $credentials['csrf_token']);
//        if (!$this->csrfTokenManager->isTokenValid($csrf_token)) {
//            throw new InvalidCsrfTokenException();
//        }

        $userRepo = $this->em->getRepository(User::class);
        $user = $userRepo->findOneBy(['username' => $credentials['username']]);
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('User could not be found');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$this->passwordEncoder->isPasswordValid($user, $credentials['password'])) {
            throw new CustomUserMessageAuthenticationException('Username and password do not match');
        }
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->router->generate('frontend'));
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('backend', ['page' => 'login']);
    }
}

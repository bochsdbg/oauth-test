<?php


namespace App\EventListener;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Trikoder\Bundle\OAuth2Bundle\Event\AuthorizationRequestResolveEvent;

final class AuthCodeListener
{
    /** @required */
    public UrlGeneratorInterface $urlGenerator;
    /** @required */
    public RequestStack $requestStack;
    /** @required */
    public UserRepository $userRepository;
    /** @required */
    public SessionInterface $session;

    public function onAuthorizationRequestResolve(AuthorizationRequestResolveEvent $event)
    {
        if (null !== $event->getUser()) {
            $event->resolveAuthorization(AuthorizationRequestResolveEvent::AUTHORIZATION_APPROVED);
        }
    }
}
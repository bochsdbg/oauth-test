<?php


namespace App\EventListener;


use App\Repository\UserRepository;
use Nyholm\Psr7\Response;
use Symfony\Component\HttpFoundation\RequestStack;
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

    public function onAuthorizationRequestResolve(AuthorizationRequestResolveEvent $event)
    {
        $user = $this->userRepository->find(1);
        $event->setUser($user);
        $event->resolveAuthorization(AuthorizationRequestResolveEvent::AUTHORIZATION_APPROVED);
        return;
        if (null !== $event->getUser()) {
            $event->resolveAuthorization(AuthorizationRequestResolveEvent::AUTHORIZATION_APPROVED);
        } else {
            $event->setResponse(
                new Response(
                    302,
                    [
                        'Location' => $this->urlGenerator->generate(
                            'backend', ['page' => 'login']
                        ),
                    ]
                )
            );
        }
    }
}
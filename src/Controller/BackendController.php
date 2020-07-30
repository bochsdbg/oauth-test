<?php

namespace App\Controller;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Trikoder\Bundle\OAuth2Bundle\Controller\AuthorizationController;
use Trikoder\Bundle\OAuth2Bundle\Controller\TokenController;

class BackendController extends AbstractController
{
    /**
     * @Route("/backend/{page}", name="backend", defaults={"page"=""})
     */
    public function index()
    {
        return $this->render('backend/index.html.twig', []);
    }

    /**
     * @Route("/backend/api/test")
     * @IsGranted("ROLE_OAUTH_ADMIN")
     */
    public function restrictedPage()
    {
        dd($this->getUser());

        return $this->json([]);
    }

    /**
     * @Route("/oauth/auth")
     */
    public function auth(AuthorizationController $controller, ServerRequestInterface $request, ResponseFactoryInterface $responseFactory)
    {
        return $controller->indexAction($request, $responseFactory);
    }

    /**
     * @Route("/oauth/token")
     */
    public function token(TokenController $controller, ServerRequestInterface $request, ResponseFactoryInterface $responseFactory)
    {
        return $controller->indexAction($request, $responseFactory);
    }
}

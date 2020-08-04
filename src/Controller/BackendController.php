<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Trikoder\Bundle\OAuth2Bundle\Controller\AuthorizationController;
use Trikoder\Bundle\OAuth2Bundle\Controller\TokenController;
use Trikoder\Bundle\OAuth2Bundle\League\Repository\AccessTokenRepository;
use Trikoder\Bundle\OAuth2Bundle\League\Repository\RefreshTokenRepository;
use Trikoder\Bundle\OAuth2Bundle\Model\AccessToken;

class BackendController extends AbstractController
{
    /**
     * @Route("/backend/{page}", name="backend", defaults={"page"=""})
     */
    public function index(AuthenticationUtils $authenticationUtils, string $page, SessionInterface $session)
    {
        if ('logout' === $page) {
            $session->clear();
            return $this->redirectToRoute('backend');
        }
        $authError = $authenticationUtils->getLastAuthenticationError();
        $user = $this->getUser();
        return $this->render('backend/index.html.twig', [
            'js_options' => [
                'auth_error' => $authError ? $authError->getMessage() : null,
                'auth_username' => $authenticationUtils->getLastUsername(),
                'user_username' => $user ? $user->getUsername() : null,
            ],
        ]);
    }

    /**
     * @Route("/backend/api/profile")
     * @IsGranted("ROLE_OAUTH_USER")
     */
    public function profile()
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->json([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'created_at' => $user->getCreatedAt()->getTimestamp(),
            'updated_at' => $user->getUpdatedAt()->getTimestamp(),
            'roles' => $user->getRoles(),
        ]);
    }

    /**
     * @Route("/backend/logout", name="backend_logout")
     */
    public function backendLogout()
    {
        return $this->render('backend/index.html.twig', []);
    }

    /**
     * @Route("/auth/signup", methods={"POST"})
     */
    public function signup(EntityManagerInterface $em, Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(), true);
        $errors = [];
        if (empty($data['username']) || !is_string($data['username'])) {
            $errors['username'] = 'Username is required to be a non-empty string';
        } else {
            $data['username'] = trim($data['username']);
            if (null !== $userRepository->findOneBy(['username' => $data['username']])) {
                $errors['username'] = 'User already registered';
            }
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password is required to be a non-empty string';
        }

        if (!empty($errors)) {
            return $this->json(['result' => 'error', 'errors' => $errors]);
        }

        $user = new User();
        $user
            ->setUsername($data['username'])
            ->setPassword($encoder->encodePassword($user, $data['password']))
            ->setRoles(['ROLE_USER', 'ROLE_OAUTH_USER']);
        $em->persist($user);
        $em->flush();

        return $this->json(['result' => 'ok']);
    }

    /**
     * @Route("/oauth/auth")
     */
    public function auth(AuthorizationController $controller, ServerRequestInterface $request, ResponseFactoryInterface $responseFactory, SessionInterface $session)
    {
        /**
         * @var User
         */
        $user = $this->getUser();

        if (!$user) {
            $session->set('return_to', $request->getUri());
            return $this->redirectToRoute('backend', ['page' => 'login']);
        }

        $response = $controller->indexAction($request, $responseFactory);

        // monkey patch to force a correct redirect (redirect_uri in a database requires a full url)
        $location = $response->getHeaderLine('location');
        if ('http://auth-server/auth' === substr($location, 0, 23)) {
            $location = '/frontend/auth' . substr($location, 23);
            return $response->withHeader('Location', $location);
        }
        return $response;
    }

    /**
     * @Route("/oauth/token")
     */
    public function token(TokenController $controller, ServerRequestInterface $request, ResponseFactoryInterface $responseFactory)
    {
        return $controller->indexAction($request, $responseFactory);
    }

    /**
     * @Route("/backend/api/tokens")
     * @IsGranted("ROLE_OAUTH_ADMIN")
     */
    public function apiTokenList(EntityManagerInterface $em, Request $request)
    {
        $whereClause = $request->get('show_revoked', false) ? '' : ' WHERE at.revoked=false ';
        $tokens = $em->createQuery('
            SELECT rt,at
            FROM Trikoder\Bundle\OAuth2Bundle\Model\RefreshToken rt 
                INNER JOIN rt.accessToken at ' . $whereClause
        )
            ->execute(null, AbstractQuery::HYDRATE_ARRAY);
        return $this->json($tokens);
    }

    /**
     * @Route("/backend/api/revoke_refresh_token/{id}")
     * @IsGranted("ROLE_OAUTH_ADMIN")
     */
    public function revokeRefreshToken(RefreshTokenRepository $tokenRepository, EntityManagerInterface $em, string $id)
    {
        $tokenRepository->revokeRefreshToken($id);
        return $this->json(['result' => 'ok']);
    }

    /**
     * @Route("/backend/api/revoke_access_token/{id}")
     * @IsGranted("ROLE_OAUTH_ADMIN")
     */
    public function revokeAccessToken(EntityManagerInterface $em, AccessTokenRepository $tokenRepository, string $id)
    {
        $tokenRepository->revokeAccessToken($id);
        $em->flush();
        return $this->json(['result' => 'ok']);
    }

    /**
     * @Route("/backend/api/revoke_user_tokens/{username}")
     * @IsGranted("ROLE_OAUTH_ADMIN")
     */
    public function revokeAllTokensForUser(EntityManagerInterface $em, string $username)
    {
        $em->createQuery('UPDATE ' . AccessToken::class . ' t SET t.revoked=true WHERE t.userIdentifier=:username')
            ->execute([':username' => $username]);
        return $this->json(['result' => 'ok']);
    }
}

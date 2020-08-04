<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    /**
     * @Route("/frontend/{page}", name="frontend", defaults={"page"=""})
     */
    public function index(ParameterBagInterface $parameterBag)
    {
        return $this->render('frontend/index.html.twig', [
            'frontend_settings' => $parameterBag->get('frontend'),
        ]);
    }
}

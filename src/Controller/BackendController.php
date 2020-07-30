<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{
    /**
     * @Route("/backend/{page}", name="backend", defaults={"page"=""})
     */
    public function index()
    {
        return $this->render('backend/index.html.twig', []);
    }
}

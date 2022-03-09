<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/front", name="front")
     */
    public function index(): Response
    {
        return $this->render('front_template/front.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

     /**
     * @Route("/back", name="back")
     */
    public function inde(): Response
    {
        return $this->render('baseBack.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }








     /**
     * @Route("/affichage", name="affichage")
     */
    public function indexeq(): Response
    {
        return $this->render('equipement/show.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}

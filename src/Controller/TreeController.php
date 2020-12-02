<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Link;
use App\Repository\LinkRepository;

class TreeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(LinkRepository $repo)
    {
        return $this->render('tree/home.html.twig', [
            'controller_name' => 'TreeController',
            'title' => "Lewys Tree",
            'links' => $links
        ]);
    }
}

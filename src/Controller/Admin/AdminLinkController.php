<?php

namespace App\Controller\Admin;

use App\Entity\Link;
use App\Form\LinkType;
use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminLinkController extends AbstractController
{
    /**
     * @Route("/admin" , name="admin_index")
     */
    public function index(LinkRepository $linkRepository)
    {
        $link = $linkRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'links' => $link
        ]);
    }

    /**
     * @Route("/admin/create" , name="admin_create")
     */
    public function create(Request $request)
    {
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        return $this->render('admin/create.html.twig', [
            'formLink' => $form->createView()
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig', [
            'title' => 'Home',
            'description' => 'Welcome to EcoSpot',
        ]);
    }

    #[Route('/p/{slug}', name: 'app_page')]
    public function show(string $slug): Response
    {
        // Load page data from database
        $page = [
            'title' => ucfirst($slug),
            'content' => 'Page content for ' . $slug,
            'slug' => $slug,
        ];

        return $this->render('pages/page.html.twig', [
            'page' => $page,
        ]);
    }
}

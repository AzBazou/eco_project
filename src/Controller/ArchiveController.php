<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArchiveController extends AbstractController
{
    #[Route('/archive/{page<\d+>}', name: 'app_archive', defaults: ['page' => 1])]
    public function index(Request $request, int $page = 1): Response
    {
        $postsPerPage = 10;
        
        // Load posts from database
        $posts = [
            [
                'title' => 'Sample Post 1',
                'slug' => 'sample-post-1',
                'excerpt' => 'This is a sample post excerpt',
                'date' => new \DateTime(),
                'format' => 'standard',
            ],
            [
                'title' => 'Sample Post 2',
                'slug' => 'sample-post-2',
                'excerpt' => 'This is another sample post excerpt',
                'date' => new \DateTime(),
                'format' => 'image',
            ],
        ];

        return $this->render('archive.html.twig', [
            'posts' => $posts,
            'page' => $page,
            'postsPerPage' => $postsPerPage,
            'total' => count($posts),
        ]);
    }
}

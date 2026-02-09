<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/{slug}', name: 'app_post_show')]
    public function show(string $slug): Response
    {
        // Load post data from database
        $post = [
            'title' => ucfirst(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'content' => 'Post content for ' . $slug,
            'author' => 'Admin',
            'date' => new \DateTime(),
            'format' => 'standard',
        ];

        return $this->render('post/single.html.twig', [
            'post' => $post,
        ]);
    }
}

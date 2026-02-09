<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request): Response
    {
        $query = $request->query->get('q', '');
        $results = [];

        if ($query) {
            // Search database for posts and pages
            $results = [
                [
                    'title' => 'Found Post about ' . $query,
                    'slug' => 'found-post',
                    'excerpt' => 'This is a search result',
                    'type' => 'post',
                ],
            ];
        }

        return $this->render('search.html.twig', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}

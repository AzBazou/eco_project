<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class EventsController extends AbstractController
{
    #[Route('/events', name: 'app_events')]
    public function index(EvenementRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchTerm = $request->query->get('q');
        
        $queryBuilder = $repository->createQueryBuilder('e')
            ->orderBy('e.dateDebut', 'DESC');

        if ($searchTerm) {
            $queryBuilder->andWhere('e.nom LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $query = $queryBuilder->getQuery();

        $events = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('events/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/events/{id}', name: 'app_events_show')]
    public function show(\App\Entity\Evenement $event): Response
    {
        return $this->render('events/show.html.twig', [
            'event' => $event,
        ]);
    }
}

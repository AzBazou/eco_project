<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;

#[Route('/admin', name: 'admin_')]
class AdminDashboardController extends AbstractController
{
    #[Route('', name: 'dashboard')]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'events' => $evenementRepository->findBy([], ['dateDebut' => 'DESC'], 6)
        ]);
    }
}

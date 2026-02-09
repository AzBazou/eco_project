<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SponsorController extends AbstractController
{
    #[Route('/sponsors', name: 'app_sponsors')]
    public function index(SponsorRepository $sponsorRepository): Response
    {
        return $this->render('sponsors/index.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }

    #[Route('/sponsors/{id}', name: 'app_sponsors_show')]
    public function show(Sponsor $sponsor): Response
    {
        return $this->render('sponsors/show.html.twig', [
            'sponsor' => $sponsor,
        ]);
    }
}

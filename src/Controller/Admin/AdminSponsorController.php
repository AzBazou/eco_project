<?php

namespace App\Controller\Admin;

use App\Entity\Sponsor;
use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin/sponsors', name: 'admin_sponsors_')]
class AdminSponsorController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(SponsorRepository $repository): Response
    {
        $sponsors = $repository->findAll();
        
        return $this->render('admin/sponsor/index.html.twig', [
            'sponsors' => $sponsors,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $sponsor = new Sponsor();
        $errors = [];
        
        if ($request->isMethod('POST')) {
            // Server-side validation
            $nom = trim($request->request->get('nom', ''));
            $description = trim($request->request->get('description', ''));
            $localisation = trim($request->request->get('localisation', ''));
            $secteur = trim($request->request->get('secteur', ''));
            
            // Populate entity with submitted data for repopulation
            $sponsor->setNom($nom);
            $sponsor->setDescription($description);
            $sponsor->setLocalisation($localisation);
            $sponsor->setSecteur($secteur);
            
            // Validate required fields
            if (empty($nom)) {
                $errors['nom'] = 'Sponsor name is required';
            }
            if (empty($description)) {
                $errors['description'] = 'Description is required';
            }
            if (empty($localisation)) {
                $errors['localisation'] = 'Location is required';
            }
            if (empty($secteur)) {
                $errors['secteur'] = 'Sector is required';
            }
            
            $imageFile = $request->files->get('image');
            if (!$imageFile) {
                $errors['image'] = 'Logo image is required';
            }
            
            // If there are no errors, save the sponsor
            if (empty($errors)) {
                try {
                    // Properties already set.
                    
                    if ($imageFile) {
                        $fileName = uniqid() . '.' . $imageFile->guessExtension();
                        $imageFile->move($this->getParameter('kernel.project_dir') . '/public/images/sponsors', $fileName);
                        $sponsor->setImage('/images/sponsors/' . $fileName);
                    }
                    
                    $em->persist($sponsor);
                    $em->flush();
                    
                    $this->addFlash('success', 'Sponsor created successfully!');
                    return $this->redirectToRoute('admin_sponsors_index');
                } catch (\Exception $e) {
                    $errors['form'] = 'Error saving sponsor: ' . $e->getMessage();
                }
            } else {
                $this->addFlash('error', 'Please fix the errors in the form.');
            }
        }
        
        return $this->render('admin/sponsor/form.html.twig', [
            'sponsor' => $sponsor,
            'isEdit' => false,
            'errors' => $errors,
        ], new Response('', empty($errors) ? 200 : 422));
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Sponsor $sponsor, Request $request, EntityManagerInterface $em): Response
    {
        $errors = [];
        
        if ($request->isMethod('POST')) {
            // Server-side validation
            $nom = trim($request->request->get('nom', ''));
            $description = trim($request->request->get('description', ''));
            $localisation = trim($request->request->get('localisation', ''));
            $secteur = trim($request->request->get('secteur', ''));
            
            // Populate entity with submitted data for repopulation
            $sponsor->setNom($nom);
            $sponsor->setDescription($description);
            $sponsor->setLocalisation($localisation);
            $sponsor->setSecteur($secteur);
            
            // Validate required fields
            if (empty($nom)) {
                $errors['nom'] = 'Sponsor name is required';
            }
            if (empty($description)) {
                $errors['description'] = 'Description is required';
            }
            if (empty($localisation)) {
                $errors['localisation'] = 'Location is required';
            }
            if (empty($secteur)) {
                $errors['secteur'] = 'Sector is required';
            }
            
            // If there are no errors, update the sponsor
            if (empty($errors)) {
                try {
                    // Properties already set.
                    
                    // Handle image upload
                    $imageFile = $request->files->get('image');
                    if ($imageFile) {
                        $fileName = uniqid() . '.' . $imageFile->guessExtension();
                        $imageFile->move($this->getParameter('kernel.project_dir') . '/public/images/sponsors', $fileName);
                        $sponsor->setImage('/images/sponsors/' . $fileName);
                    }
                    
                    $em->flush();
                    
                    $this->addFlash('success', 'Sponsor updated successfully!');
                    return $this->redirectToRoute('admin_sponsors_index');
                } catch (\Exception $e) {
                    $errors['form'] = 'Error saving sponsor: ' . $e->getMessage();
                }
            } else {
                $this->addFlash('error', 'Please fix the errors in the form.');
            }
        }
        
        return $this->render('admin/sponsor/form.html.twig', [
            'sponsor' => $sponsor,
            'isEdit' => true,
            'errors' => $errors,
        ], new Response('', empty($errors) ? 200 : 422));
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function delete(Sponsor $sponsor, EntityManagerInterface $em): Response
    {
        $em->remove($sponsor);
        $em->flush();
        
        $this->addFlash('success', 'Sponsor deleted successfully!');
        return $this->redirectToRoute('admin_sponsors_index');
    }
}

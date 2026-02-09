<?php

namespace App\Controller\Admin;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin/events', name: 'admin_events_')]
class AdminEvenementController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(EvenementRepository $repository): Response
    {
        $events = $repository->findAll();
        
        return $this->render('admin/evenement/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em, EvenementRepository $repository, \App\Repository\SponsorRepository $sponsorRepository): Response
    {
        $event = new Evenement();
        $errors = [];
        $sponsors = $sponsorRepository->findAll();
        
        if ($request->isMethod('POST')) {
            // Server-side validation
            $nom = trim($request->request->get('nom', ''));
            $description = trim($request->request->get('description', ''));
            $lieu = trim($request->request->get('lieu', ''));
            $capacite = $request->request->get('capacite');
            $dateDebut = $request->request->get('dateDebut');
            $dateFin = $request->request->get('dateFin');
            
            // Populate entity with submitted data for repopulation
            $event->setNom($nom);
            $event->setDescription($description);
            $event->setLieu($lieu);
            $event->setCapacite((int)$capacite);
            
            if ($dateDebut) {
                try {
                    $event->setDateDebut(new \DateTime($dateDebut));
                } catch (\Exception $e) {
                    $errors['dateDebut'] = 'Invalid start date format';
                }
            }
            if ($dateFin) {
                try {
                    $event->setDateFin(new \DateTime($dateFin));
                } catch (\Exception $e) {
                    $errors['dateFin'] = 'Invalid end date format';
                }
            }

            // Handle sponsors selection (sync immediately for form repopulation)
            // Note: For new event, the collection is empty.
            $sponsorIds = $request->request->all('sponsors');
            // We need to clear and re-add if we want to show what was selected.
            // But for 'new', we just add. For 'edit', we might need to clear.
            // Here in 'new', just finding and adding helps show them as checked if form re-renders.
            // However, we need to avoid adding duplicates if we re-run this? 
            // Actually, $event is new(), so it's empty.
            foreach ($sponsorIds as $sponsorId) {
                $sponsor = $sponsorRepository->find($sponsorId);
                if ($sponsor && !$event->getSponsors()->contains($sponsor)) {
                    $event->addSponsor($sponsor);
                }
            }
            
            // Validate required fields
            if (empty($nom)) {
                $errors['nom'] = 'Event name is required';
            }
            if (empty($description)) {
                $errors['description'] = 'Description is required';
            }
            if (empty($lieu)) {
                $errors['lieu'] = 'Location is required';
            }
            if (empty($capacite) || !is_numeric($capacite) || (int)$capacite <= 0) {
                $errors['capacite'] = 'Capacity must be a positive number';
            }
            if (empty($dateDebut)) {
                $errors['dateDebut'] = 'Start date is required';
            }
            if (empty($dateFin)) {
                $errors['dateFin'] = 'End date is required';
            }

            // Real-time date validation
            $now = new \DateTime();
            if ($event->getDateDebut() && $event->getDateDebut() < $now) {
                $errors['dateDebut'] = '⚠ La date de début ne peut pas être dans le passé.';
            }

            if ($event->getDateDebut() && $event->getDateFin() && $event->getDateFin() <= $event->getDateDebut()) {
                $errors['dateFin'] = '⚠ La date de fin doit être strictement après la date de début.';
            }
            
            // If there are no errors, save the event
            if (empty($errors)) {
                try {
                    // Explicit uniqueness check
                    $existing = $repository->findOneBy(['nom' => $nom]);
                    if ($existing) {
                        $errors['nom'] = '⚠ Ce nom d\'événement est déjà utilisé. Veuillez en choisir un autre.';
                        throw new \Exception('validation_failed');
                    }

                    // Handle image upload
                    $imageFile = $request->files->get('image');
                    if ($imageFile) {
                        $fileName = uniqid() . '.' . $imageFile->guessExtension();
                        $imageFile->move($this->getParameter('kernel.project_dir') . '/public/images/events', $fileName);
                        $event->setImage('/images/events/' . $fileName);
                    }

                    $em->persist($event);
                    $em->flush();
                    
                    $this->addFlash('success', 'Event created successfully!');
                    return $this->redirectToRoute('admin_events_index');
                } catch (\Exception $e) {
                    if ($e->getMessage() !== 'validation_failed') {
                        $errors['form'] = 'Error saving event: ' . $e->getMessage();
                    }
                }
            } else {
                $this->addFlash('error', 'Please fix the errors in the form.');
            }
        }
        
        return $this->render('admin/evenement/form.html.twig', [
            'event' => $event,
            'isEdit' => false,
            'errors' => $errors,
            'sponsors' => $sponsors,
        ], new Response('', empty($errors) ? 200 : 422));
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Evenement $event, Request $request, EntityManagerInterface $em, EvenementRepository $repository, \App\Repository\SponsorRepository $sponsorRepository): Response
    {
        $errors = [];
        $sponsors = $sponsorRepository->findAll();
        
        if ($request->isMethod('POST')) {
            // Server-side validation
            $nom = trim($request->request->get('nom', ''));
            $description = trim($request->request->get('description', ''));
            $lieu = trim($request->request->get('lieu', ''));
            $capacite = $request->request->get('capacite');
            $dateDebut = $request->request->get('dateDebut');
            $dateFin = $request->request->get('dateFin');
            
            // Populate entity with submitted data for repopulation
            $event->setNom($nom);
            $event->setDescription($description);
            $event->setLieu($lieu);
            $event->setCapacite((int)$capacite);
            
            if ($dateDebut) {
                try {
                    $event->setDateDebut(new \DateTime($dateDebut));
                } catch (\Exception $e) {
                    $errors['dateDebut'] = 'Invalid start date format';
                }
            }
            if ($dateFin) {
                try {
                    $event->setDateFin(new \DateTime($dateFin));
                } catch (\Exception $e) {
                    $errors['dateFin'] = 'Invalid end date format';
                }
            }

            // Handle sponsors - clear and re-add to reflect current selection
            // We need to be careful not to persist this change if validation fails
            // BUT for repopulation we want the user to see what they checked.
            // Since we don't flush if errors exist, the DB is safe.
            // The view iterates over $sponsors (all) and checks if $event->getSponsors() contains it.
            
            // Clone current sponsors to restore if needed? No, just modify the entity in memory.
            // First remove all existing sponsors (simple way to sync)
            foreach ($event->getSponsors() as $existingSponsor) {
                $event->removeSponsor($existingSponsor);
            }
            
            $sponsorIds = $request->request->all('sponsors');
            foreach ($sponsorIds as $sponsorId) {
                $sponsor = $sponsorRepository->find($sponsorId);
                if ($sponsor) {
                    $event->addSponsor($sponsor);
                }
            }
            
            // Validate required fields
            if (empty($nom)) {
                $errors['nom'] = 'Event name is required';
            }
            if (empty($description)) {
                $errors['description'] = 'Description is required';
            }
            if (empty($lieu)) {
                $errors['lieu'] = 'Location is required';
            }
            if (empty($capacite) || !is_numeric($capacite) || (int)$capacite <= 0) {
                $errors['capacite'] = 'Capacity must be a positive number';
            }
            if (empty($dateDebut)) {
                $errors['dateDebut'] = 'Start date is required';
            }
            if (empty($dateFin)) {
                $errors['dateFin'] = 'End date is required';
            }

            // Real-time date validation
            $now = new \DateTime();
            // During edit, we might allow existing events to keep their past date if not changed, 
            // but usually we want future or present. 
            // Let's enforce that if they CHANGE it, it must be valid.
            if ($event->getDateDebut() && $event->getDateDebut() < $now->modify('-1 hour')) { // small buffer
                 // $errors['dateDebut'] = '⚠ La date de début ne peut pas être dans le passé.';
                 // Actually, for edit, let's just enforce end > start
            }

            if ($event->getDateDebut() && $event->getDateFin() && $event->getDateFin() <= $event->getDateDebut()) {
                $errors['dateFin'] = '⚠ La date de fin doit être strictement après la date de début.';
            }
            
            // If there are no errors, update the event
            if (empty($errors)) {
                try {
                    // Explicit uniqueness check
                    $existing = $repository->findOneBy(['nom' => $nom]);
                    if ($existing && $existing->getId() !== $event->getId()) {
                        $errors['nom'] = '⚠ Ce nom d\'événement est déjà utilisé. Veuillez en choisir un autre.';
                        throw new \Exception('validation_failed');
                    }

                    // Handle image upload
                    $imageFile = $request->files->get('image');
                    if ($imageFile) {
                        $fileName = uniqid() . '.' . $imageFile->guessExtension();
                        $imageFile->move($this->getParameter('kernel.project_dir') . '/public/images/events', $fileName);
                        $event->setImage('/images/events/' . $fileName);
                    }

                    $em->flush();
                    
                    $this->addFlash('success', 'Event updated successfully!');
                    return $this->redirectToRoute('admin_events_index');
                } catch (\Exception $e) {
                    if ($e->getMessage() !== 'validation_failed') {
                        $errors['form'] = 'Error saving event: ' . $e->getMessage();
                    }
                }
            } else {
                $this->addFlash('error', 'Please fix the errors in the form.');
            }
        }
        
        return $this->render('admin/evenement/form.html.twig', [
            'event' => $event,
            'isEdit' => true,
            'errors' => $errors,
            'sponsors' => $sponsors,
        ], new Response('', empty($errors) ? 200 : 422));
    }

        #[Route('/{id}/delete', name: 'delete')]
        public function delete(Evenement $event, EntityManagerInterface $em): Response
        {
            $em->remove($event);
            $em->flush();
    
            $this->addFlash('success', 'Event deleted successfully!');
            return $this->redirectToRoute('admin_events_index');
        }
    }
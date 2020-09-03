<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\ConfirmDeletionFormType;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{

    private $manger;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }




    /**
     * lister les évenements
     * @Route("/liste_event", name="liste_event")
     */
    public function liste(EventRepository $event)
    {
        //dd($event);

        return $this->render('event/liste_event.html.twig', [
            'event_list' => $event->findAll(),
        ]);
    }

    /**
     * lister les évenements
     * @Route("/liste_event_profil", name="liste_event_profil")
     */
    public function liste_Evprofil(EventRepository $eventRepository)
    {
        $user = $this->getUser();
        //dd($user);

        $event = $eventRepository->findBy(['user' => $this->getUser()]);
        

        return $this->render('event/liste_event_profil.html.twig', [
            'event_list' => $event,
            'user' => $user
        ]);
    }



    /**
     * thémes évenements
     * @Route("/theme_event", name="theme_event")
     */
    public function theme()
    {
        return $this->render('event/theme_event.html.twig');
    }








    /**
     * Ajouter un évenement
     * @Route("/add_event", name="add_event")
     */
    public function add_event(Request $request)
    {
        $form = $this->createForm(EventFormType::class);
        $form->handleRequest($request);

         // Traitement du Formulaire de modification Pseudo ou Email
         if($form->isSubmitted() && $form->isValid())
         {
            $event = $form->getData();
            $user = $this->getUser();
            $event->setUser($user);


            $this->manager->persist($event);
             $this->manager->flush();
 
             $this->addFlash('success', 'Votre évenement à été ajouter!');
             return $this->redirectToRoute('liste_event_profil');
         }

         return $this->render('event/add_event.html.twig', [

            'add_event' => $form->createView(),
        ]);
    }


    /**
     * Modifier un évenement
     * @Route("/modif_event/{id}", name="modif_event")
     */
    public function modi_event(Request $request, Event $event)
    {
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

         // Traitement du Formulaire de modification Pseudo ou Email
         if($form->isSubmitted() && $form->isValid())
         {
             $this->manager->flush();
 
             $this->addFlash('success', 'Votre évenement à été modifier!');
             return $this->redirectToRoute('liste_event_profil');
         }

         return $this->render('event/add_event.html.twig', [

            'add_event' => $form->createView(),
        ]);
    }

    /**
     * suppression evenement
     * @Route("/event_delete/{id}", name="event_delete")
     * @IsGranted("DELETE_EVENT", subject="event")
     */
    public function supprim(Event $event, Request $request)
    {
        $deleteForm = $this->createForm(ConfirmDeletionFormType::class);
         $deleteForm->handleRequest($request);

         if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $this->manager->remove($event);
            $this->manager->flush();

            $this->addFlash('danger', 'L\'événement a été supprimée');
            return $this->redirectToRoute('liste_event_profil', [
                'id' => $event->getUser()->getId(),
            ]);
        }

        return $this->render('event/event_delete.html.twig', [
            'event' => $event,
            'delete_form' => $deleteForm->createView(),
        ]);

    }







}

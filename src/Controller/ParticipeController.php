<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\ConfirmDeletionFormType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class ParticipeController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }


    /**
     * @Route("/part/{id}", name="participe_event")
     * @IsGranted("ROLE_USER")
     */
    public function index(Event $event)
    {
            $this->addFlash('success', 'Vous participer à l\'événemnt');
            return $this->redirectToRoute('event_page', ['id' => $event->getId()]);
    }


    /**
     * @Route("/add_part/{id}", name="participe_profil")
     * * @IsGranted("ROLE_USER")
     */
    public function add_parti(Event $event, Request $request)
    {
        $token = $request->query->get('token');
        if ($this->isCsrfTokenValid('event_participation', $token)) {
 
           $this->getUser()->addParticipation($event);
            $this->manager->flush();
        }

        $this->addFlash('success', 'Vous avez participez à notre événement.');
        return $this->redirectToRoute('event_page', ['id' => $event->getId()]);
    }


    /**
     * supprimer particpation a un event par la page lister les evenement pour public
     * @Route("/delete_part/{id}", name="participe_profil_delete")
     *
     */
    public function delete_parti(Event $event, Request $request)
    {
        $deleteForm = $this->createForm(ConfirmDeletionFormType::class);
        $deleteForm->handleRequest($request);

        //dd($deleteForm);


        if ($deleteForm->isSubmitted() && $deleteForm->isValid())
        {
            $token = $request->query->get('token');
            if ($this->isCsrfTokenValid('delete_event', $token))
            {

                $this->getUser()->removeParticipation($event);
                $this->manager->flush();
            }

            $this->addFlash('danger', 'Vous avez annuler votre participation à l\'événement');
            return $this->redirectToRoute('event_page', ['id' => $event->getId()]);
        }
        return $this->render('event/event_delete.html.twig', [
            'event' => $event,
            'delete_form' => $deleteForm->createView(),
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


    



    /**
     * Liste participation par profil
     * @Route("/Liste_part", name="participe_profil_liste")
     */
    public function liste_parti(EventRepository $eventRepository)
    {
        $event = $eventRepository->findBy(['user' => $this->getUser()]);
            //dd($event);
        return $this->render('participe/liste_part_profil.html.twig',[
            'event' => $event,
        
        ]);
    }


    /**
     * supprimer une participation à un event depuis la page liste particiaption de l'utilisateur
     * @Route("/delete_part_profil/{id}", name="delete_participe_profil")
     *
     */
    public function delete_parti_prof(Event $event, Request $request)
    {
        $deleteForm = $this->createForm(ConfirmDeletionFormType::class);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid())
        {
            $token = $request->query->get('token');
            if ($this->isCsrfTokenValid('delete_event', $token)) {

                $this->getUser()->removeParticipation($event);
                $this->manager->flush();
            }

            $this->addFlash('danger', 'Vous avez annuler votre participation à l\'événement');
            return $this->render('participe/liste_part_profil.html.twig');

        }

        return $this->render('event/event_delete.html.twig', [
            'event' => $event,
            'delete_form' => $deleteForm->createView(),
        ]);


    }




}

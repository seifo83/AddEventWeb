<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
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
        if ($this->isCsrfTokenValid('EVENT_PARTICIPATION', $token)) {
 
           $this->getUser()->addParticipation($event);
            $this->manager->flush();
        }
        
        $this->addFlash('success', 'Vous participez à l\'événement');
        return $this->redirectToRoute('event_page', ['id' => $event->getId()]);
    }


    /**
     * @Route("/delete_part/{id}", name="participe_profil_delete")
     *
     */
    public function delete_parti(Event $event, Request $request)
    {
        $token = $request->query->get('token');
        if ($this->isCsrfTokenValid('delete_event', $token)) {

            $this->getUser()->removeParticipation($event);
            $this->manager->flush();
        }
        
        $this->addFlash('danger', 'Vous avez annuler votre participation à l\'événement');
        return $this->redirectToRoute('event_page', ['id' => $event->getId()]);
    }



    /**
     * Liste participation par profil
     * @Route("/Liste_part", name="participe_profil_liste")
     */
    public function liste_parti()
    {
        return $this->render('participe/liste_part_profil.html.twig');
    }


    /**
     * @Route("/delete_part_profil/{id}", name="delete_participe_profil")
     *
     */
    public function delete_parti_prof(Event $event, Request $request)
    {
        $token = $request->query->get('token');
        if ($this->isCsrfTokenValid('delete_event', $token)) {

            $this->getUser()->removeParticipation($event);
            $this->manager->flush();
        }
        
        $this->addFlash('danger', 'Vous avez annuler votre participation à l\'événement');
        return $this->render('participe/liste_part_profil.html.twig');
    }




}

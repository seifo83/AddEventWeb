<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ParticipeController extends AbstractController
{
    /**
     * @Route("/part/{id}", name="participe_event")
     * @IsGranted("ROLE_USER")
     */
    public function index(Event $event)
    {
        dd($event);

            $this->addFlash('success', 'Vous participer à l\'événemnt');
            return $this->redirectToRoute('event_page', ['id' => $event->getId()]);
    }
}

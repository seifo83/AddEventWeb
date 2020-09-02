<?php

namespace App\Controller;

use App\Form\EventFormType;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/event", name="event")
     */
    public function index()
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
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
             $this->manager->flush();
 
             $this->addFlash('success', 'Votre évenement à été mise à jour !');
             return $this->redirectToRoute('app_logout');
         }

         return $this->render('event/add_event.html.twig', [

            'add_event' => $form->createView(),
        ]);
    }



}

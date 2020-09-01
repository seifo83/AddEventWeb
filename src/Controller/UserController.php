<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{


    private $manger;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }


    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/inscrit", name="inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $form = $this->createForm(InscriptionFormType::class);   // on  enlevé $user et on l'appel dans le test if avec $user = $form->getData(); 
         $form->handleRequest($request);


         if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setDateEnregistrement(new \DateTime('now'));
            $password = $form->get('plainPassword')->getData();

            //hash du mot de passe et création du jeton 
            $user->setPassword($passwordEncoder->encodePassword($user, $password));

            //$entityManager = $this->getDoctrine()->getManager(); => cette ligne est remplacer l' appel a EntityManagerInterface dans la methode
            $this->manager->persist($user);
            $this->manager->flush();
     


            $this->addFlash('success', 'Vous avez bien été inscrit ! ');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/inscription.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    
}

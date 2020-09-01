<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountProfilType;
use App\Form\InscriptionFormType;
use App\Form\MotdepasseAccountType;
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



    /**
     * Account de User
     * @Route("/Profil", name= "account_profil")
     */
    public function profilUser()
    {
        $user = $this->getUser();

        return $this->render('user/profil.html.twig', [
            'user' => $user,
        
        ]);
    }




    /**
     * Modifier information personnel
     * @Route("/info_profil" , name="info_profil")
     */
    public function infoProfil(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountProfilType::class, $user);
        $form->handleRequest($request);

         // Traitement du Formulaire de modification Pseudo ou Email
         if($form->isSubmitted() && $form->isValid())
         {
             $this->manager->flush();
 
             $this->addFlash('success', 'Votre Profile à été mise à jour, Un email de confirmation vous a été envoyé.');
             return $this->redirectToRoute('app_logout');
         }

         return $this->render('user/infoPerso.html.twig', [
            'user' => $user,
            'account_profil' => $form->createView(),
        ]);

    }



    /**
     * Modifier mot de passe
     * @Route("/mdp_profil" , name="mdp_profil")
     */
    public function mdpProfil(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();

        $form2 = $this->createForm(MotdepasseAccountType::class, $user);
        $form2->handleRequest($request);


        // Traitement du Formulaire de modification du mots de passe 
        if($form2->isSubmitted() && $form2->isValid())
        {
            //récupération des données de formuliare(entité user + mot de passe)
            $password = $form2->get('plainPassword')->getData();
            //dd($password);

            //hash du mot de passe et création du jeton 
            $user->setPassword($passwordEncoder->encodePassword($user, $password));


            $this->manager->flush();

            $this->addFlash('success', 'Votre mots de passe à été mise à jour, Un email de confirmation vous a été envoyé.');
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('user/mdpPerso.html.twig', [
            'user' => $user,
            'mdp_account' => $form2->createView(),
        ]);





    }





}

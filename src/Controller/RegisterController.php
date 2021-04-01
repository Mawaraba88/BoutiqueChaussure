<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request,  UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            //Encodage du mot de passe
            $password =$encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            //dd($user);
            // Enregistrer les info dans la base
           $this->entityManager->persist($user);
            $this->entityManager->flush();

            //Avec doctrine
           /* $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($user);
            $doctrine->flush();*/

        }

        return $this->render('register/index.html.twig' , [
            'form' =>$form->createView()
        ]);
    }
}

<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use http\Env\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        //notification à l'utilisateur lors de l'inscription
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            //verification si l'utilisateur n'existe pas déjà
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if(!$search_email) {
                //Encodage du mot de passe
                $password =$encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                //dd($user);
                // Enregistrer les info dans la base
                //Sans doctrine
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                //envoi d'email au client
                $mail = new Mail();
                $content = "Bonjour ".$user->getFirstname()."<br/>Bienvenue sur la première boutique idéale pour vos pieds.";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur la Boutique Shoes&Shop', $content);

                //message de la notification
                $notification = "Votre inscription s'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte.";
            }else{
                $notification = "l'email que vous avez renseigné existe déjà.";
            }

            //Avec doctrine
           /* $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($user);
            $doctrine->flush();*/

        }

        return $this->render('register/index.html.twig' , [
            'form' =>$form->createView(),
            //passage à twig de la variable notification
            'notification'=> $notification
        ]);
    }
}

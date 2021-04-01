<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/mot-de-passe-oublie", name="reset_password")
     */
    public function index(Request $request)

    {
        if($this->getUser()){
            return $this->redirectToRoute('home');
        }
        if($request->get('email')){
            $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($request->get('email'));
            if($user){
                //Etape 1 : Enregistrer en base la demande de reset_password avec user, token, createdAd.
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());

                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                //2: Envoyer un email à l'user avec un lien lui permettant de mettre à jour son mot de passe
            }
        }
        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/modifier-mon-mot-de-passe/{token}", name="update_password")
     */
    public function update($token){

    }
}

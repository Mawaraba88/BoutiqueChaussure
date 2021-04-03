<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        //test envoi de mail
        //$mail = new Mail();
        //$mail->send('anadyeven@gmail.com', 'fatou', 'Mom premier mail', "Bonjour Fatou, j'espère que tu vas bien");
        return $this->render('home/index.html.twig');
    }
}

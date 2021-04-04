<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Header;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        //Pour afficher les bests produits
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        $headers = $this->entityManager->getRepository(Header::class)->findAll();

        //dd($products);
        //test envoi de mail
        //$mail = new Mail();
        //$mail->send('anadyeven@gmail.com', 'fatou', 'Mom premier mail', "Bonjour Fatou, j'espère que tu vas bien");
        return $this->render('home/index.html.twig',[
            'products' =>$products,
            'headers' =>$headers

        ]);
    }
}

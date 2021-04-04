<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;

    }


    //Function pour mener vers la carte bancaire
    /**
     * @Route("/buy/card", name="buy-card")
     */
    public function buy()
    {

        return $this->render('payment/buyByCard.html.twig');

    }
}

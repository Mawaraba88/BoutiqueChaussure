<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;

    //intialisation d'un constructeur en injectant  à le dependance de l'interface EntityManger de doctrine
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request): Response
    {
        //pour passer un formulaire à twig
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        //recuperation de la requete envoyé par url
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $products = $this->entityManager->getRepository(Product::class)->findwithSearch($search);
        }else{
            //recuperation de tous les produits en passant par le repository de la classe en question
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            //variable qui peremet d'afficher tous les produits
            'products'=>$products,
            //passage d'un formulaire à twig
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product")
     */
    //injection dans la fonction du paramètre slug mis dans l'url
    public function show($slug): Response
    {
        //recuperation du bon produit en exploitant le slug
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);

        if(!$product){
            return $this->redirectToRoute('products');
        }
        return $this->render('product/show.html.twig', [
            'product'=>$product,
            'products'=>$products
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAdressController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/adresses", name="account_adress")
     */
    public function index(): Response
    {
        //dd($this->getUser());


        return $this->render('account/address.html.twig');
    }

    /**
     * @Route("/compte/ajouter-une-adresse", name="account_adress-add")
     */
    public function add(Request $request)
    {
        //dd($this->getUser());
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            //Pour rediriger l'user vers ces adresses
            return $this->redirectToRoute('account_adress');
            //dd($address);
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/modifier-une-adresse/{id}", name="account_adress-edit")
     */
    public function edit(Request $request, $id)
    {
        //dd($this->getUser());
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
        if(!$address || $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('account_adress');
        }
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            //Pour rediriger l'user vers ces adresses
            return $this->redirectToRoute('account_adress');
            //dd($address);
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/supprimer-une-adresse/{id}", name="account_adress-delete")
     */
    public function delete($id)
    {
        //dd($this->getUser());
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
        if($address || $address->getUser() == $this->getUser()){
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }
            //Pour rediriger l'user vers ces adresses
            return $this->redirectToRoute('account_adress');
            //dd($address);
    }
}

<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\FilterType;
use App\Filter\ProduitFilter;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class ProduitController extends AbstractController
{
    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function catalogue(ProduitRepository $repoProduit, Request $request) : Response
    {
        $filter = new ProduitFilter;
        $form = $this->createForm(FilterType::class, $filter);


        $form->handleRequest($request);

        $produits = $repoProduit->findFiltre($filter);


        return $this->render("produit/catalogue.html.twig", [
            "produits" => $produits,
            "formFilter" => $form->createView()
        ]);
    }

    /**
     * 
     * @Route("/fiche_produit/{id}", name="fiche_produit")
     */
    
    public function fiche_produit(Produit $produit, Request $request, EntityManagerInterface $manager) 
    {


        $form = $this->createForm(FormType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            $userCo = $this->getUser();

            $manager->persist($produit);
            $manager->flush();

            return $this->redirectToRoute("fiche_produit", ['id' => $produit->getId()]);

        }

        return $this->render("produit/fiche_produit.html.twig", [
            "produit" => $produit,
        ]);
    }
}


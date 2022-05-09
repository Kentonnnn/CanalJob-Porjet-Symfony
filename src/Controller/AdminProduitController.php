<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/produit")
 */
class AdminProduitController extends AbstractController
{

    /**
     * @Route("/new", name="app_admin_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit, ["ajouter" => true]);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $produit->setCreatedAt(new \DateTimeImmutable("now"));

            $imageFile = $form->get('image')->getData();

            if($imageFile)
            {
                $nomImage = date('YmdHis') . "-" . uniqid() . "." . $imageFile->getClientOriginalExtension();

                $imageFile->move(
                    $this->getParameter('imageUpload'),
                    $nomImage
                );

                $produit->setImage($nomImage);
            }

            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash("success", "Le produit N°" . $produit->getId() . " a bien été ajouté");

            return $this->redirectToRoute('app_admin_produit_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form
        ]);
    }

    /**
     * @Route("/show", name="app_admin_produit_show", methods={"GET", "POST"})
     */
    public function show(ProduitRepository $repoProduit): Response
    {

        $produits = $repoProduit->findAll();

        return $this->render("admin_produit/show.html.twig", ["produits" => $produits]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit, ["modifier" => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageUpdate')->getData();

            if($imageFile)
            {
                $nomImage = date("YmdHis") . "-" . uniqid() . "." . $imageFile->getClientOriginalExtension();

                $imageFile->move(
                    $this->getParameter('imageUpload'),
                    $nomImage

                );

                if($produit->getImage())
                {
                    unlink($this->getParameter("imageUpload") . "/" . $produit->getImage());
                }

                $produit->setImage($nomImage);
            }

            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('success', "Le produit N°" . $produit->getId() . "a bien été modifié");

            return $this->redirectToRoute('app_admin_produit_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_produit_delete", methods={"GET", "POST"})
     */
    public function delete(Produit $produit, EntityManagerInterface $entityManager): Response
    {

        if ($produit->getImage()) 
        {
            unlink($this->getParameter("imageUpload") . "/" . $produit->getImage());
        } 
        $idProduit = $produit->getId();
        $entityManager->remove($produit);
        $entityManager->flush();

        $this->addFlash("success", "Le produit N° $idProduit a bien été supprimé");

        return $this->redirectToRoute('app_admin_produit_show', [], Response::HTTP_SEE_OTHER);
    }

        /**
     * @Route("/image/supprimer/{id}", name="images_supprimer")
     */
    public function image_supprimer(Produit $produit, EntityManagerInterface $manager)
    {
        if ($produit->getImage()) {
            unlink($this->getParameter("imageUpload") . "/" . $produit->getImage());
                    
            $produit->setImage(null);
                    
            $manager->persist($produit);

            $manager->flush();

            $this->addFlash('success', "Le produit N°" . $produit->getId() . " a bien été supprimé");

            return $this->redirectToRoute("app_admin_produit_show",[
                "id" => $produit->getId()
            ]);

        } else {
            $this->addFlash('error', "Ce produit n'a pas d'image");
            return $this->redirectToRoute("app_admin_produit_show");
        }
        

        
    }
}

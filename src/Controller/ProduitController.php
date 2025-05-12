<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produits')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'produit_list')]
    public function list(ProduitRepository $repo): Response
    {
        $produits = $repo->findAll();
        return $this->render('produit/list.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/new', name: 'produit_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $produit = new Produit();
            $produit->setName($request->request->get('name'))
                    ->setPrice((float)$request->request->get('price'))
                    ->setDescription($request->request->get('description'));
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_list');
        }

        return $this->render('produit/new.html.twig');
    }

    #[Route('/{id}/edit', name: 'produit_edit')]
    public function edit(int $id, Request $request, ProduitRepository $repo, EntityManagerInterface $em): Response
    {
        $produit = $repo->find($id);
        if (!$produit) {
            throw $this->createNotFoundException();
        }

        if ($request->isMethod('POST')) {
            $produit->setName($request->request->get('name'))
                    ->setPrice((float)$request->request->get('price'))
                    ->setDescription($request->request->get('description'));
            $em->flush();

            return $this->redirectToRoute('produit_list');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/delete', name: 'produit_delete')]
    public function delete(int $id, ProduitRepository $repo, EntityManagerInterface $em): Response
    {
        $produit = $repo->find($id);
        if ($produit) {
            $em->remove($produit);
            $em->flush();
        }

        return $this->redirectToRoute('produit_list');
    }
}
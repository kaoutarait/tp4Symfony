<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;


class ProduitController extends AbstractController
{
    /**
     * @Route("/api/produit", name="api_produit")
     */
    public function index(): Response
    {
        return $this->json(['welcome' => 'welcome message', 'test' => 'test']);
    }
    /**
     * @Route("/api/produit/add", name="api_produit_add")
     */
    public function create(PersistenceManagerRegistry $doctrine, Request $request)
    {
        $entityManager = $doctrine->getManager();

        $post = json_decode($request->getContent(), true);

        $produit = new Produit();
        $produit->setNom($post['nom']);
        $produit->setDescription($post['description']);
        $produit->setPrix($post['prix']);

        $entityManager->persist($produit);
        $entityManager->flush();
        return $this->json(['welcome' => 'welcome message']);
    }

    /**
     * @Route("/api/produit/update/{id}", name="api_produit_update")
     */
    public function update(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        $post = json_decode($request->getContent(), true);

        $produit->setNom($post['nom']);
        $produit->setDescription($post['description']);
        $produit->setPrix($post['prix']);
        $entityManager->flush();

        return $this->json(['welcome' => 'welcome message']);
    }

    /**
     * @Route("/api/produit/delete/{id}", name="api_produit_delete")
     */
    public function delete(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->json(['welcome' => 'welcome message']);
    }

    /**
     * @Route("/api/produit/getAll", name="api_produit_getAll")
     */
    public function list(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $produit = $entityManager->getRepository(Produit::class)->findAll();

        if (!$produit) {
            throw $this->createNotFoundException(
                'No product found for id ' 
            );
        }

    
        $data = [];

        foreach ($produit as $p) {
            $data[] = [
                'id' => $p->getId(),
                'nom' => $p->getNom(),
                'description' => $p->getDescription(),
                'prix' => $p->getPrix(),
            ];
        }
        return $this->json($data);
    }
}

<?php

namespace App\Controller;

use App\Entity\Projets;
use App\Repository\ProjetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/projets')]
class ProjetsController extends AbstractController
{
    #[Route('/', name: 'api_projets_index', methods: ['GET'])]
    public function index(ProjetsRepository $projetsRepository): JsonResponse
    {
        $projets = $projetsRepository->findAll();

        $data = [];
        foreach ($projets as $projet) {
            $data[] = [
                'id' => $projet->getId(),
                'title' => $projet->getTitle(),
                'description' => $projet->getDescription(),
                'image' => $projet->getImage(),
                'category' => $projet->getCategory() ? $projet->getCategory()->getName() : null,
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/new', name: 'api_projets_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $projet = new Projets();
        $projet->setTitle($data['title']);
        $projet->setDescription($data['description']);
        $projet->setImage($data['image']);
        

        $entityManager->persist($projet);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Projet créé!'], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_projets_show', methods: ['GET'])]
    public function show(Projets $projet): JsonResponse
    {
        $data = [
            'id' => $projet->getId(),
            'title' => $projet->getTitle(),
            'description' => $projet->getDescription(),
            'image' => $projet->getImage(),
            'category' => $projet->getCategory() ? $projet->getCategory()->getName() : null,
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/{id}/edit', name: 'api_projets_edit', methods: ['PUT'])]
    public function edit(Request $request, Projets $projet, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $projet->setTitle($data['title']);
        $projet->setDescription($data['description']);
        $projet->setImage($data['image']);
        

        $entityManager->flush();

        return new JsonResponse(['status' => 'Projet mis à jour!'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_projets_delete', methods: ['DELETE'])]
    public function delete(Projets $projet, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($projet);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Projet supprimé!'], Response::HTTP_NO_CONTENT);
    }
}

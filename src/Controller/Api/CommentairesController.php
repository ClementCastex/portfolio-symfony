<?php

namespace App\Controller;


use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/commentaires')]
class CommentairesController extends AbstractController
{
    #[Route('/', name: 'api_commentaires_index', methods: ['GET'])]
    public function index(CommentairesRepository $commentairesRepository): JsonResponse
    {
        $commentaires = $commentairesRepository->findAll();
        $data = [];

        foreach ($commentaires as $commentaire) {
            $data[] = [
                'id' => $commentaire->getId(),
                'user' => $commentaire->getUser()->getId(),
                'text' => $commentaire->getText(),
                'createdAt' => $commentaire->getCreateAt()->format('Y-m-d H:i:s'),
                'projet' => $commentaire->getProjets()->getId(),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/new', name: 'api_commentaires_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $commentaire = new Commentaires();
        $commentaire->setText($data['text']);
        $commentaire->setCreatedAt(new \DateTimeImmutable());

        // Ajouter les relations avec User et Projets
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Commentaire créé!'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_commentaires_show', methods: ['GET'])]
    public function show(Commentaires $commentaire): JsonResponse
    {
        $data = [
            'id' => $commentaire->getId(),
            'user' => $commentaire->getUser()->getId(),
            'text' => $commentaire->getText(),
            'createdAt' => $commentaire->getCreatedAt()->format('Y-m-d H:i:s'),
            'projet' => $commentaire->getProjets()->getId(),
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_commentaires_delete', methods: ['DELETE'])]
    public function delete(Commentaires $commentaire, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($commentaire);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Commentaire supprimé!'], JsonResponse::HTTP_NO_CONTENT);
    }
}

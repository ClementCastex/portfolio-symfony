<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users')]
class UserController extends AbstractController
{
    #[Route('/', name: 'api_users_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getIdentity()->getEmail(),
                'nom' => $user->getIdentity()->getName(),
                'prenom' => $user->getIdentity()->getSurname(),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_users_show', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        $data = [
            'id' => $user->getId(),
            'email' => $user->getIdentity()->getEmail(),
            'nom' => $user->getIdentity()->getName(),
            'prenom' => $user->getIdentity()->getSurname(),
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/new', name: 'api_users_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        // Ici tu devras lier Identity avec l'utilisateur
        // Par exemple, récupérer une instance d'Identity ou en créer une nouvelle.
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Utilisateur créé!'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}/edit', name: 'api_users_edit', methods: ['PUT'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Modifier les propriétés ici
        $entityManager->flush();

        return new JsonResponse(['status' => 'Utilisateur mis à jour!'], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_users_delete', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Utilisateur supprimé!'], JsonResponse::HTTP_NO_CONTENT);
    }
}

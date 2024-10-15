<?php

namespace App\Controller;

use App\Entity\Identity;
use App\Repository\IdentityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/identities')]
class IdentityController extends AbstractController
{
    #[Route('/', name: 'api_identities_index', methods: ['GET'])]
    public function index(IdentityRepository $identityRepository): JsonResponse
    {
        $identities = $identityRepository->findAll();
        $data = [];

        foreach ($identities as $identity) {
            $data[] = [
                'id' => $identity->getId(),
                'name' => $identity->getName(),
                'surname' => $identity->getSurname(),
                'email' => $identity->getEmail(),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_identities_show', methods: ['GET'])]
    public function show(Identity $identity): JsonResponse
    {
        $data = [
            'id' => $identity->getId(),
            'name' => $identity->getName(),
            'surname' => $identity->getSurname(),
            'email' => $identity->getEmail(),
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/new', name: 'api_identities_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $identity = new Identity();
        $identity->setName($data['name']);
        $identity->setSurname($data['surname']);
        $identity->setEmail($data['email']);

        $entityManager->persist($identity);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Identité créée!'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}/edit', name: 'api_identities_edit', methods: ['PUT'])]
    public function edit(Request $request, Identity $identity, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $identity->setName($data['name']);
        $identity->setSurname($data['surname']);
        $identity->setEmail($data['email']);

        $entityManager->flush();

        return new JsonResponse(['status' => 'Identité mise à jour!'], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_identities_delete', methods: ['DELETE'])]
    public function delete(Identity $identity, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($identity);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Identité supprimée!'], JsonResponse::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $v,
    ) {
    }

    #[Route('/register', name: 'user_store', methods: ['POST'])]
    public function store(Request $req): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $data = json_decode($req->getContent(), true);

        $user = new User();
        $user->create($data);

        $erros = $this->v->validate($user);
        if (count($erros) > 0) {
            return $this->json(
                ['message' => (string) $erros],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->em->persist($user);
        $this->em->flush();

        return $this->json(
            ['message' => 'User created with success!'],
            Response::HTTP_CREATED
        );
    }
}

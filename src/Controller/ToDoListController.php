<?php

namespace App\Controller;

use App\Entity\ToDoList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ToDoListController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $v
    ) {
    }

    #[Route('/lists', name: 'toDoList_list', methods: ['GET'])]
    public function index(): Response
    {
        // autoraização

        $lists = $this->em->getRepository(ToDoList::class)->findAll();
        if (! $lists) {
            return $this->json(
                ['message' => 'Can\'t find any list'],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json($lists);
    }

    #[Route('/lists/{id}', name: 'toDoList_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        // autoraização

        $list = $this->em->getRepository(ToDoList::class)->find($id);
        if (! $list) {
            return $this->json(
                ['message' => 'Can\'t find the list with the id: ' . $id],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json($list);
    }

    #[Route('/lists', name: 'toDoList_store', methods: ['POST'])]
    public function store(Request $req): Response
    {
        // autoraização

        $list = new ToDoList();

        $data = json_decode($req->getContent(), true);
        $list->create($data);

        $erros = $this->v->validate($list);
        if (count($erros) > 0) {
            return $this->json(
                ['message' => (string) $erros],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->em->persist($list);
        $this->em->flush();

        return $this->json([
            'message' => 'List created with success!',
            'list' => $list
        ]);
    }

    #[Route('/lists/{id}', name: 'toDoList_update', methods: ['PUT'])]
    public function update(Request $req, int $id): Response
    {
        // autoraização

        $list = $this->em->getRepository(ToDoList::class)->find($id);
        if (! $list) {
            return $this->json(
                ['message' => 'Can\'t find the list with the id: ' . $id],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($req->getContent(), true);
        $list->update($data);

        $erros = $this->v->validate($list);
        if (count($erros) > 0) {
            return $this->json(
                ['message' => (string) $erros],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->em->flush();

        return $this->json([
            'message' => 'List updated with succes!',
            'list' => $list,
        ]);
    }

    #[Route('/lists/{id}', name: 'toDoList_delete', methods: ['DELETE'])]
    public function destroy(int $id): Response
    {
        // autoraização

        $list = $this->em->getRepository(ToDoList::class)->find($id);
        if (! $list) {
            return $this->json(
                ['message' => 'Can\'t find the list with the id: ' . $id],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->em->remove($list);
        $this->em->flush();

        return $this->json([
            'message' => 'List ' . $id . ' deleted with success!'
        ]);
    }
}

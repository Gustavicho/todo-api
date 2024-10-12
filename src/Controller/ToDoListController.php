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
            throw $this->createNotFoundException(
                'Can\'t find any list'
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
            throw $this->createNotFoundException(
                'Can\'t find the list with the id: ' . $id
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
        // $this->createList($list, $data); // ---------------------------------------------------------

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
            throw $this->createNotFoundException(
                'Can\'t find the list with the id: ' . $id
            );
        }

        $data = json_decode($req->getContent(), true);
        $list->update($data);
        // $this->updateList($list, $data); // ---------------------------------------------------------

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
            throw $this->createNotFoundException(
                'Can\'t find the list with the id: ' . $id
            );
        }

        $this->em->remove($list);
        $this->em->flush();

        return $this->json([
            'message' => 'List ' . $id . ' deleted with success!'
        ]);
    }

    private function createList(ToDoList $list, array $data): void
    {
        $list->setTitle($data['title'] ?? '');
        $list->setShared($data['shared'] ?? '');
        $list->setCreatedAt();
    }

    private function updateList(ToDoList $list, array $data): void
    {
        $list->setTitle($data['title'] ?? '');
        $list->setShared($data['shared'] ?? '');
        $list->setUpdatedAt();
    }
}

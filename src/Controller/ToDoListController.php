<?php

namespace App\Controller;

use App\Entity\ToDoList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ToDoListController extends AbstractController
{
    #[Route('/lists', name: 'toDoList_list', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        // autoraização

        return $this->json(
            $em->getRepository(ToDoList::class)->findAll(),
            context: ['groups' => 'toDoList_list']
        );
    }

    #[Route('/lists/{id}', name: 'toDoList_show', methods: ['GET'])]
    public function show(EntityManagerInterface $em, int $id): JsonResponse
    {
        // autoraização

        return $this->json(
            $em->getRepository(ToDoList::class)->find($id),
            context: ['groups' => ['toDoList_list', 'with_tasks']]
        );
    }

    #[Route('/lists', name: 'toDoList_store', methods: ['POST'])]
    public function store(Request $req, EntityManagerInterface $em): JsonResponse
    {
        // autoraização

        $list = new ToDoList();

        // validar dados

        $list->setTitle('carai');
        $list->setShared(false);
        $list->setCreatedAt();

        $em->persist($list);
        $em->flush();

        return $this->json($list);
    }

    #[Route('/lists/{id}', name: 'toDoList_update', methods: ['PUT'])]
    public function update(EntityManagerInterface $em, int $id): JsonResponse
    {
        // autoraização

        $list = $em->getRepository(ToDoList::class)->find($id);

        if (! $list) {
            throw $this->createNotFoundException(
                'No list found with the id: ' . $id
            );
        }

        // validar dados

        $list->setTitle('urras');
        $list->setShared(true);
        $list->setUpdatedAt();

        $em->flush();

        return $this->json($list);
    }

    #[Route('/lists/{id}', name: 'toDoList_delete', methods: ['DELETE'])]
    public function destroy(EntityManagerInterface $em, int $id): JsonResponse
    {
        // autoraização

        $list = $em->getRepository(ToDoList::class)->find($id);

        if (! $list) {
            throw $this->createNotFoundException(
                'No list found with the id: ' . $id
            );
        }

        $em->remove($list);
        $em->flush();

        return $this->json([
            'msg' => 'List ' . $id . ' deleted with success!'
        ]);
    }
}

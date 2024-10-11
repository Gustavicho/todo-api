<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\ToDoList;
use App\Enum\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    #[Route('/lists/{listId}/tasks', name: 'task_list', methods: ['GET'])]
    public function index(int $listId, EntityManagerInterface $em): JsonResponse
    {
        // autoraização

        return $this->json(
            $em->getRepository(Task::class)
                ->findBy(['list' => $listId]),
            // context: ['groups' => 'task_list'],
        );
    }

    #[Route('/lists/{listId}/tasks/{id}', name: 'task_show', methods: ['GET'])]
    public function show(EntityManagerInterface $em, int $id, int $listId): JsonResponse
    {
        // autoraização

        return $this->json(
            $em->getRepository(Task::class)
                ->findBy(['list' => $listId, 'id' => $id]),
            // context: ['groups' => 'task_list'],
        );
    }

    #[Route('/lists/{listId}/tasks', name: 'task_store', methods: ['POST'])]
    public function store(Request $req, EntityManagerInterface $em, int $listId): JsonResponse
    {
        // autoraização

        $task = new Task();
        $list = $em->getRepository(ToDoList::class)->find($listId);

        if (! $list) {
            throw $this->createNotFoundException(
                'No list found with the id: ' . $listId . ' to create the task'
            );
        }
        // validar dados

        $task->setTitle('aiai');
        $task->setDescription('top demais');
        $task->setStatus(TaskStatus::TODO);
        $task->setCreatedAt();

        $list->addTask($task);

        $em->persist($task);
        $em->flush();

        return $this->json($task);
    }

    #[Route('/lists/{listId}/tasks/{id}', name: 'task_update', methods: ['PUT'])]
    public function update(EntityManagerInterface $em, int $listId, int $id): JsonResponse
    {
        // autoraização

        $list = $em->getRepository(ToDoList::class)->find($listId);
        if (! $list) {
            throw $this->createNotFoundException(
                'No task found with the id: ' . $listId
            );
        }

        $task = $em->getRepository(Task::class)->find($id);
        if (! $task) {
            throw $$this->createNotFoundException(
                'No task found with id:' . $id
            );
        }

        // validar dados

        $task->setTitle('urras');
        $task->setDescription('Muito foda');
        $task->setStatus(TaskStatus::DONE);
        $task->setUpdatedAt();

        $em->flush();

        return $this->json($task);
    }

    #[Route('/lists/{listId}/tasks/{id}', name: 'task_delete', methods: ['DELETE'])]
    public function destroy(EntityManagerInterface $em, int $listId, int $id): JsonResponse
    {
        // autoraização

        $list = $em->getRepository(ToDoList::class)->find($listId);
        if (! $list) {
            throw $this->createNotFoundException(
                'No task found with the id: ' . $listId
            );
        }

        $task = $em->getRepository(Task::class)->find($id);
        if (! $task) {
            throw $$this->createNotFoundException(
                'No task found with id:' . $id
            );
        }

        $list->removeTask($task);

        $em->remove($task);
        $em->flush();

        return $this->json([
            'msg' => 'Task ' . $id . ' deleted with success!'
        ]);
    }
}

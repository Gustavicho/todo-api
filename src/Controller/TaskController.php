<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\ToDoList;
use App\Enum\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $v,
    ) {
    }

    #[Route('/lists/{listId}/tasks', name: 'task_list', methods: ['GET'])]
    public function index(int $listId): JsonResponse
    {
        // autoraização

        $tasks = $this->em->getRepository(Task::class)->findBy(['list' => $listId]);
        if (!$tasks) {
            return $this->json(
                ['message' => 'Can\'t find the tasks in the list '.$listId],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json($tasks);
    }

    #[Route('/lists/{listId}/tasks/{id}', name: 'task_show', methods: ['GET'])]
    public function show(int $id, int $listId): JsonResponse
    {
        // autoraização
        $task = $this->em->getRepository(Task::class)->findBy(['list' => $listId, 'id' => $id]);
        if (!$task) {
            return $this->json(
                ['message' => 'Can\'t find the task '.$id.' in the list '.$listId],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json($task);
    }

    #[Route('/lists/{listId}/tasks', name: 'task_store', methods: ['POST'])]
    public function store(Request $req, int $listId): JsonResponse
    {
        // autoraização

        $task = new Task();
        $list = $this->em->getRepository(ToDoList::class)->find($listId);
        if (!$list) {
            return $this->json(
                ['message' => 'No list found with the id: '.$listId.' to create the task'],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($req->getContent(), true);
        $data['status'] = TaskStatus::fromString($data['status']);
        $task->create($data);

        $erros = $this->v->validate($task);
        if (count($erros) > 0) {
            return $this->json(
                ['message' => (string) $erros],
                Response::HTTP_BAD_REQUEST
            );
        }

        $list->addTask($task);
        $this->em->persist($task);
        $this->em->flush();

        return $this->json([
            'message' => 'Task created with succes!',
            'task' => $task,
        ]);
    }

    #[Route('/lists/{listId}/tasks/{id}', name: 'task_update', methods: ['PUT'])]
    public function update(Request $req, int $listId, int $id): JsonResponse
    {
        // autoraização

        $list = $this->em->getRepository(ToDoList::class)->find($listId);
        if (!$list) {
            return $this->json(
                ['message' => 'No task found with the id: '.$listId],
                Response::HTTP_NOT_FOUND
            );
        }

        $task = $this->em->getRepository(Task::class)->find($id);
        if (!$task) {
            return $this->json(
                ['message' => 'No task found with id: '.$id],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($req->getContent(), true);
        $data['status'] = TaskStatus::fromString($data['status']);
        $task->update($data);

        $erros = $this->v->validate($task);
        if (count($erros) > 0) {
            return $this->json(
                ['message' => (string) $erros],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->em->flush();

        return $this->json([
            'message' => 'Task updated with succes!',
            'task' => $task,
        ]);
    }

    #[Route('/lists/{listId}/tasks/{id}', name: 'task_delete', methods: ['DELETE'])]
    public function destroy(int $listId, int $id): JsonResponse
    {
        // autoraização

        $list = $this->em->getRepository(ToDoList::class)->find($listId);
        if (!$list) {
            return $this->json(
                ['message' => 'No task found with the id: '.$listId],
                Response::HTTP_NOT_FOUND
            );
        }

        $task = $this->em->getRepository(Task::class)->find($id);
        if (!$task) {
            return $this->json(
                ['message' => 'No task found with id: '.$id],
                Response::HTTP_NOT_FOUND
            );
        }

        $list->removeTask($task);

        $this->em->remove($task);
        $this->em->flush();

        return $this->json([
            'message' => 'Task '.$id.' deleted with success!',
        ]);
    }
}

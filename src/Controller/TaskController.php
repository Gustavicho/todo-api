<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\ToDoList;
use App\Enum\TaskStatus;
use App\Interface\ReturnPartternInterface;
use App\Trait\JsonParttern;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskController extends AbstractController implements ReturnPartternInterface
{
    use JsonParttern;

    public const ERR_NOT_FOUND = 'Cant find the task';
    public const ERR_BAD_REQUEST = 'Cant update the task';
    public const ERR_INTERNAL = 'Cant update the task';

    public const RES_CREATED = 'Task created with success!';
    public const RES_UPDATED = 'Task updated with success!';
    public const RES_DELETED = 'Task deleted with success!';
    public const RES_OK = 'Task founded!';

    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $v,
    ) {
    }

    #[Route('/lists/{listId}/tasks', name: 'task_list', methods: ['GET'])]
    public function index(int $listId): JsonResponse
    {
        // TODO: add autoraização

        $tasks = $this->em->getRepository(Task::class)->findBy(['list' => $listId]);
        if (!$tasks) {
            return $this->resNotFound(TaskController::ERR_NOT_FOUND);
        }

        return $this->resOk(TaskController::RES_OK, $tasks);
    }

    #[Route('/lists/{listId}/tasks/{id}', name: 'task_show', methods: ['GET'])]
    public function show(int $id, int $listId): JsonResponse
    {
        // TODO: add autoraização

        $task = $this->em->getRepository(Task::class)->findBy(['list' => $listId, 'id' => $id]);
        if (!$task) {
            return $this->resNotFound(TaskController::ERR_NOT_FOUND);
        }

        return $this->resOk(TaskController::RES_OK, $task);
    }

    #[Route('/lists/{listId}/tasks', name: 'task_store', methods: ['POST'])]
    public function store(Request $req, int $listId): JsonResponse
    {
        // TODO: add autoraização

        $task = new Task();
        $list = $this->em->getRepository(ToDoList::class)->find($listId);
        if (!$list) {
            return $this->resNotFound(ToDoListController::ERR_NOT_FOUND);
        }

        $data = json_decode($req->getContent(), true);
        $data['status'] = TaskStatus::fromString($data['status']);
        $task->create($data);

        $erros = $this->v->validate($task);
        if (count($erros) > 0) {
            return $this->resBadRequest((string) $erros);
        }

        $list->addTask($task);
        $this->em->persist($task);
        $this->em->flush();

        return $this->resOk(TaskController::RES_CREATED, $task);
    }

    #[Route('/lists/{listId}/tasks/{id}', name: 'task_update', methods: ['PUT'])]
    public function update(Request $req, int $listId, int $id): JsonResponse
    {
        // TODO: add autoraização

        $list = $this->em->getRepository(ToDoList::class)->find($listId);
        if (!$list) {
            return $this->resNotFound(ToDoListController::ERR_NOT_FOUND);
        }

        $task = $this->em->getRepository(Task::class)->find($id);
        if (!$task) {
            return $this->resNotFound(TaskController::ERR_NOT_FOUND);
        }

        $data = json_decode($req->getContent(), true);
        $data['status'] = TaskStatus::fromString($data['status']);
        $task->update($data);

        $erros = $this->v->validate($task);
        if (count($erros) > 0) {
            return $this->resBadRequest((string) $erros);
        }

        $this->em->flush();

        return $this->resUpdated(TaskController::RES_UPDATED);
    }

    #[Route('/lists/{listId}/tasks/{id}', name: 'task_delete', methods: ['DELETE'])]
    public function destroy(int $listId, int $id): JsonResponse
    {
        // TODO: add autoraização

        $list = $this->em->getRepository(ToDoList::class)->find($listId);
        if (!$list) {
            return $this->resNotFound(ToDoListController::ERR_NOT_FOUND);
        }

        $task = $this->em->getRepository(Task::class)->find($id);
        if (!$task) {
            return $this->resNotFound(TaskController::ERR_NOT_FOUND);
        }

        $list->removeTask($task);

        $this->em->remove($task);
        $this->em->flush();

        return $this->resDeleted(TaskController::RES_DELETED);
    }
}

<?php

namespace App\Controller;

use App\Entity\ToDoList;
use App\Interface\ReturnPartternInterface;
use App\Trait\JsonParttern;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ToDoListController extends AbstractController implements ReturnPartternInterface
{
    use JsonParttern;

    public const ERR_NOT_FOUND = 'Cant find the List';
    public const ERR_BAD_REQUEST = 'Cant update the List';
    public const ERR_INTERNAL = 'Cant update the List';

    public const RES_CREATED = 'List created with success!';
    public const RES_UPDATED = 'List updated with success!';
    public const RES_DELETED = 'List deleted with success!';
    public const RES_OK = 'List founded!';

    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $v,
    ) {
    }

    #[Route('/lists', name: 'toDoList_list', methods: ['GET'])]
    public function index(): Response
    {
        // TODO: add autoraização

        $lists = $this->em->getRepository(ToDoList::class)->findAll();
        if (!$lists) {
            return $this->resNotFound(ToDoListController::ERR_NOT_FOUND);
        }

        return $this->resOk(ToDoListController::RES_OK, $lists);
    }

    #[Route('/lists/{id}', name: 'toDoList_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        // TODO: add autoraização

        $list = $this->em->getRepository(ToDoList::class)->find($id);
        if (!$list) {
            return $this->resNotFound(ToDoListController::ERR_NOT_FOUND);
        }

        return $this->resOk(ToDoListController::RES_OK, $list);
    }

    #[Route('/lists', name: 'toDoList_store', methods: ['POST'])]
    public function store(Request $req): Response
    {
        // TODO: add autoraização

        $list = new ToDoList();

        $data = json_decode($req->getContent(), true);
        $list->create($data);

        $erros = $this->v->validate($list);
        if (count($erros) > 0) {
            return $this->resBadRequest((string) $erros);
        }

        $this->em->persist($list);
        $this->em->flush();

        return $this->resOk(ToDoListController::RES_CREATED, $list);
    }

    #[Route('/lists/{id}', name: 'toDoList_update', methods: ['PUT'])]
    public function update(Request $req, int $id): Response
    {
        // TODO: add autoraização

        $list = $this->em->getRepository(ToDoList::class)->find($id);
        if (!$list) {
            return $this->resNotFound(ToDoListController::ERR_NOT_FOUND);
        }

        $data = json_decode($req->getContent(), true);
        $list->update($data);

        $erros = $this->v->validate($list);
        if (count($erros) > 0) {
            return $this->resNotFound((string) $erros);
        }

        $this->em->flush();

        return $this->resUpdated(ToDoListController::RES_UPDATED);
    }

    #[Route('/lists/{id}', name: 'toDoList_delete', methods: ['DELETE'])]
    public function destroy(int $id): Response
    {
        // TODO: add autoraização

        $list = $this->em->getRepository(ToDoList::class)->find($id);
        if (!$list) {
            return $this->resNotFound(ToDoListController::ERR_NOT_FOUND);
        }

        $this->em->remove($list);
        $this->em->flush();

        return $this->resDeleted(ToDoListController::RES_DELETED);
    }
}

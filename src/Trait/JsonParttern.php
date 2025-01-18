<?php

namespace App\Trait;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait JsonParttern
{
    public function resNotFound(string $message): JsonResponse
    {
        return $this->json(
            ['message' => $message],
            Response::HTTP_NOT_FOUND
        );
    }

    public function resBadRequest(string $message): JsonResponse
    {
        return $this->json(
            ['message' => $message],
            Response::HTTP_BAD_REQUEST
        );
    }

    public function resInternal(string $message): JsonResponse
    {
        return $this->json(
            ['message' => $message],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public function resCreated(string $message, array|object $object): JsonResponse
    {
        return $this->json(
            ['message' => $message, 'object' => $object],
            Response::HTTP_CREATED
        );
    }

    public function resOk(string $message, array|object $object): JsonResponse
    {
        return $this->json(
            ['message' => $message, 'object' => $object],
            Response::HTTP_OK
        );
    }

    public function resUpdated(string $message): JsonResponse
    {
        return $this->json(
            ['message' => $message],
            Response::HTTP_OK
        );
    }

    public function resDeleted(string $message): JsonResponse
    {
        return $this->json(
            ['message' => $message],
            Response::HTTP_OK
        );
    }
}

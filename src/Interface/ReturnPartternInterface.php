<?php

namespace App\Interface;

use Symfony\Component\HttpFoundation\JsonResponse;

interface ReturnPartternInterface
{
    public function resNotFound(string $message): JsonResponse;

    public function resBadRequest(string $message): JsonResponse;

    public function resInternal(string $message): JsonResponse;

    public function resCreated(string $message, array|object $object): JsonResponse;

    public function resOk(string $message, array|object $object): JsonResponse;

    public function resUpdated(string $message): JsonResponse;

    public function resDeleted(string $message): JsonResponse;
}

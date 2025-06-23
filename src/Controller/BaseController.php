<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends AbstractController
{
    protected function success(mixed $data = null, string $message = 'Operación exitosa'): JsonResponse
    {
        return $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }

    protected function error(string $message = 'Ocurrió un error', int $statusCode = 500): JsonResponse
    {
        return $this->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }

    protected function getRequestData(Request $request): array
    {
        $data = json_decode($request->getContent(), true);
        return is_array($data) ? $data : [];
    }

    protected function validateRequiredFields(array $data, array $requiredFields): array
    {
        $missing = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                $missing[] = $field;
            }
        }

        return $missing;
    }

    protected function jsonCreated(mixed $data = null, string $message = 'Recurso creado correctamente'): JsonResponse
    {
        return $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], 201);
    }

    protected function jsonUpdated(mixed $data = null, string $message = 'Recurso actualizado correctamente'): JsonResponse
    {
        return $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }

    protected function jsonDeleted(string $message = 'Recurso eliminado correctamente'): JsonResponse
    {
        return $this->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}

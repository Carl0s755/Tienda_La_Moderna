<?php

namespace App\Controller;

use App\DTO\ExpirationAlertDTO;
use App\Service\GenericCrudService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ExpirationAlertController extends BaseController
{
    public function __construct(private GenericCrudService $crud) {}

    #[Route('/expiration-alerts', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->success($this->crud->getAll('ALERTAS_CADUCIDAD', ExpirationAlertDTO::class));
    }

    #[Route('/expiration-alerts/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $alert = $this->crud->getById('ALERTAS_CADUCIDAD', 'ID_ALERTA', $id, ExpirationAlertDTO::class);
        return $alert ? $this->success($alert) : $this->error('Expiration alert not found', 404);
    }
}

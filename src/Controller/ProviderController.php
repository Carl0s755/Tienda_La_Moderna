<?php

namespace App\Controller;

use App\DTO\ProviderDTO;
use App\Service\GenericCrudService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProviderController extends BaseController
{
    public function __construct(private GenericCrudService $crud) {}

    #[Route('/providers', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->success($this->crud->getAll('PROVEEDORES', ProviderDTO::class));
    }

    #[Route('/providers/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $provider = $this->crud->getById('PROVEEDORES', 'PROVEEDORES_ID', $id, ProviderDTO::class);
        return $provider ? $this->success($provider) : $this->error('Provider not found', 404);
    }

    #[Route('/providers', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $this->getRequestData($request);
        $required = ['NOMBRE', 'DIRECCION', 'TELEFONO'];
        $missing = $this->validateRequiredFields($data, $required);
        if ($missing) {
            return $this->error('Missing required fields: ' . implode(', ', $missing), 400);
        }

        $this->crud->insert('PROVEEDORES', $data);
        return $this->jsonCreated();
    }

    #[Route('/providers/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = $this->getRequestData($request);
        $this->crud->update('PROVEEDORES', 'PROVEEDORES_ID', $id, $data);
        return $this->jsonUpdated();
    }

    #[Route('/providers/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->crud->delete('PROVEEDORES', 'PROVEEDORES_ID', $id);
        return $this->jsonDeleted();
    }
}

<?php

namespace App\Controller;

use App\DTO\SaleDTO;
use App\Service\GenericCrudService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SaleController extends BaseController
{
    public function __construct(private GenericCrudService $crud) {}

    #[Route('/sales', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->success($this->crud->getAll('VENTAS', SaleDTO::class));
    }

    #[Route('/sales/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $sale = $this->crud->getById('VENTAS', 'ID_VENTA', $id, SaleDTO::class);
        return $sale ? $this->success($sale) : $this->error('Sale not found', 404);
    }

    #[Route('/sales', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $this->getRequestData($request);
        $required = ['CLIENTE_ID', 'PRODUCTO_ID', 'CANTIDAD', 'FECHA'];
        $missing = $this->validateRequiredFields($data, $required);
        if ($missing) {
            return $this->error('Missing required fields: ' . implode(', ', $missing), 400);
        }

        // Conversión de fecha al formato Oracle
        $data['FECHA'] = "TO_DATE('{$data['FECHA']}', 'YYYY-MM-DD')";

        $this->crud->insert('VENTAS', $data, ['FECHA']);
        return $this->jsonCreated();
    }

    #[Route('/sales/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = $this->getRequestData($request);

        if (!empty($data['FECHA'])) {
            $data['FECHA'] = "TO_DATE('{$data['FECHA']}', 'YYYY-MM-DD')";
        }

        $this->crud->update('VENTAS', 'ID_VENTA', $id, $data, ['FECHA']);
        return $this->jsonUpdated();
    }

    #[Route('/sales/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->crud->delete('VENTAS', 'ID_VENTA', $id);
        return $this->jsonDeleted();
    }
}

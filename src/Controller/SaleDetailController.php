<?php

namespace App\Controller;

use App\DTO\SaleDetailDTO;
use App\Service\GenericCrudService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SaleDetailController extends BaseController
{
    public function __construct(private GenericCrudService $crud) {}

    #[Route('/sale-details', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->success($this->crud->getAll('DETALLE_VENTAS', SaleDetailDTO::class));
    }

    #[Route('/sale-details/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $detail = $this->crud->getById('DETALLE_VENTAS', 'DETALLE_ID', $id, SaleDetailDTO::class);
        return $detail ? $this->success($detail) : $this->error('Sale detail not found', 404);
    }
}

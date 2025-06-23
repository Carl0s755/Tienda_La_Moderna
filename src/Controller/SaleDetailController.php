<?php

namespace App\Controller;

use App\DTO\RepositoryDTO\SaleWithDetailsDTO;
use App\Repository\SaleDetailRepository;
use App\Service\GenericCrudService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SaleDetailController extends BaseController
{
    public function __construct(
        private GenericCrudService $crud,
        private SaleDetailRepository $repository
    ) {}

    #[Route('/sale-details', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->success(
            $this->crud->getAll('DETALLE_VENTAS', SaleWithDetailsDTO::class)
        );
    }

    #[Route('/sale-details/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $detail = $this->crud->getById('DETALLE_VENTAS', 'ID_DETALLE', $id, SaleWithDetailsDTO::class);
        return $detail ? $this->success($detail) : $this->error('Detalle no encontrado', 404);
    }

    #[Route('/sales/{id}/with-details', methods: ['GET'])]
    public function getVentaCompleta(int $id): JsonResponse
    {
        $data = $this->repository->getSaleWithDetails($id);
        return $data ? $this->success($data) : $this->error('Venta no encontrada', 404);
    }

}

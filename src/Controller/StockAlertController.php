<?php

namespace App\Controller;

use App\DTO\StockAlertDTO;
use App\Service\GenericCrudService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StockAlertController extends BaseController
{
    public function __construct(private GenericCrudService $crud) {}

    #[Route('/stock-alerts', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->success($this->crud->getAll('ALERTAS_STOCK', StockAlertDTO::class));
    }

    #[Route('/stock-alerts/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $alert = $this->crud->getById('ALERTAS_STOCK', 'ALERTA_ID', $id, StockAlertDTO::class);
        return $alert ? $this->success($alert) : $this->error('Stock alert not found', 404);
    }
}

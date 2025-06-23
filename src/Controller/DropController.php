<?php

namespace App\Controller;

use App\Service\OracleClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DropController extends BaseController
{
    public function __construct(private OracleClient $oracle) {}

    #[Route('/drops', methods: ['GET'])]
    public function clientList(): JsonResponse
    {
        $results = $this->oracle->query("
            SELECT ID_CLIENTE AS Id_Cliente, NOMBRE AS nombre
            FROM CLIENTES
            ORDER BY NOMBRE
        ");

        return $this->success($results);
    }
}

<?php

namespace App\Controller;

use App\Service\OracleClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SalesSummaryController extends BaseController
{
    private OracleClient $oracle;

    public function __construct(OracleClient $oracle)
    {
        $this->oracle = $oracle;
    }

    #[Route('/sales-today', name: 'sales_today', methods: ['GET'])]
    public function today(): JsonResponse
    {
        try {
            $rows = $this->oracle->query("
                SELECT
                    TO_CHAR(TRUNC(fecha_venta), 'YYYY-MM-DD') AS fecha,
                    SUM(total_venta) AS total_dia,
                    COUNT(*) AS total_ventas
                FROM ventas
                WHERE TRUNC(fecha_venta) = TRUNC(SYSDATE)
                GROUP BY TRUNC(fecha_venta)
            ");

            return $this->success($rows[0] ?? []);
        } catch (\Exception $e) {
            return $this->error('Error al obtener ventas del día: ' . $e->getMessage());
        }
    }
}

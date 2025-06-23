<?php

namespace App\Controller;

use App\Service\OracleClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SaleReportController extends BaseController
{
    private OracleClient $oracle;

    public function __construct(OracleClient $oracle)
    {
        $this->oracle = $oracle;
    }

    #[Route('/sales-weekly', name: 'sales_weekly', methods: ['GET'])]
    public function weekly(): JsonResponse
    {
        try {
            $rows = $this->oracle->query("
                SELECT
                    TO_CHAR(TRUNC(fecha_venta), 'YYYY-MM-DD') AS fecha,
                    COUNT(*) AS total_ventas,
                    SUM(total_venta) AS total_monto
                FROM ventas
                WHERE fecha_venta >= TRUNC(SYSDATE) - 7
                GROUP BY TRUNC(fecha_venta)
                ORDER BY fecha
            ");

            return $this->success($rows);
        } catch (\Exception $e) {
            return $this->error('Error al obtener el reporte semanal: ' . $e->getMessage());
        }
    }
}

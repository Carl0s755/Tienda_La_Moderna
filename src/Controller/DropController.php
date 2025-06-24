<?php

namespace App\Controller;

use App\Service\OracleClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DropController extends BaseController
{
    public function __construct(private OracleClient $oracle)
    {
    }

    #[Route('/drops/clients', methods: ['GET'])]
    public function clientList(): JsonResponse
    {
        $results = $this->oracle->query("
        SELECT ID_CLIENTE AS id, NOMBRE AS nombre
        FROM CLIENTES
        ORDER BY NOMBRE
    ");

        return $this->success($results);
    }

    #[Route('/drops/providers', methods: ['GET'])]
    public function providerList(): JsonResponse
    {
        $results = $this->oracle->query("
        SELECT ID_PROVEEDOR AS id, NOMBRE AS nombre
        FROM PROVEEDORES
        ORDER BY NOMBRE
    ");

        return $this->success($results);
    }

    #[Route('/drops/products', methods: ['GET'])]
    public function productList(): JsonResponse
    {
        $results = $this->oracle->query("
        SELECT
            ID_PRODUCTO AS id,
            NOMBRE AS nombre,
            PRECIO_UNITARIO AS precio_unitario
        FROM PRODUCTOS
        ORDER BY NOMBRE
    ");

        return $this->success($results);
    }

    #[Route('/sales-report-data', name: 'sales_report_data', methods: ['GET'])]
    public function getSalesReportData(): JsonResponse
    {
        try {
            $ventas = $this->oracle->query("
            SELECT
                v.ID_VENTA,
                TO_CHAR(v.FECHA_VENTA, 'YYYY-MM-DD HH24:MI') AS FECHA,
                c.NOMBRE AS CLIENTE,
                v.METODO_PAGO,
                v.TOTAL_VENTA
            FROM VENTAS v
            LEFT JOIN CLIENTES c ON c.ID_CLIENTE = v.ID_CLIENTE
            WHERE TRUNC(v.FECHA_VENTA) = TRUNC(SYSDATE)
            ORDER BY v.FECHA_VENTA DESC
        ");

            $detalles = $this->oracle->query("
            SELECT
                d.ID_VENTA,
                p.NOMBRE AS PRODUCTO,
                d.CANTIDAD,
                d.PRECIO_UNITARIO,
                (d.CANTIDAD * d.PRECIO_UNITARIO) AS SUBTOTAL
            FROM DETALLE_VENTAS d
            INNER JOIN PRODUCTOS p ON p.ID_PRODUCTO = d.ID_PRODUCTO
            WHERE d.ID_VENTA IN (
                SELECT ID_VENTA FROM VENTAS WHERE TRUNC(FECHA_VENTA) = TRUNC(SYSDATE)
            )
        ");

            return $this->success([
                'ventas' => $ventas,
                'detalles' => $detalles,
            ]);
        } catch (\Exception $e) {
            return $this->error('Error al obtener ventas y detalles: ' . $e->getMessage());
        }
    }

    #[Route('/sales-weekly-report', name: 'weekly_report', methods: ['GET'])]
    public function weeklyReport(): JsonResponse
    {
        try {
            $ventasPorDia = $this->oracle->query("
            SELECT
                TO_CHAR(TRUNC(v.fecha_venta), 'YYYY-MM-DD') AS fecha,
                COUNT(*) AS total_ventas,
                SUM(v.total_venta) AS total_monto
            FROM ventas v
            WHERE v.fecha_venta >= TRUNC(SYSDATE) - 6
            GROUP BY TRUNC(v.fecha_venta)
            ORDER BY fecha ASC
        ");

            $detalles = $this->oracle->query("
            SELECT
                TO_CHAR(v.fecha_venta, 'YYYY-MM-DD') AS fecha,
                p.nombre AS producto,
                d.cantidad,
                d.precio_unitario,
                (d.cantidad * d.precio_unitario) AS subtotal
            FROM detalle_ventas d
            JOIN ventas v ON v.id_venta = d.id_venta
            JOIN productos p ON p.id_producto = d.id_producto
            WHERE v.fecha_venta >= TRUNC(SYSDATE) - 6
            ORDER BY v.fecha_venta ASC
        ");

            return $this->success([
                'resumen' => $ventasPorDia,
                'detalles' => $detalles
            ]);
        } catch (\Exception $e) {
            return $this->error('Error al obtener el reporte semanal: ' . $e->getMessage());
        }
    }

    #[Route('/sales-ticket/{id}', name: 'sales_ticket', methods: ['GET'])]
    public function getSaleWithDetails(int $id): JsonResponse
    {
        try {
            $venta = $this->oracle->query("
            SELECT
                v.id_venta,
                TO_CHAR(v.fecha_venta, 'YYYY-MM-DD HH24:MI') AS fecha,
                c.nombre AS cliente,
                v.metodo_pago,
                v.total_venta
            FROM ventas v
            LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
            WHERE v.id_venta = :id
        ", ['id' => $id]);

            if (!$venta) {
                return $this->error('Venta no encontrada');
            }

            $detalles = $this->oracle->query("
            SELECT
                p.nombre AS producto,
                d.cantidad,
                d.precio_unitario,
                (d.cantidad * d.precio_unitario) AS subtotal
            FROM detalle_ventas d
            JOIN productos p ON p.id_producto = d.id_producto
            WHERE d.id_venta = :id
        ", ['id' => $id]);

            return $this->success([
                'venta' => $venta[0],
                'detalles' => $detalles
            ]);
        } catch (\Exception $e) {
            return $this->error('Error al obtener la venta: ' . $e->getMessage());
        }
    }
}

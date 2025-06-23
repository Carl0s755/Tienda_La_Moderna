<?php

namespace App\Repository;

use App\DTO\RepositoryDTO\SaleWithDetailsDTO;
use App\Service\OracleClient;

class SaleDetailRepository
{
    public function __construct(private OracleClient $oracle) {}

    public function getSaleWithDetails(int $idVenta): ?SaleWithDetailsDTO
    {
        // Obtener los datos de la venta
        $venta = $this->oracle->queryOne(
            "SELECT * FROM VENTAS WHERE ID_VENTA = :id",
            ['id' => $idVenta]
        );

        if (!$venta) return null;

        // Obtener los detalles de la venta
        $detalles = $this->oracle->query(
            "SELECT d.*, p.NOMBRE AS NOMBRE_PRODUCTO
             FROM DETALLE_VENTAS d
             JOIN PRODUCTOS p ON p.ID_PRODUCTO = d.ID_PRODUCTO
             WHERE d.ID_VENTA = :id",
            ['id' => $idVenta]
        );

        return new SaleWithDetailsDTO($venta, $detalles);
    }
}

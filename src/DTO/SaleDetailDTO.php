<?php

namespace App\DTO;

class SaleDetailDTO extends BaseDTO
{
    public int $detalleId;
    public int $ventaId;
    public int $productoId;
    public int $cantidad;
    public float $precioUnitario;
}

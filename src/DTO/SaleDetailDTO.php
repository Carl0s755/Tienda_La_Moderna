<?php

namespace App\DTO;

use App\DTO\BaseDTO;

class SaleDetailDTO extends BaseDTO
{
    public int $id_detalle;
    public int $id_venta;
    public int $id_producto;
    public int $cantidad;
    public float $precio_unitario;
    public float $subtotal;
}

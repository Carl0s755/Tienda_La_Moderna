<?php

namespace App\DTO;

use App\DTO\BaseDTO;

class SaleDTO extends BaseDTO
{
    public int $Id_Venta;
    public string $fecha_venta;
    public ?int $Id_Cliente;
    public float $total_venta;
    public ?string $metodo_pago;
}

<?php

namespace App\DTO;

class SaleDTO extends BaseDTO
{
    public int $ventasId;
    public int $clienteId;
    public int $productoId;
    public int $cantidad;
    public string $fecha;
}

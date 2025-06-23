<?php

namespace App\DTO;

class StockAlertDTO extends BaseDTO
{
    public int $alertaId;
    public int $productoId;
    public int $stockMinimo;
    public int $stockActual;
    public string $fechaAlerta;
}

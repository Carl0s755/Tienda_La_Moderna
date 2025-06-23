<?php

namespace App\DTO;

class StockAlertDTO extends BaseDTO
{
    public int $ID_ALERTA;
    public int $ID_PRODUCTO;
    public string $MENSAJE;
    public string $FECHA_ALERTA;
}

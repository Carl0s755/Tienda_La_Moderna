<?php

namespace App\DTO;

class ExpirationAlertDTO extends BaseDTO
{
    public int $Id_Alerta;
    public int $Id_Producto;
    public string $Mensaje ;
    public string $Fecha_Alerta;
}

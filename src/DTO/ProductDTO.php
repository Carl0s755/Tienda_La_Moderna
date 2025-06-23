<?php

namespace App\DTO;

class ProductDTO extends BaseDTO
{
    public int $Id_Producto;
    public string $nombre;
    public string $descripcion;
    public float $precio_unitario;
    public int $stock;
    public ?string $fecha_caducidad;
    public ?int $Id_Proveedor;
}

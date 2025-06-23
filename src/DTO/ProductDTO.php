<?php

namespace App\DTO;

class ProductDTO extends BaseDTO
{
    public int $idProducto;
    public string $nombre;
    public float $precio;
    public int $stock;
}

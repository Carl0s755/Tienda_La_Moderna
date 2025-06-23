<?php

namespace App\DTO;

class LowStockProductDTO
{
    public int $productId;
    public string $name;
    public int $stock;
    public float $unitPrice;

    public static function fromArray(array $row): self
    {
        $dto = new self();
        $dto->productId = (int) ($row['PRODUCT_ID'] ?? 0);
        $dto->name = $row['NAME'] ?? '';
        $dto->stock = (int) ($row['STOCK'] ?? 0);
        $dto->unitPrice = (float) ($row['UNIT_PRICE'] ?? 0);
        return $dto;
    }

    /**
     * @param array[] $rows
     * @return LowStockProductDTO[]
     */
    public static function fromRows(array $rows): array
    {
        return array_map([self::class, 'fromArray'], $rows);
    }
}

<?php

namespace App\DTO;

class ExpiringProductDTO
{
    public int $productId;
    public string $name;
    public string $expirationDate;
    public int $daysRemaining;

    public static function fromArray(array $row): self
    {
        $dto = new self();
        $dto->productId = (int) ($row['PRODUCT_ID'] ?? 0);
        $dto->name = $row['NAME'] ?? '';
        $dto->expirationDate = $row['EXPIRATION_DATE'] ?? '';
        $dto->daysRemaining = (int) ($row['DAYS_REMAINING'] ?? 0);
        return $dto;
    }

    /**
     * @param array[] $rows
     * @return ExpiringProductDTO[]
     */
    public static function fromRows(array $rows): array
    {
        return array_map([self::class, 'fromArray'], $rows);
    }
}

<?php

namespace App\DTO;

class IntervalCashCutDTO
{
    public string $date;
    public float $total;

    public static function fromArray(array $row): self
    {
        $dto = new self();
        $dto->date = $row['DATE'] ?? '';
        $dto->total = (float) ($row['TOTAL'] ?? 0);
        return $dto;
    }

    /**
     * @param array[] $rows
     * @return IntervalCashCutDTO[]
     */
    public static function fromRows(array $rows): array
    {
        return array_map([self::class, 'fromArray'], $rows);
    }
}

<?php
namespace App\Mapper;

use ReflectionClass;
use ReflectionProperty;

class ArrayToDtoMapper
{
    /**
     * Convierte un array asociativo a una instancia del DTO dado
     */
    public static function map(array $row, string $dtoClass): object
    {
        $reflection = new ReflectionClass($dtoClass);
        $instance = $reflection->newInstance();

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $name = strtoupper($property->getName());
            if (array_key_exists($name, $row)) {
                $property->setValue($instance, $row[$name]);
            }
        }

        return $instance;
    }

    /**
     * Convierte múltiples filas a un array de DTOs
     */
    public static function mapMany(array $rows, string $dtoClass): array
    {
        return array_map(fn($row) => self::map($row, $dtoClass), $rows);
    }
}

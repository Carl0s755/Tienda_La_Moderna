<?php

namespace App\DTO;

use ReflectionClass;
use ReflectionProperty;

abstract class BaseDTO
{
    public static function fromArray(array $row): static
    {
        $dto = new static();
        $reflection = new ReflectionClass($dto);

        $normalizedRow = [];
        foreach ($row as $key => $value) {
            $camelCaseKey = lcfirst(str_replace(' ', '', ucwords(strtolower(str_replace('_', ' ', $key)))));
            $normalizedRow[$camelCaseKey] = $value;
        }

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();

            if (array_key_exists($propertyName, $normalizedRow)) {
                $value = $normalizedRow[$propertyName];
                $type = $property->getType()?->getName();

                if ($type === 'int') {
                    $value = (int) $value;
                } elseif ($type === 'float') {
                    $value = (float) $value;
                } elseif ($type === 'bool') {
                    $value = (bool) $value;
                } elseif ($type === 'string') {
                    $value = (string) $value;
                }

                $dto->$propertyName = $value;
            }
        }

        return $dto;
    }


    /** @return static[] */
    public static function fromRows(array $rows): array
    {
        return array_map([static::class, 'fromArray'], $rows);
    }
}

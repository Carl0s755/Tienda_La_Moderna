<?php

namespace App\Service;

use App\Mapper\ArrayToDtoMapper;

class GenericCrudService
{
    public function __construct(private OracleClient $oracle) {}

    public function getAll(string $table, string $dtoClass): array
    {
        $rows = $this->oracle->query("SELECT * FROM {$table}");
        return ArrayToDtoMapper::mapMany($rows, $dtoClass);
    }

    public function getById(string $table, string $idField, int $id, string $dtoClass): ?object
    {
        $rows = $this->oracle->query("SELECT * FROM {$table} WHERE {$idField} = :id", [':id' => $id]);
        return count($rows) > 0 ? ArrayToDtoMapper::map($rows[0], $dtoClass) : null;
    }

    public function insert(string $table, array $data): void
    {
        $columns = array_keys($data);

        $placeholders = array_map(function ($col) {
            return $col === 'fecha_caducidad'
                ? "TO_DATE(:$col, 'YYYY-MM-DD')"
                : ":$col";
        }, $columns);

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(',', $columns),
            implode(',', $placeholders)
        );

        $params = [];
        foreach ($columns as $col) {
            $params[":$col"] = $data[$col];
        }

        $this->oracle->execute($sql, $params);
    }


    public function update(string $table, string $idField, int $id, array $data): void
    {
        $set = [];
        $params = [];

        foreach ($data as $col => $val) {
            if ($col === 'fecha_caducidad') {
                $set[] = "$col = TO_DATE(:$col, 'YYYY-MM-DD')";
            } else {
                $set[] = "$col = :$col";
            }
            $params[":$col"] = $val;
        }

        $params[":id"] = $id;

        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s = :id",
            $table,
            implode(',', $set),
            $idField
        );

        $this->oracle->execute($sql, $params);
    }


    public function delete(string $table, string $idField, int $id): void
    {
        $this->oracle->execute("DELETE FROM {$table} WHERE {$idField} = :id", [':id' => $id]);
    }
}

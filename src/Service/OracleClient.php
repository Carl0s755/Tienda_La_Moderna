<?php

namespace App\Service;

class OracleClient
{
    private $conn;

    public function __construct()
    {
        $username = 'tienda';
        $password = 'tienda123';
        $connectionString = "(DESCRIPTION =
            (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521))
            (CONNECT_DATA = (SERVICE_NAME = xepdb1))
        )";

        $this->conn = oci_connect($username, $password, $connectionString, 'AL32UTF8');

        if (!$this->conn) {
            $e = oci_error();
            throw new \RuntimeException('Error de conexión Oracle: ' . $e['message']);
        }
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = oci_parse($this->conn, $sql);

        foreach ($params as $key => &$val) {
            $placeholder = ':' . ltrim($key, ':');
            oci_bind_by_name($stmt, $placeholder, $val);
        }

        oci_execute($stmt);

        $rows = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $rows[] = $row;
        }

        oci_free_statement($stmt);
        return $rows;
    }

    public function execute(string $sql, array $params = []): void
    {
        $stmt = oci_parse($this->conn, $sql);

        foreach ($params as $key => &$val) {
            oci_bind_by_name($stmt, $key, $val);
        }

        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            $error = oci_error($stmt);
            oci_free_statement($stmt);
            throw new \RuntimeException('Error al ejecutar SQL: ' . $error['message']);
        }

        oci_commit($this->conn);
        oci_free_statement($stmt);
    }

    public function __destruct()
    {
        if ($this->conn) {
            oci_close($this->conn);
        }
    }

    public function queryOne(string $sql, array $params = []): ?array
    {
        $rows = $this->query($sql, $params);
        return $rows[0] ?? null;
    }
}

<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

$connectionParams = [
    'driver' => 'oci8',
    'user' => 'tienda',
    'password' => 'tienda123',
    'dbname' => '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=XEPDB1)))',
    'charset' => 'AL32UTF8',
];

try {
    $conn = DriverManager::getConnection($connectionParams);
    $result = $conn->executeQuery('SELECT 1 FROM DUAL')->fetchOne();
    echo "✅ Conexión exitosa con Doctrine. Resultado: $result\n";
} catch (\Throwable $e) {
    echo "❌ Error con Doctrine: " . $e->getMessage() . "\n";
}

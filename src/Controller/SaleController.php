<?php

namespace App\Controller;

use App\DTO\SaleDTO;
use App\Service\GenericCrudService;
use App\Service\OracleClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SaleController extends BaseController
{
    public function __construct(
        private GenericCrudService $crud,
        private OracleClient $oracle
    ) {}

    #[Route('/sales', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->success($this->crud->getAll('VENTAS', SaleDTO::class));
    }

    #[Route('/sales/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $sale = $this->crud->getById('VENTAS', 'ID_VENTA', $id, SaleDTO::class);
        return $sale ? $this->success($sale) : $this->error('Sale not found', 404);
    }

    #[Route('/sales', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $this->getRequestData($request);
        $conn = $this->oracle->getConnection();

        $stmt = oci_parse($conn, "BEGIN registrar_venta_json(:id_cliente, :metodo_pago, :fecha_venta, :json_detalles); END;");

        $idCliente = $data['Id_Cliente'] ?? null;
        oci_bind_by_name($stmt, ":id_cliente", $idCliente);
        oci_bind_by_name($stmt, ":metodo_pago", $data['metodo_pago']);
        $fecha = date('d-m-Y', strtotime($data['fecha_venta']));
        oci_bind_by_name($stmt, ":fecha_venta", $fecha);

        $jsonDetalles = json_encode($data['detalles']);
        $lob = oci_new_descriptor($conn, OCI_D_LOB);
        $lob->writeTemporary($jsonDetalles, OCI_TEMP_CLOB);

        oci_bind_by_name($stmt, ":json_detalles", $lob, -1, OCI_B_CLOB);

        oci_execute($stmt);
        $lob->free();

        return $this->jsonCreated();
    }

    #[Route('/sales/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = $this->getRequestData($request);

        if (!empty($data['FECHA'])) {
            $data['FECHA'] = "TO_DATE('{$data['FECHA']}', 'YYYY-MM-DD')";
        }

        $this->crud->update('VENTAS', 'ID_VENTA', $id, $data, ['FECHA']);
        return $this->jsonUpdated();
    }

    #[Route('/sales/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->oracle->execute("BEGIN eliminar_venta_y_reponer_stock(:id); END;", [
                ':id' => $id
            ]);
            return $this->jsonDeleted();
        } catch (\Exception $e) {
            return $this->jsonError('Error al eliminar la venta: ' . $e->getMessage());
        }
    }
}

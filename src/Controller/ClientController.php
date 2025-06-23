<?php

namespace App\Controller;

use App\DTO\ClientDTO;
use App\Service\GenericCrudService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class ClientController extends BaseController
{
    public function __construct(private GenericCrudService $crud) {}

    #[Route('/clients', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->success($this->crud->getAll('CLIENTES', ClientDTO::class));
    }

    #[Route('/clients/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $client = $this->crud->getById('CLIENTES', 'ID_CLIENTE', $id, ClientDTO::class);
        return $client ? $this->success($client) : $this->error('Client not found', 404);
    }

    /**
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=ClientDTO::class)
     * )
     * @OA\Response(response=201, description="Cliente creado exitosamente")
     * @OA\Response(response=400, description="Datos incompletos")
     */
    #[Route('/clients', methods: ['POST'])]
    public function create(#[MapRequestPayload] ClientDTO $client): JsonResponse
    {
        $this->crud->insert('CLIENTES', [
            'NOMBRE'   => $client->nombre,
            'EMAIL'    => $client->email,
            'TELEFONO' => $client->telefono,
        ]);

        return $this->jsonCreated();
    }


    /**
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=ClientDTO::class)
     * )
     * @OA\Response(response=200, description="Cliente actualizado exitosamente")
     */
    #[Route('/clients/{id}', methods: ['PUT'])]
    public function update(int $id, #[MapRequestPayload] ClientDTO $client): JsonResponse
    {
        $this->crud->update('CLIENTES', 'ID_CLIENTE', $id, [
            'NOMBRE'   => $client->nombre,
            'EMAIL'    => $client->email,
            'TELEFONO' => $client->telefono,
        ]);

        return $this->jsonUpdated();
    }

    #[Route('/clients/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->crud->delete('CLIENTES', 'ID_CLIENTE', $id);
        return $this->jsonDeleted();
    }
}

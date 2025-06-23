<?php

namespace App\Controller;

use App\DTO\ProviderDTO;
use App\Service\GenericCrudService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Nelmio\ApiDocBundle\Annotation\Model;

class ProviderController extends BaseController
{
    public function __construct(private GenericCrudService $crud) {}

    #[Route('/providers', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->success($this->crud->getAll('PROVEEDORES', ProviderDTO::class));
    }

    #[Route('/providers/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $provider = $this->crud->getById('PROVEEDORES', 'ID_PROVEEDOR', $id, ProviderDTO::class);
        return $provider ? $this->success($provider) : $this->error('Provider not found', 404);
    }

    #[Route('/providers/list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $results = $this->crud->query("
            SELECT ID_PROVEEDOR AS id, NOMBRE AS nombre
            FROM PROVEEDORES
            ORDER BY NOMBRE
        ");
        return $this->success($results);
    }

    /**
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=ProviderDTO::class)
     * )
     * @OA\Response(response=201, description="Proveedor creado exitosamente")
     */
    #[Route('/providers', methods: ['POST'])]
    public function create(#[MapRequestPayload] ProviderDTO $provider): JsonResponse
    {
        $this->crud->insert('PROVEEDORES', [
            'NOMBRE'   => $provider->nombre,
            'CONTACTO' => $provider->contacto,
            'TELEFONO' => $provider->telefono,
        ]);

        return $this->jsonCreated();
    }

    /**
     * @OA\RequestBody(
     *     required=true,
     *     @Model(type=ProviderDTO::class)
     * )
     * @OA\Response(response=200, description="Proveedor actualizado exitosamente")
     */
    #[Route('/providers/{id}', methods: ['PUT'])]
    public function update(int $id, #[MapRequestPayload] ProviderDTO $provider): JsonResponse
    {
        $this->crud->update('PROVEEDORES', 'ID_PROVEEDOR', $id, [
            'NOMBRE'   => $provider->nombre,
            'CONTACTO' => $provider->contacto,
            'TELEFONO' => $provider->telefono,
        ]);

        return $this->jsonUpdated();
    }

    #[Route('/providers/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->crud->delete('PROVEEDORES', 'ID_PROVEEDOR', $id);
        return $this->jsonDeleted();
    }
}

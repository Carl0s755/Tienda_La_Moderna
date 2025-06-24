<?php

namespace App\Controller;

use App\DTO\ProductDTO;
use App\Service\GenericCrudService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{
    public function __construct(private GenericCrudService $crud) {}

    #[Route('/products', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->success($this->crud->getAll('PRODUCTOS', ProductDTO::class));
    }

    #[Route('/products/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $product = $this->crud->getById('PRODUCTOS', 'ID_PRODUCTO', $id, ProductDTO::class);
        return $product ? $this->success($product) : $this->error('Not found', 404);
    }

    #[Route('/products', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $this->getRequestData($request);

        try {
            $this->crud->insert('PRODUCTOS', $data, ['fecha_caducidad']);
            return $this->jsonCreated();
        } catch (\Exception $e) {
            return $this->error('Error al crear el producto: ' . $e->getMessage());
        }
    }

    #[Route('/products/{id}', name: 'products_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = $request->toArray();

        try {
            $this->crud->update('PRODUCTOS', 'ID_PRODUCTO', $id, $data, ['fecha_caducidad']);
            return $this->success();
        } catch (\Exception $e) {
            return $this->error('Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    #[Route('/products/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->crud->delete('PRODUCTOS', 'ID_PRODUCTO', $id);
        return $this->jsonDeleted();
    }
}

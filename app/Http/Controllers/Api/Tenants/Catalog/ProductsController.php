<?php

namespace App\Http\Controllers\Api\Tenants\Catalog;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Http\Requests\Tenants\Catalog\ProductRequest;
use App\Repositories\Catalog\ProductsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends BaseController
{
    private mixed $productsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productsRepository = app(ProductsRepository::class);
    }

    /**
     * @comment The index function is used to retrieve all products from the database
     * and return them as a paginated response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $result = $this->productsRepository->getAllWithPaginate($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @comment The create function is used to create a new product in the database
     * using data from the provided request.
     *
     * @param ProductRequest $request
     * @return JsonResponse
     */
    final public function create(ProductRequest $request): JsonResponse
    {
        $result = $this->productsRepository->create($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @comment The update function is used to update a product in the database
     * using data from the provided request.
     *
     * @param int $id
     * @param ProductRequest $request
     * @return JsonResponse
     */
    final public function update(int $id, ProductRequest $request): JsonResponse
    {
        $result = $this->productsRepository->update($id, $request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @comment The destroy function is used to delete a product from the database
     * using the provided product id.
     *
     * @param int $id
     * @return JsonResponse
     */
    final public function destroy(int $id): JsonResponse
    {
        $result = $this->productsRepository->destroy($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @comment The edit function is used to retrieve a specific product from the database
     * using the provided product id.
     *
     * @param int $id
     * @return JsonResponse
     */
    final public function edit(int $id): JsonResponse
    {
        $result = $this->productsRepository->getModelById($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @comment The list function is used to retrieve a list of products from the database.
     *
     * @param Request $request
     * @return JsonResponse
     */
    final public function list(Request $request): JsonResponse
    {
        $result = $this->productsRepository->list($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @comment The search function is used to search for products in the database
     * using the provided search keyword.
     *
     * @param string $search
     * @param Request $request
     * @return JsonResponse
     */
    final public function search(string $search, Request $request): JsonResponse
    {
        $result = $this->productsRepository->search($search, $request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}

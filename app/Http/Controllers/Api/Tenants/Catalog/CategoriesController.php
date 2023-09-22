<?php

namespace App\Http\Controllers\Api\Tenants\Catalog;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Http\Requests\Tenants\Catalog\CategoryRequest;
use App\Repositories\Catalog\CategoriesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriesController extends BaseController
{
    private mixed $categoriesRepository;

    public function __construct()
    {
        parent::__construct();
        $this->categoriesRepository = app(CategoriesRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $result = $this->categoriesRepository->getAllWithPaginate($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @return JsonResponse
     */
    final public function list(): JsonResponse
    {
        $result = $this->categoriesRepository->list();

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    final public function create(CategoryRequest $request): JsonResponse
    {
        $result = $this->categoriesRepository->create($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    final public function edit(int $id): JsonResponse
    {
        $result = $this->categoriesRepository->getModelById($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param int $id
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    final public function update(int $id, CategoryRequest $request): JsonResponse
    {
        $result = $this->categoriesRepository->update($id, $request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    final public function destroy(int $id): JsonResponse
    {
        $result = $this->categoriesRepository->destroy($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param string $search
     * @return JsonResponse
     */
    final public function search(string $search): JsonResponse
    {
        $result = $this->categoriesRepository->search($search);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}

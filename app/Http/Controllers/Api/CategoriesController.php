<?php

namespace App\Http\Controllers\Api;

use App\Repositories\CategoriesRepository;
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
     * @param Request $request
     * @return JsonResponse
     */
    final public function create(Request $request): JsonResponse
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
     * @param Request $request
     * @return JsonResponse
     */
    final public function update(int $id, Request $request): JsonResponse
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

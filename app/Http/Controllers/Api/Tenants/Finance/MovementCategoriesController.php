<?php

namespace App\Http\Controllers\Api\Tenants\Finance;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Http\Requests\Tenants\Statistics\MovementCategories\MovementCategoriesRequest;
use App\Repositories\Finance\MovementCategoriesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovementCategoriesController extends BaseController
{
    private mixed $movementCategoriesRepository;

    public function __construct()
    {
        parent::__construct();
        $this->movementCategoriesRepository = app(MovementCategoriesRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $result = $this->movementCategoriesRepository->getAllWithPaginate($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function list(Request $request): JsonResponse
    {
        $result = $this->movementCategoriesRepository->list($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param MovementCategoriesRequest $request
     * @return JsonResponse
     */
    final public function create(MovementCategoriesRequest $request): JsonResponse
    {
        $result = $this->movementCategoriesRepository->create($request->all());

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
        $result = $this->movementCategoriesRepository->getModelById($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param int $id
     * @param MovementCategoriesRequest $request
     * @return JsonResponse
     */
    final public function update(int $id, MovementCategoriesRequest $request): JsonResponse
    {
        $result = $this->movementCategoriesRepository->update($id, $request->all());

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
        $result = $this->movementCategoriesRepository->destroy($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TenantsRequest;
use App\Repositories\TenantsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantsController extends BaseController
{
    private mixed $tenantsRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->tenantsRepository = app(TenantsRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $result = $this->tenantsRepository->getAllWithPaginate($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param TenantsRequest $request
     * @return JsonResponse
     */
    final public function create(TenantsRequest $request): JsonResponse
    {
        $result = $this->tenantsRepository->create($request->all());

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
        $result = $this->tenantsRepository->getModelById($id);

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
        $result = $this->tenantsRepository->update($id, $request->all());

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
        $result = $this->tenantsRepository->destroy($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}

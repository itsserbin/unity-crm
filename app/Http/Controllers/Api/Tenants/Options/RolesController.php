<?php

namespace App\Http\Controllers\Api\Tenants\Options;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Http\Requests\Tenants\Options\RolesRequest;
use App\Repositories\Options\RolesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolesController extends BaseController
{
    private mixed $rolesRepository;

    public function __construct()
    {
        parent::__construct();
        $this->rolesRepository = app(RolesRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $result = $this->rolesRepository->getAllWithPaginate($request->all());

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
        $result = $this->rolesRepository->list();

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param RolesRequest $request
     * @return JsonResponse
     */
    final public function create(RolesRequest $request): JsonResponse
    {
        $result = $this->rolesRepository->create($request->all());

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
        $result = $this->rolesRepository->getModelById($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param int $id
     * @param RolesRequest $request
     * @return JsonResponse
     */
    final public function update(int $id, RolesRequest $request): JsonResponse
    {
        $result = $this->rolesRepository->update($id, $request->all());

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
        $result = $this->rolesRepository->destroy($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param string $search
     * @param Request $request
     * @return JsonResponse
     */
    final public function search(string $search, Request $request): JsonResponse
    {
        $result = $this->rolesRepository->search($search, $request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Tenants\Options;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Repositories\Options\StatusesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusesController extends BaseController
{
    private mixed $statusesRepository;

    public function __construct()
    {
        parent::__construct();
        $this->statusesRepository = app(StatusesRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $result = $this->statusesRepository->getAllWithPaginate($request->all());

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
        $result = $this->statusesRepository->list();

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
        $result = $this->statusesRepository->create($request->all());

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
        $result = $this->statusesRepository->getModelById($id);

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
        $result = $this->statusesRepository->update($id, $request->all());

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
        $result = $this->statusesRepository->destroy($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    final public function setPublished(Request $request): JsonResponse
    {
        $result = $this->statusesRepository->setPublished($request->get('id'), $request->get('value'));

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}

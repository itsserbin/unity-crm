<?php

namespace App\Http\Controllers\Api\Tenants\Options;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Http\Requests\Tenants\Options\SourcesRequest;
use App\Repositories\Options\SourcesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SourcesController extends BaseController
{
    private mixed $sourcesRepository;

    public function __construct()
    {
        parent::__construct();
        $this->sourcesRepository = app(SourcesRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $result = $this->sourcesRepository->getAllWithPaginate($request->all());

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
        $result = $this->sourcesRepository->list();

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param SourcesRequest $request
     * @return JsonResponse
     */
    final public function create(SourcesRequest $request): JsonResponse
    {
        $result = $this->sourcesRepository->create($request->all());

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
        $result = $this->sourcesRepository->getModelById($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param int $id
     * @param SourcesRequest $request
     * @return JsonResponse
     */
    final public function update(int $id, SourcesRequest $request): JsonResponse
    {
        $result = $this->sourcesRepository->update($id, $request->all());

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
        $result = $this->sourcesRepository->destroy($id);

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
        $result = $this->sourcesRepository->search($search, $request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}

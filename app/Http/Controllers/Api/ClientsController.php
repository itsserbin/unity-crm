<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ClientsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientsController extends BaseController
{
    private mixed $clientsRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->clientsRepository = app(ClientsRepository::class);
    }

    final public function index(Request $request): JsonResponse
    {
        return $this->returnResponse([
            'success' => true,
            'result' => $this->clientsRepository->getAllWithPaginate($request->all()),
        ]);
    }

    final public function create(Request $request): JsonResponse
    {
        return $this->returnResponse([
            'success' => true,
            'result' => $this->clientsRepository->create($request->all()),
        ]);
    }

    final public function search(string $search, Request $request): JsonResponse
    {
        return $this->returnResponse([
            'success' => true,
            'result' => $this->clientsRepository->search($search, $request->all()),
        ]);
    }

    final public function destroy(int $id): JsonResponse
    {
        $result = $this->clientsRepository->destroy($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    final public function edit(int $id): JsonResponse
    {
        return $this->returnResponse([
            'success' => true,
            'result' => $this->clientsRepository->getModelById($id),
        ]);
    }

    final public function update(int $id, Request $request): JsonResponse
    {
        return $this->returnResponse([
            'success' => true,
            'result' => $this->clientsRepository->update($id, $request->all()),
        ]);
    }

//    final public function statuses(): JsonResponse
//    {
//        return $this->returnResponse([
//            'statuses' => ClientStatus::state,
//            'subStatuses' => ClientSubStatus::state
//        ]);
//    }
}

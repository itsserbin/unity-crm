<?php

namespace App\Http\Controllers\Api\Tenants\CRM;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Http\Requests\Tenants\CRM\ClientCreateRequest;
use App\Repositories\CRM\ClientsRepository;
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
        $result = $this->clientsRepository->getAllWithPaginate($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result
        ]);
    }

    final public function create(ClientCreateRequest $request): JsonResponse
    {
        $result = $this->clientsRepository->create($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result
        ]);
    }

    final public function search(string $search, Request $request): JsonResponse
    {
        $result = $this->clientsRepository->search($search, $request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result
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
        $result = $this->clientsRepository->getModelById($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result
        ]);
    }

    final public function update(int $id, Request $request): JsonResponse
    {
        $result = $this->clientsRepository->update($id, $request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result
        ]);
    }

    final public function list(Request $request): JsonResponse
    {
        $result = $this->clientsRepository->list($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result
        ]);
    }

    final public function createClientAddress(int $id, Request $request): JsonResponse
    {
        $result = $this->clientsRepository->createClientAddress($id, $request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OrdersRequest;
use App\Repositories\OrdersRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends BaseController
{
    private mixed $ordersRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->ordersRepository = app(OrdersRepository::class);
    }

    final public function index(Request $request): JsonResponse
    {
        return $this->returnResponse([
            'success' => true,
            'result' => $this->ordersRepository->getAllWithPaginate($request->all()),
        ]);
    }

    final public function create(OrdersRequest $request): JsonResponse
    {
        return $this->returnResponse([
            'success' => true,
            'result' => $this->ordersRepository->create($request->all()),
        ]);
    }

    final public function search(string $search, Request $request): JsonResponse
    {
        return $this->returnResponse([
            'success' => true,
            'result' => $this->ordersRepository->search($search, $request->all()),
        ]);
    }

    final public function destroy(int $id): JsonResponse
    {
        $result = $this->ordersRepository->destroy($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    final public function edit(int $id): JsonResponse
    {
        return $this->returnResponse([
            'success' => true,
            'result' => $this->ordersRepository->getModelById($id),
        ]);
    }

    final public function update(int $id, OrdersRequest $request): JsonResponse
    {
        return $this->returnResponse([
            'success' => true,
            'result' => $this->ordersRepository->update($id, $request->all()),
        ]);
    }
}

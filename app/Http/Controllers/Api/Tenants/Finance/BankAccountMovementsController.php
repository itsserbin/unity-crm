<?php

namespace App\Http\Controllers\Api\Tenants\Finance;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Http\Requests\Tenants\Statistics\BankAccountMovements\CreateMovementRequest;
use App\Repositories\Finance\BankAccountMovementsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankAccountMovementsController extends BaseController
{
    private mixed $bankAccountMovementsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->bankAccountMovementsRepository = app(BankAccountMovementsRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $result = $this->bankAccountMovementsRepository->getAllWithPaginate($request->all());

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
        $result = $this->bankAccountMovementsRepository->list();

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param CreateMovementRequest $request
     * @return JsonResponse
     */
    final public function create(CreateMovementRequest $request): JsonResponse
    {
        $result = $this->bankAccountMovementsRepository->create($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    final public function massCreate(Request $request): JsonResponse
    {
        $this->bankAccountMovementsRepository->massCreate($request->all());

        return $this->returnResponse([
            'success' => true,
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    final public function edit(int $id): JsonResponse
    {
        $result = $this->bankAccountMovementsRepository->getModelById($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param int $id
     * @param CreateMovementRequest $request
     * @return JsonResponse
     */
    final public function update(int $id, CreateMovementRequest $request): JsonResponse
    {
        $result = $this->bankAccountMovementsRepository->update($id, $request->all());

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
        $result = $this->bankAccountMovementsRepository->destroy($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}

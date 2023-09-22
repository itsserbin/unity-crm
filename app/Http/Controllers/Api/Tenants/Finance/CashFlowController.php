<?php

namespace App\Http\Controllers\Api\Tenants\Finance;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Repositories\Finance\CashFlowRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CashFlowController extends BaseController
{
    private mixed $cashFlowRepository;

    public function __construct()
    {
        parent::__construct();
        $this->cashFlowRepository = app(CashFlowRepository::class);
    }

    final public function index(Request $request): JsonResponse
    {
        $result = $this->cashFlowRepository->getAllWithPaginate($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result
        ]);
    }
}

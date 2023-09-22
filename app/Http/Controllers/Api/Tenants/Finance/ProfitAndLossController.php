<?php

namespace App\Http\Controllers\Api\Tenants\Finance;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\Finance\ProfitAndLossRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfitAndLossController extends BaseController
{
    private mixed $profitAndLossRepository;

    public function __construct()
    {
        parent::__construct();
        $this->profitAndLossRepository = app(ProfitAndLossRepository::class);
    }

    final public function index(Request $request): JsonResponse
    {
        $result = $this->profitAndLossRepository->getAllWithPaginate($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result
        ]);
    }
}

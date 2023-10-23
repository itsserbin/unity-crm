<?php

namespace App\Http\Controllers\Api\Tenants\Statistics;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Repositories\Statistics\ProfitStatisticsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfitStatisticsController extends BaseController
{
    private mixed $profitStatisticsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->profitStatisticsRepository = app(ProfitStatisticsRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $result = $this->profitStatisticsRepository->getAllWithPaginate($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}

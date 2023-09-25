<?php

namespace App\Http\Controllers\Api\Tenants\Statistics;

use App\Http\Controllers\Api\Tenants\BaseController;
use App\Repositories\Statistics\OrderStatisticsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderStatisticsController extends BaseController
{
    private mixed $orderStatisticsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderStatisticsRepository = app(OrderStatisticsRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $result = $this->orderStatisticsRepository->getAllWithPaginate($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}

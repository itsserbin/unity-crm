<?php

namespace App\Http\Controllers\Tenants\Statistics;

use App\Http\Controllers\Tenants\BaseController;
use App\Models\Options\StatusGroup;
use App\Repositories\Options\StatusesRepository;
use App\Repositories\Options\StatusGroupsRepository;
use App\Repositories\Statistics\OrderStatisticsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderStatisticsController extends BaseController
{
    private mixed $orderStatisticsRepository;
    private mixed $statusGroupsRepository;
    private mixed $statusesRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderStatisticsRepository = app(OrderStatisticsRepository::class);
        $this->statusGroupsRepository = app(StatusGroupsRepository::class);
        $this->statusesRepository = app(StatusesRepository::class);
    }

    final public function create(Request $request): Response
    {
        $orderStatistics = $this->orderStatisticsRepository->getAllWithPaginate($request->all());
        $statusGroups = $this->statusGroupsRepository->list();
        $statuses = $this->statusesRepository->list();

        return Inertia::render('Tenants/Statistics/OrderStatistics/Index', [
            'orderStatistics' => $orderStatistics,
            'statusGroups' => $statusGroups,
            'statuses' => $statuses
        ]);
    }
}

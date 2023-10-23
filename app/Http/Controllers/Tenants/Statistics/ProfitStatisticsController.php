<?php

namespace App\Http\Controllers\Tenants\Statistics;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Options\StatusesRepository;
use App\Repositories\Options\StatusGroupsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfitStatisticsController extends BaseController
{
    private mixed $statusGroupsRepository;
    private mixed $statusesRepository;

    public function __construct()
    {
        parent::__construct();
        $this->statusGroupsRepository = app(StatusGroupsRepository::class);
        $this->statusesRepository = app(StatusesRepository::class);
    }

    final public function create(Request $request): Response
    {
        $statusGroups = $this->statusGroupsRepository->list();
        $statuses = $this->statusesRepository->list();

        return Inertia::render('Tenants/Statistics/Profit/Index', [
            'statusGroups' => $statusGroups,
            'statuses' => $statuses
        ]);
    }
}

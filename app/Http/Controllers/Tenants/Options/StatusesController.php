<?php

namespace App\Http\Controllers\Tenants\Options;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Options\StatusesRepository;
use App\Repositories\Options\StatusGroupsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StatusesController extends BaseController
{
    private mixed $statusesRepository;
    private mixed $statusGroupsRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->statusesRepository = app(StatusesRepository::class);
        $this->statusGroupsRepository = app(StatusGroupsRepository::class);
    }

    final public function create(Request $request): Response
    {
        $statuses = $this->statusesRepository->getAllWithPaginate($request->all());
        $statusGroups = $this->statusGroupsRepository->getAllWithPaginate($request->all());

        return Inertia::render('Tenants/Options/Statuses/Index', [
            'statuses' => $statuses,
            'statusGroups' => $statusGroups
        ]);
    }
}

<?php

namespace App\Http\Controllers\Tenants\Finance;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Finance\CashFlowRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CashFlowController extends BaseController
{

    private mixed $cashFlowRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->cashFlowRepository = app(CashFlowRepository::class);
    }

    final public function create(Request $request): Response
    {
        $cashFlow = $this->cashFlowRepository->getAllWithPaginate($request->all());

        return Inertia::render('Tenants/Finance/CashFlow/Index', [
            'cashFlow' => $cashFlow
        ]);
    }
}

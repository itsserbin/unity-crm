<?php

namespace App\Http\Controllers\Tenants\Finance;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Finance\ProfitAndLossRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfitAndLossController extends BaseController
{

    private mixed $profitAndLossRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->profitAndLossRepository = app(ProfitAndLossRepository::class);
    }

    final public function create(Request $request): Response
    {
        $profitAndLoss = $this->profitAndLossRepository->getAllWithPaginate($request->all());

        return Inertia::render('Tenants/Finance/ProfitAndLoss/Index', [
            'profitAndLoss' => $profitAndLoss
        ]);
    }
}

<?php

namespace App\Http\Controllers\Tenants\Finance;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Finance\AccountsRepository;
use App\Repositories\Finance\BankAccountMovementsRepository;
use App\Repositories\Finance\BankAccountsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BankAccountMovementsController extends BaseController
{
    private mixed $bankAccountMovementsRepository;
    private mixed $accountsRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->bankAccountMovementsRepository = app(BankAccountMovementsRepository::class);
        $this->accountsRepository = app(AccountsRepository::class);
    }

    final public function create(Request $request): Response
    {
        $data = $this->bankAccountMovementsRepository->getAllWithPaginate($request->all());
        $accounts = $this->accountsRepository->list();

        return Inertia::render('Tenants/Finance/BankAccountMovements/Index', [
            'data' => $data,
            'accounts' => $accounts,
        ]);
    }
}

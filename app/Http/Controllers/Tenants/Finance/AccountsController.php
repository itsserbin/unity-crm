<?php

namespace App\Http\Controllers\Tenants\Finance;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Finance\AccountsRepository;
use App\Repositories\Finance\BankAccountsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccountsController extends BaseController
{

    private mixed $accountsRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->accountsRepository = app(AccountsRepository::class);
    }

    final public function create(Request $request): Response
    {
        $accounts = $this->accountsRepository->getAllWithPaginate($request->all());

        return Inertia::render('Tenants/Finance/Accounts/Index', [
            'accounts' => $accounts
        ]);
    }
}

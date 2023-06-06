<?php

namespace App\Http\Controllers\Tenants\Statistics;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Statistics\BankAccountsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BankAccountsController extends BaseController
{

    private mixed $bankAccountsRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->bankAccountsRepository = app(BankAccountsRepository::class);
    }

    final public function create(Request $request): Response
    {
        $bankAccounts = $this->bankAccountsRepository->getAllWithPaginate($request->all());

        return Inertia::render('Tenants/Statistics/BankAccounts/Index', [
            'bankAccounts' => $bankAccounts
        ]);
    }
}

<?php

namespace App\Http\Controllers\Tenants\Statistics;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Statistics\BankAccountMovementsRepository;
use App\Repositories\Statistics\BankAccountsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BankAccountMovementsController extends BaseController
{
    private mixed $bankAccountMovementsRepository;
    private mixed $bankAccountsRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->bankAccountMovementsRepository = app(BankAccountMovementsRepository::class);
        $this->bankAccountsRepository = app(BankAccountsRepository::class);
    }

    final public function create(Request $request): Response
    {
        $data = $this->bankAccountMovementsRepository->getAllWithPaginate($request->all());
        $bankAccounts = $this->bankAccountsRepository->list();

        return Inertia::render('Tenants/Statistics/BankAccountMovements/Index', [
            'data' => $data,
            'bankAccounts' => $bankAccounts,
        ]);
    }
}

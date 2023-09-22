<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Models\Finance\BankAccount;
use App\Services\MonobankService;
use Illuminate\Http\Request;

class MonobankController extends BaseController
{
    private mixed $monobankService;

    public function __construct()
    {
        parent::__construct();
        $this->monobankService = app(MonobankService::class);
    }

    final public function clientInfo(string $token): array
    {
        return $this->monobankService->getPersonalInfo($token);
    }

    final public function extract(Request $request): array
    {
        $account = BankAccount::where('id', $request->get('account'))->first();

        $data = [
            'token' => $account->data['api_key'],
            'from' => $request->get('date')['start'] ?? null,
            'to' => $request->get('date')['end'] ?? null,
            'clientId' => $account->data['account']['id']
        ];

        return $this->monobankService->getExtract($data);
    }
}

<?php

namespace App\Http\Controllers\Tenants\CRM;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\CRM\ClientsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientsController extends BaseController
{
    private mixed $clientsRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->clientsRepository = app(ClientsRepository::class);
    }

    final public function create(Request $request): Response
    {
        $clients = $this->clientsRepository->getAllWithPaginate($request->all());

        return Inertia::render('Tenants/CRM/Clients/Index', [
            'clients' => $clients
        ]);
    }
}

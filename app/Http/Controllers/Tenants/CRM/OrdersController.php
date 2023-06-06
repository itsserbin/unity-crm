<?php

namespace App\Http\Controllers\Tenants\CRM;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Catalog\ProductsRepository;
use App\Repositories\CRM\ClientsRepository;
use App\Repositories\CRM\OrdersRepository;
use App\Repositories\Options\DeliveryServicesRepository;
use App\Repositories\Options\SourcesRepository;
use App\Repositories\Options\StatusGroupsRepository;
use App\Repositories\UsersRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrdersController extends BaseController
{
    private mixed $productsRepository;
    private mixed $clientsRepository;
    private mixed $ordersRepository;
    private mixed $sourcesRepository;
    private mixed $statusGroupsRepository;
    private mixed $deliveryServicesRepository;
    private mixed $usersRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->productsRepository = app(ProductsRepository::class);
        $this->clientsRepository = app(ClientsRepository::class);
        $this->ordersRepository = app(OrdersRepository::class);
        $this->sourcesRepository = app(SourcesRepository::class);
        $this->statusGroupsRepository = app(StatusGroupsRepository::class);
        $this->deliveryServicesRepository = app(DeliveryServicesRepository::class);
        $this->usersRepository = app(UsersRepository::class);
    }

    final public function create(Request $request): Response
    {
        $orders = $this->ordersRepository->getAllWithPaginate($request->all());
        $sources = $this->sourcesRepository->list();
        $statuses = $this->statusGroupsRepository->list();
        $users = $this->usersRepository->list();
        $deliveryServices = $this->deliveryServicesRepository->list();
        $products = $this->productsRepository->list();
        $clients = $this->clientsRepository->list();

        return Inertia::render('Tenants/CRM/Orders/Index', [
            'orders' => $orders,
            'sources' => $sources,
            'statuses' => $statuses,
            'users' => $users,
            'deliveryServices' => $deliveryServices,
            'products' => $products,
            'clients' => $clients,
        ]);
    }
}

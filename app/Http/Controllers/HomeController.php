<?php

namespace App\Http\Controllers;

use App\Repositories\CategoriesRepository;
use App\Repositories\ClientsRepository;
use App\Repositories\OrdersRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\SourcesRepository;
use App\Repositories\StatusesRepository;
use App\Repositories\StatusGroupsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    private mixed $productsRepository;
    private mixed $categoriesRepository;
    private mixed $clientsRepository;
    private mixed $ordersRepository;
    private mixed $sourcesRepository;
    private mixed $statusesRepository;
    private mixed $statusGroupsRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->productsRepository = app(ProductsRepository::class);
        $this->categoriesRepository = app(CategoriesRepository::class);
        $this->clientsRepository = app(ClientsRepository::class);
        $this->ordersRepository = app(OrdersRepository::class);
        $this->sourcesRepository = app(SourcesRepository::class);
        $this->statusesRepository = app(StatusesRepository::class);
        $this->statusGroupsRepository = app(StatusGroupsRepository::class);
    }

    final public function dashboard(): Response
    {
        return Inertia::render('Dashboard');
    }

    final public function products(Request $request): Response
    {
        $products = $this->productsRepository->getAllWithPaginate($request->all());

        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    final public function categories(Request $request): Response
    {
        $categories = $this->categoriesRepository->getAllWithPaginate($request->all());

        return Inertia::render('Categories/Index', [
            'categories' => $categories
        ]);
    }

    final public function clients(Request $request): Response
    {
        $clients = $this->clientsRepository->getAllWithPaginate($request->all());

        return Inertia::render('Clients/Index', [
            'clients' => $clients
        ]);
    }

    final public function orders(Request $request): Response
    {
        $orders = $this->ordersRepository->getAllWithPaginate($request->all());

        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }

    final public function sources(Request $request): Response
    {
        $sources = $this->sourcesRepository->getAllWithPaginate($request->all());

        return Inertia::render('Sources/Index', [
            'sources' => $sources
        ]);
    }

    final public function statuses(Request $request): Response
    {
        $statuses = $this->statusesRepository->getAllWithPaginate($request->all());
        $statusGroups = $this->statusGroupsRepository->getAllWithPaginate($request->all());

        return Inertia::render('Statuses/Index', [
            'statuses' => $statuses,
            'statusGroups' => $statusGroups
        ]);
    }
}

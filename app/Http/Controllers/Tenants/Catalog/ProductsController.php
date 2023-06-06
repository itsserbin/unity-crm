<?php

namespace App\Http\Controllers\Tenants\Catalog;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Catalog\CategoriesRepository;
use App\Repositories\Catalog\ProductsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductsController extends BaseController
{
    private mixed $productsRepository;
    private mixed $categoriesRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->productsRepository = app(ProductsRepository::class);
        $this->categoriesRepository = app(CategoriesRepository::class);
    }

    final public function create(Request $request): Response
    {
        $products = $this->productsRepository->getAllWithPaginate($request->all());
        $categories = $this->categoriesRepository->list();

        return Inertia::render('Tenants/Catalog/Products/Index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}

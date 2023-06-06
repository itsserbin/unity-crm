<?php

namespace App\Http\Controllers\Tenants\Catalog;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Catalog\CategoriesRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoriesController extends BaseController
{
    private mixed $categoriesRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->categoriesRepository = app(CategoriesRepository::class);
    }

    final public function create(Request $request): Response
    {
        $categories = $this->categoriesRepository->getAllWithPaginate($request->all());

        return Inertia::render('Tenants/Catalog/Categories/Index', [
            'categories' => $categories
        ]);
    }
}

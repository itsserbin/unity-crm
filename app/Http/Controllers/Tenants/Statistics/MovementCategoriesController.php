<?php

namespace App\Http\Controllers\Tenants\Statistics;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Statistics\MovementCategoriesRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MovementCategoriesController extends BaseController
{

    private mixed $movementCategoriesRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->movementCategoriesRepository = app(MovementCategoriesRepository::class);
    }

    final public function create(Request $request): Response
    {
        $movementCategories = $this->movementCategoriesRepository->getAllWithPaginate($request->all());

        return Inertia::render('Tenants/Statistics/MovementCategories/Index', [
            'movementCategories' => $movementCategories
        ]);
    }
}

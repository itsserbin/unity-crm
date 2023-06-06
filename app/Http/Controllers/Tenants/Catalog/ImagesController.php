<?php

namespace App\Http\Controllers\Tenants\Catalog;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Catalog\ImagesRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ImagesController extends BaseController
{
    private mixed $imagesRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->imagesRepository = app(ImagesRepository::class);
    }

    final public function create(Request $request): Response
    {
        $images = $this->imagesRepository->getAllWithPaginate($request->all());

        return Inertia::render('Tenants/Catalog/Images/Index', [
            'images' => $images
        ]);
    }
}

<?php

namespace App\Http\Controllers\Tenants\Options;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Options\SourcesRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SourcesController extends BaseController
{
    private mixed $sourcesRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->sourcesRepository = app(SourcesRepository::class);
    }

    final public function create(Request $request): Response
    {
        $sources = $this->sourcesRepository->getAllWithPaginate($request->all());

        return Inertia::render('Tenants/Options/Sources/Index', [
            'sources' => $sources
        ]);
    }
}

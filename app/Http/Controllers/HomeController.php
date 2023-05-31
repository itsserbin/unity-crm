<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enums\DeliveryServices;
use App\Models\Tenant;
use App\Repositories\CategoriesRepository;
use App\Repositories\ClientsRepository;
use App\Repositories\DeliveryServicesRepository;
use App\Repositories\ImagesRepository;
use App\Repositories\OrdersRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\SourcesRepository;
use App\Repositories\StatusesRepository;
use App\Repositories\StatusGroupsRepository;
use App\Repositories\UsersRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    private mixed $productsRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->productsRepository = app(ProductsRepository::class);
    }

    final public function index(): Response|Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
    {
        if (auth()->user()) {
            return redirect(route('dashboard'));
        } else {
            return Inertia::render('Welcome', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
            ]);
        }
    }

    final public function dashboard(): Response
    {
        $tenants = Tenant::where('user_id', auth()->id())->with('domains')->get();

        return Inertia::render('Dashboard', [
            'tenants' => $tenants,
        ]);
    }
}

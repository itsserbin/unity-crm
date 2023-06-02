<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Repositories\TenantsRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{
    private mixed $tenantsRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->tenantsRepository = app(TenantsRepository::class);
    }

    final public function index(): Response|Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
    {
        if (auth()->user()) {
            return redirect(route('dashboard'));
        } else {
            return Inertia::render('Welcome');
        }
    }

    final public function dashboard(Request $request): Response
    {
        $tenants = $this->tenantsRepository->getAllWithPaginate($request->all());

        return Inertia::render('Dashboard', [
            'tenants' => $tenants,
        ]);
    }

    final public function tenant($id, Request $request)
    {
        $tenant = Tenant::with('domains')->find($id);
        $user = auth()->user();
//        $tenant->run(function () use ($user) {
//            Auth::login($user);
//        });
//        return redirect(route('login'));
        return Inertia::location(env('APP_PROTOCOL') . $tenant->domains[0]->domain . '?user=' . Crypt::encryptString($user->id));
    }
}

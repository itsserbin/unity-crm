<?php

namespace App\Http\Controllers\Tenants;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends BaseController
{
    final public function __construct()
    {
        parent::__construct();
    }

    final public function create(): Response
    {
        return Inertia::render('Tenants/Dashboard/Dashboard');
    }
}

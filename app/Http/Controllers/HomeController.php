<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    final public function __construct()
    {
        parent::__construct();
    }

    final public function dashboard(): Response
    {
        return Inertia::render('Dashboard');
    }

    final public function products(): Response
    {
        return Inertia::render('Products/Index');
    }

    final public function categories(): Response
    {
        return Inertia::render('Categories/Index');
    }
}

<?php

namespace App\Http\Controllers\Tenants\Options;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Options\RolesRepository;
use App\Repositories\Options\UsersRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UsersController extends BaseController
{
    private mixed $usersRepository;
    private mixed $rolesRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->usersRepository = app(UsersRepository::class);
        $this->rolesRepository = app(RolesRepository::class);
    }

    final public function create(Request $request): Response
    {
        $users = $this->usersRepository->getAllWithPaginate($request->all());
        $roles = $this->rolesRepository->list();

        return Inertia::render('Tenants/Options/Users/Index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }
}

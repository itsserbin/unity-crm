<?php

namespace App\Http\Controllers\Tenants\Options;

use App\Http\Controllers\Tenants\BaseController;
use App\Repositories\Options\RolesRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class RolesController extends BaseController
{
    private mixed $rolesRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->rolesRepository = app(RolesRepository::class);
    }

    final public function create(Request $request): Response
    {
        $roles = $this->rolesRepository->getAllWithPaginate($request->all());
        $permissions = Permission::select(['id', 'name'])->get();

        return Inertia::render('Tenants/Options/Roles/Index', [
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }
}

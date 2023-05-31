<?php

namespace App\Repositories;

use App\Models\Tenant as Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class TenantsRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelById(string $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->coreFind($this->model, $id);
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $columns = [
            'id',
            'name',
            'user_id',
            'data',
        ];

        $model = $this->model::select($columns);

        return $model
            ->where('user_id', auth()->id())
            ->orderBy(
                $data['sort']['column'] ?? 'id',
                $data['sort']['type'] ?? 'desc'
            )
            ->with('domains')
            ->paginate($data['perPage'] ?? 15);
    }

    final public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $params = [
            'id' => $data['id'],
            'name' => $data['name'],
            'user_id' => auth()->id(),
        ];
        $tenant = $this->coreCreate($this->model, $params);

        $tenant->domains()->create([
            'domain' => $data['id'] . '.' . env('APP_DOMAIN')
        ]);

        $tenant->run(function () {
            User::create([
                'name' => auth()->user()->name,
                'phone' => auth()->user()->phone,
                'email' => auth()->user()->email,
                'password' => auth()->user()->password,
            ]);
            Artisan::call('db:seed');
        });


        return $tenant;
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->coreUpdate($this->model, $id, $data);
    }

    final public function destroy(int $id): int
    {
        return $this->coreDestroy($this->model, $id);
    }
}

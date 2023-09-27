<?php

namespace App\Repositories\Options;

use App\Models\User as Model;
use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UsersRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelById(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model::where('id', $id)->with('roles:id,name')->first();
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $model = $this->model::select($this->getTableColumns());

        return $model
            ->orderBy(
                $data['sort']['column'] ?? 'id',
                $data['sort']['type'] ?? 'desc'
            )
            ->with('roles:id,name')
            ->paginate($data['perPage'] ?? 15);
    }

    final public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
        $data['password'] = Hash::make($data['password']);
        $model = $this->coreCreate($this->model, $data);
        $model->syncRoles($data['roles'] ?? []);
        return $model;
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $model = $this->coreUpdate($this->model, $id, $data);
        $model->syncRoles($data['roles'] ?? []);
        return $model;
    }

    private function syncRoles(\Illuminate\Database\Eloquent\Model $model, array $roles = [])
    {
        if (count($roles)) {
            return $model->syncRoles($roles);
        }
    }

    final public function destroy(int $id): int
    {
        return $this->coreDestroy($this->model, $id);
    }

    final public function list(array $data = []): Collection
    {
        $model = $this->model::select($this->getTableColumns());

        if (isset($data['query'])) {
            $query = htmlspecialchars($data['query'], ENT_QUOTES, 'UTF-8');
            $model->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('id', 'ilike', "%$query%")
                    ->orWhere('name', 'ilike', "%$query%")
                    ->orWhere('email', 'ilike', "%$query%")
                    ->orWhere('phone', 'ilike', "%$query%");
            });
        }

        return $model->with('roles:id,name')->limit($data['limit'] ?? 15)->get();
    }

    private function getTableColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
            'created_at',
        ];
    }
}

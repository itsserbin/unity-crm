<?php

namespace App\Repositories;

use App\Models\User as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelById(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->coreFind($this->model, $id);
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $columns = [
            'id',
            'name',
            'email',
            'phone',
        ];

        $model = $this->model::select($columns);

        return $model
            ->orderBy(
                $data['sort']['column'] ?? 'id',
                $data['sort']['type'] ?? 'desc'
            )
            ->paginate($data['perPage'] ?? 15);
    }

    final public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
        return $this->coreCreate($this->model, $data);
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
        return $this->coreUpdate($this->model, $id, $data);
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

        return $model->limit($data['limit'] ?? 15)->get();
    }

    private function getTableColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
        ];
    }
}

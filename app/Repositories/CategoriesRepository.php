<?php

namespace App\Repositories;

use App\Models\Category as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoriesRepository extends CoreRepository
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
            'title',
            'description',
            'image',
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
        return $this->coreCreate($this->model, $data);
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->coreUpdate($this->model, $id, $data);
    }

    final public function destroy(int $id): int
    {
        return $this->coreDestroy($this->model, $id);
    }

    final public function list(): Collection
    {
        return $this->model::select(['id', 'title'])->orderBy('id', 'desc')->get();
    }
}

<?php

namespace App\Repositories\Options;

use App\Models\Options\Source as Model;
use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SourcesRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelById(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->coreFind($this->model, $id);
    }

    final public function getModelByColumn(string $column, mixed $value)
    {
        return $this->coreGetModelByColumn($this->model, $column, $value);
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $model = $this->model::select($this->getTableColumns());

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
        return $this->model::select($this->getTableColumns())->orderBy('id', 'desc')->get();
    }

    final public function search(string $query, array $data): LengthAwarePaginator
    {
        return $this->model::select($this->getTableColumns())
            ->where('id', 'LIKE', "%$query%")
            ->orWhere('title', 'LIKE', "%$query%")
            ->orWhere('source', 'LIKE', "%$query%")
            ->paginate($data['perPage'] ?? 15);
    }

    final public function getAll(): Collection
    {
        return $this->model::all();
    }

    private function getTableColumns(): array
    {
        return [
            'id',
            'title',
            'source',
        ];
    }
}

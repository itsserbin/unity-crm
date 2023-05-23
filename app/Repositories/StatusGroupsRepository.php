<?php

namespace App\Repositories;

use App\Models\StatusGroup as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class StatusGroupsRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelById(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->coreFind($this->model, $id);
    }

    final public function getModelBySlug(string $slug): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->coreFindBySlug($this->model, $slug);
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $columns = [
            'id',
            'title',
            'slug',
            'hex',
            'is_system_status',
        ];

        $model = $this->model::select($columns);

        return $model
            ->orderBy(
                $data['sort']['column'] ?? 'id',
                $data['sort']['type'] ?? 'asc'
            )
            ->with('statuses:id,title,group_slug')
            ->paginate($data['perPage'] ?? 15);
    }

    final public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->model::create([
            'title' => $data['title'],
            'hex' => Str::startsWith($data['hex'], '#') ? $data['hex'] : '#' . $data['hex'],
            'slug' => $data['slug'] ?? Str::slug($data['title'])
        ]);
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
        return $this->model::select(['id', 'title', 'slug', 'hex'])->orderBy('id', 'asc')->get();
    }
}

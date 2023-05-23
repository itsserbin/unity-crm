<?php

namespace App\Repositories;

use App\Models\Status as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class StatusesRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelById(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->coreFind($this->model, $id);
    }

    final public function getModelByTitleAndGroupSlug(string $title, string $slug): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model::where(function ($q) use ($title, $slug) {
            $q->where('group_slug', $slug);
            $q->where('title', $title);
        })->first();
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $columns = [
            'id',
            'group_slug',
            'title',
            'is_system_status',
            'orders_count',
            'published',
        ];

        $statusGroupRepository = new StatusGroupsRepository();

        $groups = $statusGroupRepository->list()->pluck('slug')->toArray();

        return $this->model::select($columns)
            ->orderByRaw("FIELD(group_slug, '" . implode("','", $groups) . "')")
            ->with('group')
            ->paginate($data['perPage'] ?? 30);
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
        return $this->model::select(['id', 'group_slug', 'title'])
            ->where('published', 1)
            ->orderBy('id', 'desc')
            ->get();
    }

    final public function setPublished(int $id, int $value): bool
    {
        return $this->model::where('id', $id)->update(['published' => $value]);
    }
}

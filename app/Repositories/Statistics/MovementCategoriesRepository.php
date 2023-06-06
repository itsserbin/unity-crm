<?php

namespace App\Repositories\Statistics;

use App\Models\Statistic\MovementCategory as Model;
use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class MovementCategoriesRepository extends CoreRepository
{
    private int $cacheTime;

    public function __construct()
    {
        parent::__construct();
        $this->cacheTime = 3600;
    }

    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelById(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->coreFind($this->model, $id);
    }

    private function getColumnsForDataTable(): array
    {
        return [
            'id',
            'name',
            'type',
        ];
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $cacheKey = 'getAllWithPaginate_' . serialize($data);

        return Cache::tags(['paginate'])->remember($cacheKey, $this->cacheTime, function () use ($data) {
            return $this
                ->model::select($this->getColumnsForDataTable())
                ->orderBy(
                    $data['sort']['column'] ?? 'id',
                    $data['sort']['type'] ?? 'desc'
                )
                ->paginate($data['perPage'] ?? 15);
        });
    }

    private function removeAllCache()
    {
        return Cache::tags(['list', 'paginate'])->flush();
    }

    final public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = $this->coreCreate($this->model, $data);
        $this->removeAllCache();
        return $model;
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = $this->coreUpdate($this->model, $id, $data);
        $this->removeAllCache();
        return $model;
    }

    final public function destroy(int $id): int
    {
        $result = $this->coreDestroy($this->model, $id);
        $this->removeAllCache();
        return $result;
    }

    final public function list(array $data = []): Collection
    {
        $cacheKey = 'list_' . serialize($data);

        return Cache::tags(['list'])->remember($cacheKey, $this->cacheTime, function () use ($data) {
            $model = $this->model::select(['id', 'title', 'type']);

            if (isset($data['type'])) {
                $model->where('type', $data['type']);
            }

            return $model->orderBy('id', 'desc')->get();
        });
    }

//    final public function search(string $query, array $data): LengthAwarePaginator
//    {
//        $columns = [
//            'id',
//            'name',
//            'data',
//        ];
//
//        return $this->model::select($columns)
//            ->where('id', 'LIKE', "%$query%")
//            ->orWhere('title', 'LIKE', "%$query%")
//            ->orWhere('source', 'LIKE', "%$query%")
//            ->paginate($data['perPage'] ?? 15);
//    }
}

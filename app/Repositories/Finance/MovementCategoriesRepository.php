<?php

namespace App\Repositories\Finance;

use App\Models\Finance\MovementCategory as Model;
use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class MovementCategoriesRepository extends CoreRepository
{
    private int $cacheTime;
    private string $cacheTagPaginate;
    private string $cacheTagList;

    public function __construct()
    {
        parent::__construct();
        $this->cacheTime = 3600;
        $this->cacheTagPaginate = self::class . '_getAllWithPaginate';
        $this->cacheTagList = self::class . '_list';
    }

    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function removeAllCache()
    {
        return Cache::tags([$this->cacheTagList, $this->cacheTagPaginate])->flush();
    }

    final public function getModelById(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->coreFind($this->model, $id);
    }

    private function getColumnsForDataTable(): array
    {
        return [
            'id',
            'title',
            'type',
        ];
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $cacheKey = self::class . '_getAllWithPaginate_' . serialize($data);

        return Cache::tags(['paginate', $this->cacheTagPaginate])
            ->remember($cacheKey, $this->cacheTime, function () use ($data) {
                return $this
                    ->model::select($this->getColumnsForDataTable())
                    ->orderBy(
                        $data['sort']['column'] ?? 'id',
                        $data['sort']['type'] ?? 'desc'
                    )
                    ->paginate($data['perPage'] ?? 15);
            });
    }

    final public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['slug'] = cyrToLat($data['title']);
        $model = $this->coreCreate($this->model, $data);
        $this->removeAllCache();
        return $model;
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['slug'] = cyrToLat($data['title']);
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
        $cacheKey = self::class . 'list_' . serialize($data);

        return Cache::tags(['list', $this->cacheTagList])
            ->remember($cacheKey, $this->cacheTime, function () use ($data) {
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

<?php

namespace App\Repositories\Catalog;

use App\Models\Catalog\Product as Model;
use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelById(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model::where('id', $id)->with('categories:id,title', 'preview')->first();
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $columns = [
            'id',
            'availability',
            'title',
            'trade_price',
            'price',
            'discount_price',
            'preview_id',
            'sku',
        ];

        $model = $this->model::select($columns);

        if (isset($data['filter'])) {
            $model->where(
                $data['filter']['column'],
                $data['filter']['operator'],
                $data['filter']['key']
            );
        }

        return $model
            ->orderBy(
                $data['sort']['column'] ?? 'id',
                $data['sort']['type'] ?? 'desc'
            )
            ->with('categories:id,title', 'preview')
            ->paginate($data['perPage'] ?? 15);
    }

    final public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = $this->coreCreate($this->model, $data);
        $model->categories()->sync($data['categories']);
        return $model;
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = $this->coreUpdate($this->model, $id, $data);
        $model->categories()->sync($data['categories']);
        return $this->coreUpdate($this->model, $id, $data);
    }
//    private function syncCategories($model, $categories): bool
//    {
//        $categoryItems = Arr::pluck($categories, 'id');
//        $model->categories()->sync($categoryItems);
//        return 1;
//    }
    final public function destroy(int $id): int
    {
        return $this->coreDestroy($this->model, $id);
    }

    final public function search(string $query, array $data): LengthAwarePaginator
    {
        $columns = [
            'id',
            'availability',
            'published',
            'title',
            'trade_price',
            'price',
            'discount_price',
            'preview_id',
            'sku',
        ];

        return $this
            ->model::select($columns)
            ->where('id', 'LIKE', "%$query%")
            ->orWhere('title', 'LIKE', "%$query%")
            ->orWhere('trade_price', 'LIKE', "%$query%")
            ->orWhere('price', 'LIKE', "%$query%")
            ->orWhere('discount_price', 'LIKE', "%$query%")
            ->orWhere('sku', 'LIKE', "%$query%")
            ->with('categories:id,title', 'preview')
            ->paginate($data['perPage'] ?? 15);
    }

    final public function list(): Collection
    {
        return $this->model::select([
            'id',
            'title',
            'trade_price',
            'price',
            'preview_id',
            'sku',
            'discount_price',
        ])->with('preview:alt,data,id')->get();
    }
}

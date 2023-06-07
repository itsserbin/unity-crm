<?php

namespace App\Repositories\Statistics;

use App\Models\Statistic\BankAccountMovement as Model;
use App\Repositories\CoreRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BankAccountMovementsRepository extends CoreRepository
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
            'account_id',
            'movement_id',
            'date',
            'sum',
            'balance',
            'category_id',
            'comment',
        ];
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $cacheKey = 'getAllWithPaginate_' . serialize($data);

        return Cache::tags(['paginate'])->remember($cacheKey, $this->cacheTime, function () use ($data) {
            return $this
                ->model::select($this->getColumnsForDataTable())
                ->orderBy(
                    $data['sort']['column'] ?? 'date',
                    $data['sort']['type'] ?? 'desc'
                )
                ->with('account', 'category')
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
        $this->calculateBalance($data['date']);
        $this->removeAllCache();
        return $model;
    }


    final public function massCreate(array $data): void
    {
        if (!empty($data)) {
            $existingMovements = $this->model::whereIn('movement_id', array_column($data, 'movement_id'))->pluck('movement_id')->toArray();

            $dataToInsert = array_filter($data, function ($item) use ($existingMovements) {
                return !in_array($item['movement_id'], $existingMovements);
            });

            if (!empty($dataToInsert)) {
                $this->model::insert($dataToInsert);
                $this->calculateBalance(min(array_column($dataToInsert, 'date')));
            }

            $this->removeAllCache();
        }
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = $this->coreUpdate($this->model, $id, $data);
        $this->calculateBalance($data['date']);
        $this->removeAllCache();
        return $model;
    }

    final public function destroy(int $id): int
    {
        $model = $this->coreDestroy($this->model, $id);
        $this->removeAllCache();
        return $model;
    }

    final public function list(array $data = []): Collection
    {
        $cacheKey = 'list_' . serialize($data);

        return Cache::tags(['list'])->remember($cacheKey, $this->cacheTime, function () use ($data) {
            return $this
                ->model::select(['id', 'name'])
                ->orderBy('id', 'desc')
                ->get();
        });

    }

    final public function calculateBalance(string $date): bool
    {
        $items = $this->model::where('date', '>=', $date)
            ->orderBy('date', 'asc')
            ->get();

        $previous_model = $this->model::where('date', '<', $date)->orderBy('date', 'desc')->first();
        $previous_balance = $previous_model ? $previous_model->balance : 0;

        $balances = [];

        if (count($items)) {
            foreach ($items as $item) {
                $balance = $previous_balance + $item->sum;
                $previous_balance = $balance;
                $balances[$item->id] = $balance;
            }

            $this->model::whereIn('id', array_keys($balances))
                ->update(['balance' => DB::raw('id IN (' . implode(',', array_keys($balances)) . ')')]);
        }

        return true;
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

<?php

namespace App\Repositories\Statistics;

use App\Models\Statistic\BankAccountMovement as Model;
use App\Repositories\CoreRepository;
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
        $this->calculateBalance($data['date'], $model->account_id);
        $this->removeAllCache();
        return $model;
    }


    final public function massCreate(array $data): void
    {
        if (!empty($data)) {
            $existingMovements = $this->model::whereIn(
                'movement_id', array_column($data, 'movement_id')
            )->pluck('movement_id')->toArray();

            $dataToInsert = array_filter($data, function ($item) use ($existingMovements) {
                return !in_array($item['movement_id'], $existingMovements);
            });

            if (!empty($dataToInsert)) {
                $this->model::insert($dataToInsert);

                $this->calculateBalance(
                    min(array_column($dataToInsert, 'date')),
                    $dataToInsert[0]['account_id']
                );
            }

            $this->removeAllCache();
        }
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = $this->coreUpdate($this->model, $id, $data);
        $this->calculateBalance($data['date'], $model->account_id);
        $this->removeAllCache();
        return $model;
    }

    final public function destroy(int $id): bool
    {
        $model = $this
            ->model::where('id', $id)
            ->select(['date', 'account_id'])
            ->first();

        if ($model) {
            $this->coreDestroy($this->model, $id);
            $this->calculateBalance($model->date, $model->account_id);
            $this->removeAllCache();
            return true;
        }
        return false;
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

    final public function calculateBalance(string $date, int $accountId): bool
    {
        $account = (new BankAccountsRepository())->getModelById($accountId);

        if (!$account) {
            return false;
        }

        $items = $this->model::where('date', '>=', $date)
            ->where('account_id', $accountId)
            ->orderBy('date', 'asc')
            ->get();

        $dateWithSeconds = date('Y-m-d H:i:s', strtotime($date));

        $previous_model = $this->model::where('date', '<', $dateWithSeconds)
            ->where('account_id', $accountId)
            ->orderBy('date', 'desc')
            ->first();

        $previous_balance = 0;

        if (count($items)) {
            foreach ($items as $item) {
                if ($previous_model) {
                    $balance = $previous_model->balance + $item->sum;
                } else {
//                    $previous_model->balance = $previous_model->balance - $item->sum;
//                    $balance = $previous_model->balance;
                    $firstItem = $items->first();
                    $balance = $firstItem->sum < 0
                        ? $firstItem->balance - $firstItem->sum
                        : $firstItem->balance + $firstItem->sum;
                }
                $previous_balance = $balance;
                $item->balance = $balance;
                $item->update();
            }
        }
        $account->balance = $previous_balance;
        $account->update();

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

<?php

namespace App\Repositories\Statistics;

use App\Models\Statistics\OrderStatistics as Model;
use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderStatisticsRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelByColumn(string $column, mixed $value): Model|array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|null
    {
        return $this->coreGetModelByColumn($this->model, $column, $value);
    }

    final public function getModelByStatusAndDate(string $date, int $status_id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model::whereDate('date', $date)->where('status_id', $status_id)->first();
    }

    final public function createModelClass(): mixed
    {
        return new $this->model;
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

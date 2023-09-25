<?php

namespace App\Repositories\Statistics;

use App\Models\Options\Status;
use App\Models\Statistics\OrderStatistics as Model;
use App\Repositories\CoreRepository;

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

    final public function getModelByStatusAndDate(string $date, int $status_id): \Illuminate\Database\Eloquent\Model
    {
        $model = $this->model::whereDate('date', $date)->where('status_id', $status_id)->first();

        if (!$model) {
            $status = Status::where('id', $status_id)->with('group')->first();
            $model = new $this->model;
            $model['date'] = $date;
            $model['status_id'] = $status_id;
            $model['group_slug'] = $status['group']['slug'];
            $model->save();
        }

        return $model;
    }

    final public function createModelClass(): mixed
    {
        return new $this->model;
    }

    final public function getAllWithPaginate(array $data = [])
    {
        $model = $this->model::select(['date', 'group_slug', 'count'])
            ->get()->groupBy('date');

        $perPage = $data['perPage'] ?? 15;
        $page = $data['page'] ?? 1;
        $total = $model->count();
        $results = $model->forPage($page, $perPage);
        $from = ($page - 1) * $perPage + 1;
        $to = min($page * $perPage, $total);

        return [
            'data' => $results,
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
            'from' => $from,
            'to' => $to,
        ];
    }


    private function getTableColumns(): array
    {
        return [
            'id',
            'group_slug',
            'status_id',
            'date',
            'count',
        ];
    }
}

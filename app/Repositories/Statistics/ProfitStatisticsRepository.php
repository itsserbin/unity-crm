<?php

namespace App\Repositories\Statistics;

use App\Models\Options\Status;
use App\Models\Statistics\ProfitStatistics as Model;
use App\Repositories\CoreRepository;
use App\Services\Statistics\ProfitStatisticsAggregateDataService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProfitStatisticsRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelByColumn(string $column, mixed $value): Model|array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|null
    {
        return $this->coreGetModelByColumn($this->model, $column, $value);
    }

    final public function findOrCreateAndUpdate(string $date, int $status_id, int $source_id): \Illuminate\Database\Eloquent\Model
    {
        $model = $this->model::whereDate('date', $date)
            ->where('status_id', $status_id)
            ->where('source_id', $source_id)
            ->first();


        if (!$model) {
            $status = Status::where('id', $status_id)->with('group')->first();
            $model = new $this->model;
            $model['date'] = $date;
            $model['status_id'] = $status_id;
            $model['group_slug'] = $status['group']['slug'];
            $model['source_id'] = $source_id;
            $model->save();
        }

        (new ProfitStatisticsAggregateDataService())->aggregateData($model);

        return $model;
    }

    final public function getAllWithPaginate(array $data = []): LengthAwarePaginator
    {
        $model = $this->model::select(
            'date',
            DB::raw('SUM(orders_cost) AS orders_cost'),
            DB::raw('SUM(orders_sale_price) AS orders_sale_price'),
            DB::raw('SUM(orders_clear_price) AS orders_clear_price'),
            DB::raw('SUM(orders_trade_price) AS orders_trade_price'),
            DB::raw('SUM(total_additional_sales) AS total_additional_sales'),
            DB::raw('SUM(total_additional_sales_items_sale_price) AS total_additional_sales_items_sale_price'),
            DB::raw('SUM(total_additional_sales_items_trade_price_price) AS total_additional_sales_items_trade_price_price'),
            DB::raw('SUM(total_additional_sales_items_trade_clear_price) AS total_additional_sales_items_trade_clear_price'),
            DB::raw('SUM(total_prepayment) AS total_prepayment'),
            DB::raw('SUM(total_prepayment_sum) AS total_prepayment_sum'),
            DB::raw('SUM(total_sales_of_air) AS total_sales_of_air'),
            DB::raw('SUM(total_sales_of_air_sum) AS total_sales_of_air_sum'),
        )->groupBy('date');

        if (isset($data['date_start'], $data['date_end'])) {
            $startDate = date('Y-m-d', strtotime($data['date_start']));
            $endDate = date('Y-m-d', strtotime($data['date_end']));

            $model->whereBetween('date', [$startDate, $endDate]);
        }

        return $model->paginate($data['perPage'] ?? 15);
    }

    private function getTableColumns(): array
    {
        return [
            'id',
            'group_slug',
            'status_id',
            'date',
            'orders_cost',
            'orders_sale_price',
            'orders_clear_price',
            'orders_trade_price',
            'total_additional_sales',
            'total_additional_sales_items_sale_price',
            'total_additional_sales_items_trade_price_price',
            'total_additional_sales_items_trade_clear_price',
            'total_prepayment',
            'total_prepayment_sum',
            'total_sales_of_air',
            'total_sales_of_air_sum',
        ];
    }
}

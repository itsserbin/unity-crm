<?php

namespace App\Services\Statistics;

use App\Repositories\CRM\OrdersRepository;
use App\Repositories\Statistics\ProfitStatisticsRepository;
use Illuminate\Database\Eloquent\Model;

class ProfitStatisticsAggregateDataService
{
    private mixed $ordersRepository;

    public function __construct()
    {
        $this->ordersRepository = app(OrdersRepository::class);
    }

    final public function aggregateData(Model $model): void
    {
        $model['orders_cost'] = $this->ordersRepository->aggregateRelatedColumnByConditions(
            'costs',
            'sum',
            [
                ['source_id', '=', $model['status_id']],
                ['status_id', '=', $model['source_id']],
                ['created_at', '=', $model['date']],
            ],
            [],
            'sum'
        );

        $model['orders_sale_price'] = $this->ordersRepository->aggregateColumnByConditions(
            'total_price',
            [
                ['source_id', '=', $model['status_id']],
                ['status_id', '=', $model['source_id']],
                ['created_at', '=', $model['date']],
            ]
        );

        $model['orders_clear_price'] = $this->ordersRepository->aggregateColumnByConditions(
            'clear_total_price',
            [
                ['source_id', '=', $model['status_id']],
                ['status_id', '=', $model['source_id']],
                ['created_at', '=', $model['date']],
            ]
        );

        $model['orders_trade_price'] = $this->ordersRepository->aggregateColumnByConditions(
            'clear_total_price',
            [
                ['source_id', '=', $model['status_id']],
                ['status_id', '=', $model['source_id']],
                ['created_at', '=', $model['date']],
            ]
        );

        $model['total_prepayment'] = $this->ordersRepository->aggregateRelatedColumnByConditions(
            'invoices', 'sum',
            [
                ['source_id', '=', $model['status_id']],
                ['status_id', '=', $model['source_id']],
                ['created_at', '=', $model['date']],
            ],
            [
                ['status', '=', 1]
            ]
        );

        $model['total_prepayment_sum'] = $this->ordersRepository->aggregateRelatedColumnByConditions(
            'invoices',
            'sum',
            [
                ['source_id', '=', $model['status_id']],
                ['status_id', '=', $model['source_id']],
                ['created_at', '=', $model['date']],
            ],
            [
                ['status', '=', 1]
            ],
            'sum'
        );

        $model->update();
    }
}

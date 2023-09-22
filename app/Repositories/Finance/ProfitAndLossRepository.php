<?php

namespace App\Repositories\Finance;

use App\Models\Finance\ProfitAndLoss as Model;
use App\Repositories\CoreRepository;
use App\Repositories\CRM\OrdersRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfitAndLossRepository extends CoreRepository
{
    private mixed $bankAccountMovementsRepository;
    private mixed $ordersRepository;

    public function __construct()
    {
        parent::__construct();
        $this->bankAccountMovementsRepository = app(BankAccountMovementsRepository::class);
        $this->ordersRepository = app(OrdersRepository::class);
    }

    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getByMonth(string $month): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model::where('month', $month)->first();
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $model = $this->model::select([
            'id',
            'month',
            'purchase_cost',
            'business_profitability',
            'total_revenues',
            'costs',
            'net_profit',
            'investments',
            'returned_investments',
            'dividends',
        ]);

//        if (isset($data['date_start'], $data['date_end'])) {
//            $model->whereBetween('date', [$data['date_start'], $data['date_end']]);
//        }
//
        if (isset($data['sort'], $data['param'])) {
            $model->orderBy($data['sort'], $data['param']);
        } else {
            $model->orderBy('month', 'desc');
        }

        return $model->orderBy('month', 'desc')->paginate($data['perPage'] ?? 15);
    }

    final public function create(string $month): bool
    {
        $model = new $this->model;
        $model->month = $month;

        $model = $this->fillModel($model, $month);
        $model->save();

        return true;
    }

    final public function update(string $month): bool
    {
        $model = $this->getByMonth($month);

        if (!$model) {
            return $this->create($month);
        }

        $model = $this->fillModel($model, $month);
        $model->update();

        return true;
    }

    final public function fillModel(\Illuminate\Database\Eloquent\Model $model, string $month): \Illuminate\Database\Eloquent\Model
    {
        $items = $this->bankAccountMovementsRepository->getModelByMonth($month);
        $model->purchase_cost = $this->ordersRepository->getPriceByMonth($month, 'trade_price') ?: 0;
        $model->total_revenues = $this->ordersRepository->getPriceByMonth($month) ?: 0;

        $isNotEmptyItems = $items->isNotEmpty();

        $model->costs = $isNotEmptyItems
            ? $items->filter(function ($item) {
                return ($item->category && !collect(['dividends', 'investments', 'return-investment'])->contains($item->category->slug) && $item->sum < 0)
                    || (!$item->category && $item->sum < 0);
            })->sum('sum')
            : 0;

        $model->net_profit = $model->total_revenues + $model->costs - $model->purchase_cost;
        $model->business_profitability = ($model->total_revenues ? ($model->net_profit / $model->total_revenues) * 100 : 0) ?? 0;
        $model->investments = $isNotEmptyItems ? $items->where('category.slug', 'investments')->where('sum', '>', 0)->sum('sum') : 0;
        $model->returned_investments = $isNotEmptyItems ? $items->where('category.slug', 'return-investment')->where('sum', '<', 0)->sum('sum') : 0;
        $model->dividends = $isNotEmptyItems ? $items->where('category.slug', 'dividends')->where('sum', '<', 0)->sum('sum') : 0;

        return $model;
    }

}

<?php

namespace App\Repositories\CRM;

use App\Jobs\UpdateTrackingCode;
use App\Models\CRM\Order as Model;
use App\Repositories\CoreRepository;
use App\Repositories\Options\StatusesRepository;
use App\Repositories\Statistics\OrderStatisticsRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use JsonException;

class OrdersRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelById(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        $relations = [
            'source:id,title',
            'status' => function ($q) {
                $q->select(['id', 'title', 'group_slug']);
                $q->with('group:id,slug,hex');
            },
            'client' => function ($q) {
                $q->select('id', 'full_name', 'phones');
                $q->with('addresses');
            },
            'items' => function ($q) {
                $q->select([
                    'id',
                    'title',
                    'order_id',
                    'product_price',
                    'sale_price',
                    'trade_price',
                    'discount',
                    'count',
                    'preview_id',
                    'additional_sale',
                    'product_id',
                ]);
                $q->with('preview:id,alt,data');
            },
            'manager:id,name,phone,email',
            'costs:title,sum,comment,order_id',
            'deliveryService:id,title',
            'invoices:id,order_id,date,payment_type,sum,comment,status',
            'trackingCodes:id,order_id,code,data,delivery_service_id'
        ];

        return $this->model::with($relations)
            ->where('id', $id)
            ->first();
    }

    final public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = new $this->model;
        $model->fill($data);
        $model->save();

        $this->updateStatusesOrderCount($model['created_at'], null, $data['status_id']);
        $this->updateInvoices($model, $data['invoices'] ?? []);
        $this->updateTrackingCodes($model, $data['tracking_codes'] ?? []);
        $this->updateCosts($model, $data['costs'] ?? []);
        $this->updateItems($model, $data['items'] ?? []);
        if ($model->client_id) {
            $clientsRepository = new ClientsRepository();
            $clientsRepository->updateChecks($model->client_id);
            $clientsRepository->updateClientLastOrder($model->client_id, $model->created_at);
        }
        return $model;
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = $this->model::where('id', $id)->with('items')->first();
        $currentStatus = $model['status_id'];
        $model->fill($data);
        $model->update();

        if ($currentStatus !== $data['status_id']) {
            $this->updateStatusesOrderCount($model['created_at'], $currentStatus, $data['status_id']);
        }
        $this->updateInvoices($model, $data['invoices'] ?? []);
        $this->updateTrackingCodes($model, $data['tracking_codes'] ?? []);
        $this->updateCosts($model, $data['costs'] ?? []);
        $this->updateItems($model, $data['items'] ?? []);

        if ($model->client_id) {
            (new ClientsRepository())->updateChecks($model->client_id);
        }

        return $model;
    }

    final public function updateTrackingCode(string $code, int $order_id): PendingDispatch
    {
        return UpdateTrackingCode::dispatch($code, $order_id);
    }

    private function updateItems($model, $data)
    {
        if (isset($data)) {
            if (count($data)) {
                $model = $this->calculateSum($model, $data);
                $model->items()->delete();
                $model->items()->createMany($data);
            }
        }
        return $model;
    }

    private function updateCosts($model, $data)
    {
        if (isset($data)) {
            if (count($data)) {
                $model->costs()->delete();
                $model->costs()->createMany($data);
            }
        }
        return $model;
    }

    private function updateInvoices($model, $data)
    {
        if (isset($data)) {
            if (count($data)) {
                $model->invoices()->delete();
                $model->invoices()->createMany($data);
            }
        }
        return $model;
    }

    /**
     * @throws JsonException
     */
    private function updateTrackingCodes(\Illuminate\Database\Eloquent\Model $model, array $data = []): void
    {
        if (isset($data)) {
            if (count($data)) {
                $model->trackingCodes()->delete();
                $model->trackingCodes()->createMany($data);
                foreach ($data as $value) {
                    $this->updateTrackingCode($value['code'], $model->id);
                }
            }
        }
    }


    private function calculateSum(\Illuminate\Database\Eloquent\Model $model, array $items): \Illuminate\Database\Eloquent\Model
    {
        $costs = array_reduce($model->costs->toArray(), function ($carry, $item) {
            return $carry + $item['sum'];
        }, 0);

        $model->total_price = array_reduce($items, function ($carry, $item) {
                if (isset($item['sale_price'])) {
                    return $carry + $item['sale_price'];
                }
                return $carry;
            }, 0) - $model->discount;

        $model->trade_price = array_reduce($items, function ($carry, $item) {
            if (isset($item['trade_price'])) {
                return $carry + $item['trade_price'];
            }
            return $carry;
        }, 0);

        $model->clear_total_price = $model->total_price - $model->trade_price - $costs;;
        $model->update();

        return $model;
    }

    private function updateStatusesOrderCount(string $created_at, int $old = null, int $new = null): int
    {
        if (!$old && !$new) {
            return 0;
        }

        $statusesRepository = new StatusesRepository();
        $orderStatisticsRepository = new OrderStatisticsRepository();

        if ($old) {
            $statusModelOld = $statusesRepository->getModelById($old);
            if ($statusModelOld >= 1) {
                $statusModelOld->decrement('orders_count');
            }

            $statisticModelOld = $orderStatisticsRepository->getModelByStatusAndDate($created_at, $old);

            if ($statisticModelOld >= 1) {
                $statisticModelOld->decrement('count');
            }
        }

        if ($new) {
            $statusesRepository
                ->getModelById($new)
                ->increment('orders_count');

            $orderStatisticsRepository
                ->getModelByStatusAndDate($created_at, $new)
                ->increment('count');
        }

        return 1;
    }

    final public function destroy(int $id): int
    {
        $model = $this->model::where('id', $id)->first();
        if ($model) {
            $this->updateStatusesOrderCount($model['created_at'], $model['status_id']);
            return $model->delete();
        }
        return 0;
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $model = $this->model::select($this->getTableColumns());

        if (isset($data['statuses'])) {
            $model->whereIn('status_id', $data['statuses']);
        }

        return $this->returnTableData($model, $data);
    }

    final public function search(string $query, array $data): LengthAwarePaginator
    {
        $query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');

        $model = $this->model::select($this->getTableColumns())
            ->where('id', 'LIKE', "%$query%")
            ->orWhereHas('client', function ($q) use ($query) {
                $q->where('full_name', 'LIKE', "%$query%");
                $q->orWhereRaw("phones->>'$[*].number' LIKE ?", ["%$query%"]);
                $q->orWhereRaw("emails->>'$[*].address' LIKE ?", ["%$query%"]);
            })
            ->orWhere('tracking_code', 'LIKE', "%$query%")
            ->orWhere('comment', 'LIKE', "%$query%");

        return $this->returnTableData($model, $data);
    }

    private function returnTableData(Builder $model, array $data): LengthAwarePaginator
    {
        return $model
            ->orderBy(
                $data['sort']['column'] ?? 'id',
                $data['sort']['type'] ?? 'desc'
            )
            ->with($this->getRelations())
            ->paginate($this->getPagination($data));
    }

    private function getTableColumns(): array
    {
        return [
            'id',
            'source_id',
            'status_id',
            'client_id',
            'manager_id',
            'delivery_service_id',
            'delivery_address',
            'client_comment',
            'manager_comment',
            'total_price',
            'trade_price',
            'clear_total_price',
            'discount',
        ];
    }

    private function getRelations(): array
    {
        return [
            'source:id,title',
            'status' => function ($q) {
                $q->select(['id', 'title', 'group_slug']);
                $q->with('group', function ($q) {
                    $q->select('slug', 'hex');
                });
            },
            'client:id,full_name,phones',
            'manager:id,name',
            'deliveryService:id,title',
            'items:id,title,order_id,sale_price',
            'trackingCodes:id,order_id,code,log'
        ];
    }

    private function getPagination(array $data): int
    {
        return $data['perPage'] ?? 15;
    }

    final public function getPriceByMonth(string $month, string $column = 'total_price'): float
    {
        $startOfMonth = date('Y-m-01', strtotime($month));
        $endOfMonth = date('Y-m-t', strtotime($month));

        $orders = $this->model::whereHas('status', function ($q) {
            $q->where('group_slug', 'new');
        })
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        return $orders->sum($column);
    }

    final public function countOrderByDate(string $date, int $status_id = null)
    {
        $model = $this->model::whereDate('created_at', $date);

        if ($status_id) {
            $model->where('status_id', $status_id);
        }

        return $model->count();
    }
}

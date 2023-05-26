<?php

namespace App\Repositories;

use App\Models\Order as Model;
use Illuminate\Pagination\LengthAwarePaginator;

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
        return $this->model::where('id', $id)
            ->with([
                'source:id,title',
                'status' => function ($q) {
                    $q->select(['id', 'title', 'group_slug']);
                    $q->with('group', function ($q) {
                        $q->select('slug', 'hex');
                    });
                },
                'client' => function ($q) {
                    $q->select('id', 'full_name', 'phones');
                    $q->with('addresses');
                },
                'manager:id,name',
                'deliveryService:id,title',
                'items:id,title,order_id,product_price,sale_price,trade_price,discount,count,image,additional_sale'
            ])
            ->first();
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {
        $columns = [
            'id',
            'source_id',
            'status_id',
            'client_id',
            'manager_id',
            'delivery_service_id',
            'delivery_address',
            'tracking_code',
            'comment',
            'total_price',
            'trade_price',
            'clear_total_price',
            'discount',
        ];

        $model = $this->model::select($columns);

        if (isset($data['statuses'])) {
            $model->whereIn('status_id', $data['statuses']);
        }

        return $model
            ->orderBy(
                $data['sort']['column'] ?? 'id',
                $data['sort']['type'] ?? 'desc'
            )
            ->with([
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
                'items:id,title,order_id'
            ])
            ->paginate($data['perPage'] ?? 15);
    }

    final public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = new $this->model;
        $this->updateStatusesOrderCount(null, $data['status_id']);
        $model = $this->fillData($model, $data);
        $model->save();

        if (count($data['items'])) {
            $model = $this->calculateSum($model, $data['items']);
            $model->items()->createMany($data['items']);
        }
        return $model;
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = $this->model::where('id', $id)->with('items')->first();
        if ($model->status_id !== $data['status_id']) {
            $this->updateStatusesOrderCount($model->status_id, $data['status_id']);
        }
        $model = $this->fillData($model, $data);

        if (count($data['items'])) {
            $model = $this->calculateSum($model, $data['items']);
            $model->items()->delete();
            $model->items()->createMany($data['items']);
        }
        $model->update();
        return $model;
    }


    private function calculateSum(\Illuminate\Database\Eloquent\Model $model, array $items): \Illuminate\Database\Eloquent\Model
    {
        $model->total_price = array_reduce($items, function ($carry, $item) {
                return $carry + $item['sale_price'];
            }, 0) - $model->discount;

        $model->trade_price = array_reduce($items, function ($carry, $item) {
            return $carry + $item['trade_price'];
        }, 0);

        $model->clear_total_price = $model->total_price - $model->trade_price;

        return $model;
    }

    private function updateStatusesOrderCount(int $old = null, int $new = null): int
    {
        if ($old || $new) {
            $statusesRepository = new StatusesRepository();
        }

        if ($old) {
            $prevStatus = $statusesRepository->getModelById($old);
            --$prevStatus->orders_count;
            $prevStatus->update();
        }

        if ($new) {
            $currentStatus = $statusesRepository->getModelById($new);
            ++$currentStatus->orders_count;
            $currentStatus->update();
        }

        return 1;
    }

    private function fillData(\Illuminate\Database\Eloquent\Model $model, array $data): \Illuminate\Database\Eloquent\Model
    {

        $model->source_id = $data['source_id'];
        $model->status_id = $data['status_id'];

        if (isset($data['client_id'])) {
            $model->client_id = $data['client_id'];
        }
        if (isset($data['manager_id'])) {
            $model->manager_id = $data['manager_id'];
        }
        if (isset($data['delivery_service_id'])) {
            $model->delivery_service_id = $data['delivery_service_id'];
        }
        if (isset($data['delivery_address'])) {
            $model->delivery_address = $data['delivery_address'];
        }
        if (isset($data['tracking_code'])) {
            $model->tracking_code = $data['tracking_code'];
        }
        if (isset($data['comment'])) {
            $model->comment = $data['comment'];
        }
        if (isset($data['discount'])) {
            $model->discount = $data['discount'];
        }
        return $model;
    }

    final public function destroy(int $id): int
    {
        return $this->model::where('id', $id)->delete();
    }

    final public function search(string $query, array $data): LengthAwarePaginator
    {
        $columns = [
            'id',
            'full_name',
            'emails',
            'phones',
            'emails',
            'number_of_orders',
            'number_of_success_orders',
            'average_check',
            'general_check',
            'last_order_created_at',
            'created_at'
        ];

        return $this->model::select($columns)
            ->where('id', 'LIKE', "%$query%")
            ->orWhere('full_name', 'LIKE', "%$query%")
            ->orWhereRaw("phones->>'$[*].number' LIKE ?", ["%$query%"])
            ->orWhereRaw("phones->>'$[*].number' LIKE ?", ["%$query%"])
            ->orWhereRaw("phones->>'$[*].number' LIKE ?", ["%$query%"])
            ->orWhereJsonContains('emails', $query)
            ->paginate($data['perPage'] ?? 15);

    }
}

<?php

namespace App\Repositories\CRM;

use App\Models\CRM\Client as Model;
use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ClientsRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass(): string
    {
        return Model::class;
    }

    public function findByColumn(string $column, string $value)
    {
        if ($column === 'phones') {
            return DB::table('clients')
                ->whereJsonContains('phones', $value)
                ->first();
        } else {
            return $this->model::where($column, $value)->first();
        }
    }

    final public function getModelById(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model::where('id', $id)
            ->with('addresses', 'orders.status.group')
            ->first();
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
    {

        $model = $this->model::select($this->getTableColumns());

        return $model
            ->orderBy(
                $data['sort']['column'] ?? 'id',
                $data['sort']['type'] ?? 'desc'
            )
            ->paginate($this->getPagination($data));
    }

    final public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = new $this->model;
        $model = $this->fillData($model, $data);
        $model->save();

        if (isset($data['addresses'])) {
            $model->addresses()->createMany($data['addresses']);
        }

        return $model;
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        $model = $this->getModelById($id);
        $model = $this->fillData($model, $data);
        $model->save();

        if (isset($data['addresses'])) {
            $model->addresses()->delete();
            $model->addresses()->createMany($data['addresses']);
        }

        return $model;
    }

    private function fillData(\Illuminate\Database\Eloquent\Model $model, array $data): \Illuminate\Database\Eloquent\Model
    {
        $fillableData = $data;

        if (isset($data['phones'])) {
            $phones = [];
            foreach ($data['phones'] as $phone) {
                $phones[] = preg_replace('/[^0-9]/', '', $phone);
            }
            $fillableData['phones'] = $phones;
        }

        $model->fill($fillableData);

        return $model;
    }

    final public function destroy(int $id): int
    {
        return $this->model::where('id', $id)->delete();
    }

    final public function search(string $query, array $data): LengthAwarePaginator
    {
        return $this->model::select($this->getTableColumns())
            ->where('id', 'LIKE', "%$query%")
            ->orWhere('full_name', 'LIKE', "%$query%")
            ->orWhereRaw("jsonb_array_elements_text(phones)-> LIKE ?", ["%$query%"])
            ->orWhereJsonContains('emails', $query)
            ->paginate($this->getPagination($data));
    }

    final public function list(): Collection
    {
        return $this->model::select(['id', 'full_name', 'phones', 'emails'])->get();
    }

    final public function createClientAddress(int $id, array $data): ?\Illuminate\Database\Eloquent\Model
    {
        $model = $this->model::where('id', $id)->with('addresses')->first();
        if ($model) {
            $model->addresses()->create($data);
        }
        return $model;
    }

    final public function updateChecks(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        $model = $this->model::where('id', $id)
            ->with(['orders' => function ($q) {
                $q->select(['id', 'client_id', 'total_price', 'status_id']);
                $q->with('status.group');
            }])
            ->first();

        if (!$model || empty($model->orders)) {
            return null;
        }

        $orders = collect($model->orders);
        $count = $orders->count();
        $sum = $orders->sum('total_price');

        $successfulOrders = $orders->filter(function ($order) {
            return $order->status->group->slug === 'done';
        });

        $successCount = $successfulOrders->count();
        $successSum = $successfulOrders->sum('total_price');

        $model->fill([
            'number_of_orders' => $count,
            'number_of_success_orders' => $successCount,
            'general_check' => $sum,
            'success_general_check' => $successSum,
            'average_check' => $count ? $sum / $count : 0,
            'success_average_check' => $successCount ? $successSum / $successCount : 0,
        ])->save();

        return $model;
    }

    private function getTableColumns(): array
    {
        return [
            'id',
            'full_name',
            'emails',
            'phones',
            'emails',
            'number_of_orders',
            'number_of_success_orders',
            'success_average_check',
            'average_check',
            'success_general_check',
            'general_check',
            'last_order_created_at',
            'success_last_order_created_at',
            'created_at'
        ];
    }

    private function getPagination(array $data): int
    {
        return $data['perPage'] ?? 15;
    }

    final public function updateClientLastOrder(int $id, string $date): ?int
    {
        return $this->model::where('id', $id)->update(['last_order_created_at' => $date]);
    }
}

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
            ->with('addresses', 'orders')
            ->first();
    }

    final public function getAllWithPaginate(array $data): LengthAwarePaginator
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

        $model = $this->model::select($columns);

        return $model
            ->orderBy(
                $data['sort']['column'] ?? 'id',
                $data['sort']['type'] ?? 'desc'
            )
            ->paginate($data['perPage'] ?? 15);
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

    private function fillData(\Illuminate\Database\Eloquent\Model $model, array $data): \Illuminate\Database\Eloquent\Model
    {
        foreach ($data['phones'] as &$phone) {
            $phone['number'] = preg_replace('/[^0-9]/', '', $phone['number']);
        }
        $model->full_name = $data['full_name'];
        $model->comment = $data['comment'];
        $model->phones = $data['phones'];
        $model->emails = $data['emails'];
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

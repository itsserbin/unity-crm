<?php

namespace App\Repositories\CRM;

use App\Models\CRM\TrackingCode as Model;
use App\Repositories\CoreRepository;

class TrackingCodesRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelById(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model::where('id', $id)->with('deliveryServices')->first();
    }

    final public function getModelByCodeAndOrderId(string $code, int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model::where(function ($q) use ($code, $id) {
            $q->where('code', $code);
            $q->where('order_id', $id);
        })
            ->with('deliveryServices:id,type,api_key,configuration,status')
            ->first();
    }

    final public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->coreCreate($this->model, $data);
    }

    final public function update(int $id, array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->coreUpdate($this->model, $id, $data);
    }

    final public function destroy(int $id): int
    {
        return $this->coreDestroy($this->model, $id);
    }
}

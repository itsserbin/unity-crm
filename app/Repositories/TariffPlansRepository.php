<?php

namespace App\Repositories;

use App\Models\TariffPlan as Model;

class TariffPlansRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return Model::class;
    }

    final public function getModelByColumn(string $column, mixed $value): Model|array|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|null
    {
        return $this->coreGetModelByColumn($this->model, $column, $value);
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

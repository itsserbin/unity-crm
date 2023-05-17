<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class CoreRepository
{
    protected mixed $model;

    /**
     * CoreRepository constructor
     */
    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * @return mixed
     */
    abstract protected function getModelClass();

    final public function coreCreate(Model $model, array $data): Model
    {
        $model = new $model;
        $model->fill($data);
        $model->save();
        return $model;
    }

    final public function coreUpdate(Model $model, int $id, array $data): Model
    {
        $eModel = $model::where('id', $id)->first();
        $eModel->fill($data);
        $eModel->update();
        return $eModel;
    }

    final public function coreDestroy(Model $model, int $id): int
    {
        return $model::where('id', $id)->delete();
    }

    final public function coreFind(Model $model, int $id): Model
    {
        return $model::where('id', $id)->first();
    }
}

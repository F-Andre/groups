<?php

namespace App\Repositories;

abstract class DataRepository
{
    protected $model;

    public function getId()
    {
        return $this->model->pluck('id');
    }

    public function store($inputs)
    {
        $this->model->create($inputs);
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function dropForeign($id)
    {
        $this->model->dropForeign($id);
    }

    public function destroy($id)
    {
        $this->model->findOrFail($id)->delete();

    }
}

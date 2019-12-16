<?php

namespace App\Repositories;

abstract class DataRepository
{
    protected $model;

    public function getCollection()
    {
        return $this->model->all();
    }

    public function getCollectionOrdered()
    {
        return $this->model->orderBy('id', 'desc')->get();
    }

    public function getId()
    {
        return $this->model->firstOrFail();
    }

    public function store($inputs)
    {
        $this->model->create($inputs);
    }

    public function getByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getIncludesIds(Array $ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    public function getIncludesIdsByName(Array $ids)
    {
        return $this->model->whereIn('id', $ids)->orderBy('name')->get();
    }

    public function getNotIncludesIds(Array $ids)
    {
        return $this->model->whereNotIn('id', $ids)->get();
    }

    public function getNotIncludesIdsByName(Array $ids)
    {
        return $this->model->whereNotIn('id', $ids)->orderBy('name')->get();
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

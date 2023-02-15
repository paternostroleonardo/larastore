<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $model;
    private $relations;
    private $status;

    public function __construct(Model $model, array $relations = [],  string $status = null)
    {
        $this->model = $model;
        $this->relations = $relations;
        $this->status = $status;
    }

    public function all()
    {
        $query = $this->model;

        if(!empty($this->relations)) {
            $query = $query->with($this->relations);
        }

        return $query->get();
    }

    public function get(int $id)
    {
        return $this->model->find($id);
    }

    public function save(Model $model)
    {
        $model->save();

        return $model;
    }

    public function update(Model $model)
    {
        $model->update();

        return $model;
    }

    public function status(Model $model, $status)
    {
        $query = $model->where('status', $status)->get();

        return $query;
    }

    public function delete(Model $model)
    {
        $model->delete();

        return $model;
    }
}
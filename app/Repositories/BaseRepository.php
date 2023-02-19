<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class BaseRepository
{
    protected $model;
    private $relations;

    public function __construct(Model $model, array $relations = [])
    {
        $this->model = $model;
        $this->relations = $relations;
    }

    public function all(): Paginator
    {
        $query = $this->model;

        if(!empty($this->relations)) {
            $query = $query->with($this->relations);
        }

        return $query->latest()->simplePaginate(10);
    }

    public function get(int $id): Model
    {
        return $this->model->find($id);
    }

    public function save(Model $model): Model
    {
        $model->save();

        return $model;
    }

    public function update(Model $model): Model
    {
        $model->update();

        return $model;
    }

    public function status(string $status): Collection
    {
        $query = $this->model->where('status', $status)->get();

        return $query;
    }

    public function delete(Model $model): Model
    {
        $model->delete();

        return $model;
    }
}
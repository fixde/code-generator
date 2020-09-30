<?php

namespace Fixde\CodeGenerator\Repositories;

use DB;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param App $app
     *
     * @return void
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->setModel();
    }

    abstract public function getModel();

    public function setModel()
    {
        $model = $this->app->make($this->getModel());
        if (! $model instanceof Model) {
            throw new Exception("Class {$this->getModel()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Get list model.
     *
     * @param array $data
     * @return array $columns
     */
    public function list($data = [], $columns = ['*'])
    {
        $data = collect($data);

        $query = $this->model->select($columns);

        if ($data->count() && method_exists($this, 'search')) {
            foreach ($data as $column => $value) {
                $query = $this->search($query, $column, $value);
            }
        }

        $orderBy =
            $data->has('orderBy') && in_array($data['orderBy'], $this->model->getAttributes())
            ? $data['orderBy']
            : $this->model->getKeyName();

        $query = $query->orderBy($orderBy, $data->has('sort') ? 'asc' : 'desc');

        return $data->has('limit') ? $query->paginate($data['limit']) : $query->get();
    }

    /**
     * Find model by id.
     *
     * @param number $id
     * @param array $columns
     * @return Model
     */
    public function find($id, $columns = ['*'])
    {
        $entity = $model->findOrFail($id, $columns);

        return $entity;
    }

    /**
     * Create entity.
     *
     * @param array $data
     * @return Model
     */
    public function create($data = [])
    {
        $fillable = $this->model->getFillable();
        if ($fillable) {
            $data = collect($data)->only($fillable)->toArray();
        }

        return $this->model->create($data);
    }

    /**
     * Update entity.
     *
     * @param Model $entity
     * @param array $data
     * @return Model
     */
    public function update(Model $entity, $data = [])
    {
        $fillable = $this->model->getFillable();
        if ($fillable) {
            $data = collect($data)->only($fillable)->toArray();
        }

        $entity->update($data);

        return $entity;
    }

    /**
     * Delete entity.
     *
     * @param Model $entity
     * @return void
     */
    public function delete(Model $entity)
    {
        $entity->delete();
    }
}

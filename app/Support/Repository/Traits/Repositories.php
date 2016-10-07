<?php

namespace App\Support\Repository\Traits;

trait Repositories {

    /**
     * Return all model records.
     *
     * @return Collection
     */
    public function all()
    {
        return $this->getModel()->all();
    }

    /**
     * Return all model records.
     *
     * @param   array      $with The related data to include.
     *
     * @return Collection
     */
    public function allWith( $with = [] )
    {
        return $this->getModel()->with( $with )->get();
    }

    /**
     * Fetch model record by its ID number.
     *
     * @param $id
     *
     * @return mixed
     */
    public function byId( $id )
    {
        return $this->getModel()->find( $id );
    }

    /**
     * Create a new model record.
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create( $attributes = [] )
    {
        return $this->getModel()->create( $attributes );
    }

    /**
     * Delete a model record.
     *
     * @param mixed $model
     *
     * @return mixed
     */
    public function delete( $model )
    {
        return $model->delete();
    }

    /**
     * Return the model object.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Lazy-load relationship data.
     *
     * @param       $model
     * @param array $relationships
     *
     * @return mixed
     */
    public function load( $model, $relationships = [] )
    {
        return $model->load( $relationships );
    }

    /**
     * Create a new model instance.
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function newInstance( $attributes = [] )
    {
        return $this->getModel()->newInstance( $attributes );
    }

    /**
     * Return all model records in paginated form.
     *
     * @param int $per_page
     *
     * @return mixed
     */
    public function paginate( $per_page = 15 )
    {
        return $this->getModel()->paginate( $per_page );
    }

    /**
     * Update a model record with new data.
     *
     * @param mixed $model
     * @param array $attributes
     *
     * @return mixed
     */
    public function update( $model, $attributes = [] )
    {
        return $model->fill( $attributes )->save();
    }
}
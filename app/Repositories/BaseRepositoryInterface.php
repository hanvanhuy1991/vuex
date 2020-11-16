<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface BaseRepositoryInterface
 *
 * @package App\Repositories
 */
interface BaseRepositoryInterface
{
    /**
     * Find all records that match a given conditions
     *
     * @param  array  $conditions
     *
     * @return Model[]
     */
    public function find(array $conditions = []);

    /**
     * Find a first record that match a given conditions for its value
     *
     * @param  string  $condition
     * @param  string  $value
     *
     * @return Model
     */
    public function first(string $condition, string $value);

    /**
     * Find all records that match a given condition for its Value
     *
     * @param  string  $condition
     * @param  string  $value
     *
     * @return Model
     */
    public function findByConditionLike(string $condition, string $value);

    /**
     * Find a specific record that matches a given conditions
     *
     * @param  array  $conditions
     *
     * @return Model
     */
    public function findOne(array $conditions);

    /**
     * Find all records that match a given conditions for its Value
     *
     * @param  string  $condition
     * @param  int  $value
     *
     * @return Model
     */
    public function findByCondition(string $condition, int $value);

    /**
     * Find a specific record by its ID
     *
     * @param  int  $id
     *
     * @return Model
     */
    public function findById(int $id);

    /**
     * Find a model by its primary key
     *
     * @param  int  $id
     *
     * @return Model
     */
    public function findOrFail(int $id);

    /**
     * Find a model by its primary key
     *
     * @param  int  $id
     * @param string relation
     * @param string condition
     * @param string value
     *
     * @return Model
     */
    public function findOrFailWithRelation(int $id, string $relation, string $condition, string $value);

    /**
     * Find a specific first record that match a given conditions for its Value
     *
     * @param  string  $condition
     * @param  string  $value
     *
     * @return Model
     */
    public function firstOrFail(string $condition, string $value);

    /**
     * Find a specific first record that match a given conditions for its Value
     *
     * @param  string  $condition
     * @param  string  $value
     *
     * @return bool
     */
    public function exists(string $condition, string $value);

    /**
     * Create a record
     *
     * @param  array  $attributes
     *
     * @return Model
     */
    public function create(array $attributes);

    /**
     * Create a new entry in the database if there is not match found.
     *
     * @param  array  $attributes
     * @param  array  $values
     *
     * @return Model|static
     */
    public function firstOrCreate(array $attributes, array $values = []);

    /**
     * Update an existing model or create a new model if none exists
     *
     * @param  array  $attributes
     * @param  array  $values
     *
     * @return Model
     */
    public function updateOrCreate(array $attributes, array $values = []);

    /**
     * Update a record
     *
     * @param  Model  $model
     * @param  array  $attributes
     *
     * @return bool
     */
    public function update(Model $model, array $attributes = []);

    /**
     * Save a given record
     *
     * @param  Model  $model
     *
     * @return boolean
     */
    public function save(Model $model);

    /**
     * Delete the record from the database.
     *
     * @param  Model  $model
     *
     * @return bool
     *
     * @throws Exception
     */
    public function delete(Model $model);

    /**
     * Delete the record from the database.
     *
     * @param  int  $id
     *
     * @return int
     *
     * @throws Exception
     */
    public function destroy($id);

    /**
     * Update point model.
     *
     * @param [model] $model
     * @param [integer] $point
     */
    public function updatePoint($model, $point);
}

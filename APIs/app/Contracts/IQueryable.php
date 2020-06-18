<?php

namespace App\Contracts;

interface IQueryable
{

    /**
     * Get the entity data by pass a key.
     *
     * @param  mixed  $key
     * @return mixed
     */
    function findEntity($key);

    /**
     * Get all entities.
     *
     * @param  mixed  $key
     * @return array
     */
    function allEntities();

    /**
     * Get entities by where.
     *
     * @return array
     */
    function entitiesWhere($key, $equalsTo);

    /**
     * Get entities by whereHas.
     *
     * @param  mixed  $key
     * @param  callable  $whereHasFunc
     * @return array
     */
    function entitiesWhereHas($key, callable $whereHasFunc);
}

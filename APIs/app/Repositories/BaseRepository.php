<?php

namespace App\Repositories;

use App\Contracts\{ILogger, IQueryable};
use App\Exceptions\DatabaseException;
use App\Providers\RecordableProvider;
use Exception;

abstract class BaseRepository extends RecordableProvider
{
    protected IQueryable $queryable;

    public function __construct(ILogger $logger, IQueryable $queryable)
    {
        parent::__construct($logger);
        $this->queryable = $queryable;
    }

    public function all()
    {
        try {
            $data = $this->queryAll();
        } catch (Exception $ex) {
            throw new DatabaseException($ex);
        }

        if (!$data) {
            return [];
        }
        $result = [];
        foreach ($data as $entity) {
            $resultTemp = $this->toResultEntity($entity);
            $result[] = $resultTemp;
        }
        return $result;
    }

    public function one($id)
    {
        try {
            $data = $this->queryOne($id);
        } catch (Exception $ex) {
            throw new DatabaseException($ex);
        }

        if (!$data) {
            return null;
        }

        $result = $this->toResultEntity($data);
        return $result;
    }

    protected function byWhere($key, $equalsTo)
    {
        try {
            $data = $this->queryByWhere($key, $equalsTo);
        } catch (Exception $ex) {
            throw new DatabaseException($ex);
        }

        if (!$data) {
            return [];
        }
        $result = [];
        foreach ($data as $entity) {
            $resultTemp = $this->toResultEntity($entity);
            $result[] = $resultTemp;
        }
        return $result;
    }

    protected function byWhereHas($key, callable $whereHasFunc)
    {
        try {
            $data = $this->queryByWhereHas($key, $whereHasFunc);
        } catch (Exception $ex) {
            throw new DatabaseException($ex);
        }

        if (!$data) {
            return [];
        }
        $result = [];
        foreach ($data as $entity) {
            $resultTemp = $this->toResultEntity($entity);
            $result[] = $resultTemp;
        }
        return $result;
    }

    /**
     * Override in the subclass if needed.
     */
    protected function queryAll()
    {
        return $this->queryable->allEntities();
    }

    /**
     * Override in the subclass if needed.
     */
    protected function queryOne($id)
    {
        return $this->queryable->findEntity($id);
    }

    /**
     * Override in the subclass if needed.
     */
    protected function queryByWhere($key, $equalsTo)
    {
        return $this->queryable->entitiesWhere($key, $equalsTo);
    }

    /**
     * Override in the subclass if needed.
     */
    protected function queryByWhereHas($key, callable $whereHasFunc)
    {
        return $this->queryable->entitiesWhereHas($key, $whereHasFunc);
    }

    /**
     * Override in the subclass is needed. To convert the incoming entity to the result for the use of the controller.
     * 
     * @param mixed $entity
     * @return mixed
     */
    protected function toResultEntity($entity)
    {
    }
}

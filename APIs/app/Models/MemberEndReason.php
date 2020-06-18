<?php

namespace App\Models;

use App\Contracts\IQueryable;

class MemberEndReason extends CapstoneModel implements IQueryable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'MemberEndReason';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Name',
        'Description',
        'LastModifiedDate',
        'LastModifiedBy'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function findEntity($key)
    {
        return MemberEndReason::find($key);
    }

    public function allEntities()
    {
        return MemberEndReason::all();
    }

    public function entitiesWhere($key, $equalsTo)
    {
        return MemberEndReason::where($key, $equalsTo)->get();
    }
    
    public function entitiesWhereHas($key, callable $whereHasFunc)
    {
        return MemberEndReason::whereHas($key, $whereHasFunc)->get();
    }
}

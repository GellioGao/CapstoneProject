<?php

namespace App\Models;

use App\Contracts\IQueryable;

class MemberAddressHistory extends CapstoneModel implements IQueryable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'MemberAddressHistory';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Member_ID',
        'Active',
        'StartDate',
        'EndDate',
        'Building',
        'Street',
        'TownCity',
        'PostCode',
        'Country'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function findEntity($key)
    {
        return MemberGroupHistory::find($key);
    }

    public function allEntities()
    {
        return MemberGroupHistory::all();
    }

    public function entitiesWhere($key, $equalsTo)
    {
        return MemberGroupHistory::where($key, $equalsTo)->get();
    }
    
    public function entitiesWhereHas($key, callable $whereHasFunc)
    {
        return MemberGroupHistory::whereHas($key, $whereHasFunc)->get();
    }
}
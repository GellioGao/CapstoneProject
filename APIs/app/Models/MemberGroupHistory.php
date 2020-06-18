<?php

namespace App\Models;

use App\Contracts\IQueryable;

class MemberGroupHistory extends CapstoneModel implements IQueryable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'MemberGroupHistory';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Member_ID',
        'Group_ID',
        'Member_End_Reason_ID',
        'StartDate',
        'EndDate',
        'Capitation',
        'Notes',
        'LastModifiedDate',
        'LastModifiedBy'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function endReason()
    {
        return $this->belongsTo(\App\Models\MemberEndReason::class, 'Member_End_Reason_ID');
    }

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
<?php

namespace App\Models;

use App\Contracts\IQueryable;

class Member extends CapstoneModel implements IQueryable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Member';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Title',
        'FirstNames',
        'MiddleNames',
        'LastNames',
        'KnownAs',
        'MailName',
        'DOB',
        'PhotoFileName',
        'LastModifiedDate',
        'LastModifiedBy'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function address()
    {
        return $this->hasOne(\App\Models\MemberAddressHistory::class, 'Member_ID');
    }

    public function history()
    {
        return $this->hasMany(\App\Models\MemberGroupHistory::class, 'Member_ID');
    }

    public function findEntity($key)
    {
        return Member::find($key);
    }

    public function allEntities()
    {
        return Member::all();
    }

    public function entitiesWhere($key, $equalsTo)
    {
        return Member::where($key, $equalsTo)->get();
    }
    
    public function entitiesWhereHas($key, callable $whereHasFunc)
    {
        return Member::whereHas($key, $whereHasFunc)->get();
    }
}

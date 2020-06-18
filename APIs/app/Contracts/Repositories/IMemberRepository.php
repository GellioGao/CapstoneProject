<?php

namespace App\Contracts\Repositories;

interface IMemberRepository
{
    /**
     * Get all member data.
     *
     * @return array
     */
    function all();
    /**
     * Get the member data by pass a member id.
     *
     * @param  int  $id
     * @return mixed
     */
    function one($id);
}

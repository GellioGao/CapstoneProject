<?php

namespace App\Contracts;

interface IArrayStructureChecker
{
    /**
     * Check the $target array's structure with the $structure.
     *
     * @param  array  $structure
     * @param  array  $target
     * @return bool
     */
    function checkStructure(array $structure, array $target): bool;
}

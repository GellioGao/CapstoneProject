<?php

namespace App\Providers;

use App\Contracts\{
    IArrayStructureChecker
};

class ArrayStructureChecker implements IArrayStructureChecker
{
    public function checkStructure(array $structure, array $target): bool
    {
        if (is_null($structure) || is_null($target)) {
            return false;
        }

        foreach ($structure as $key => $value) {
            if (is_array($value) && $key === '*') {
                if (!is_array($target)) {
                    return false;
                }

                foreach ($target as $responseDataItem) {
                    $this->checkStructure($structure['*'], $responseDataItem);
                }
            } elseif (is_array($value)) {
                if (!array_key_exists($key, $target)) {
                    return false;
                }
                $targetValue = $target[$key];
                if (!is_array($targetValue)) {
                    $targetValue = (array) $targetValue;
                }

                $this->checkStructure($structure[$key], $targetValue);
            } else {
                if (!array_key_exists($value, $target)) {
                    return false;
                }
            }
        }
        return true;
    }
}

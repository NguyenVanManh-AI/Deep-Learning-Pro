<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CustomContentIdsValidation implements Rule
{
    public function passes($attribute, $value)
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!is_numeric($item) && (!is_array($item) || count($item) !== 2 || !isset($item['packageId']) || !isset($item['stickerId']) || !is_string($item['packageId']) || !is_string($item['stickerId']))) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'The :attribute must be an array containing numbers or objects with packageId and stickerId as strings, with minimum 1 and maximum 5 elements.';
    }
}

<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class SentAtDifference implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $sentAtTimestamp = Carbon::parse($value)->timestamp; // quy đổi về dạng số hết
        $nowTimestamp = Carbon::now()->timestamp;
        $diffInMinutes = ($nowTimestamp - $sentAtTimestamp) / 60; // không quá 1 phút thì lỗi

        return $diffInMinutes <= 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The difference between sent_at and current time must be at least 1 minute.';
    }
}

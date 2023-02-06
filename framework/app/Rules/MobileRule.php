<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use libphonenumber\PhoneNumberUtil;

/**
 * Class MobileRule
 * @package App\Rules
 */
class MobileRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param mixed $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $phoneUtil   = PhoneNumberUtil::getInstance();
            $numberProto = $phoneUtil->parse($value);

            return $phoneUtil->isValidNumber($numberProto);
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid phone number.';
    }
}

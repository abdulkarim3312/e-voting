<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    protected $message;

    public function passes($attribute, $value)
    {
        if (is_null($value) || $value === '') {
            return true; // allow nullable
        }

        if (strlen($value) < 8) {
            $this->message = 'The password must be at least 8 characters.';
            return false;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $this->message = 'The password must contain at least one lowercase letter.';
            return false;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $this->message = 'The password must contain at least one uppercase letter.';
            return false;
        }

        if (!preg_match('/[0-9]/', $value)) {
            $this->message = 'The password must contain at least one number.';
            return false;
        }

        if (!preg_match('/[@$!%*?&#]/', $value)) {
            $this->message = 'The password must contain at least one special character.';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}

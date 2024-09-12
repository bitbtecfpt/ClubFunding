<?php

namespace App\Validations;

class PaymentValidation extends Validation
{
    public function phoneNumber()
    {
        $this->rules = [
            'phone' => 'required|regex:/^0[0-9]{9}$/',
        ];

        $this->messages = [
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Phone number must be in the format 0xxxxxxxxx',
        ];

        return $this;
    }

    public function rules()
    {
        return count($this->rules) ? $this->rules : [];
    }
}

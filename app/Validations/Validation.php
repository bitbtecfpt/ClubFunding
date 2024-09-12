<?php

namespace App\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class Validation
 *
 * @package App\Validation
 */
abstract class Validation
{
    /**
     * @var array rules
     */
    protected $rules = [];

    /**
     * @var array messages
     */
    protected $messages = [];

    /**
     * @var array attributes
     */
    protected $attributes = [];

    /**
     * @var array ignore
     */
    protected $ignore = [];

    /**
     * Specify laravel style rules
     *
     * @return array
     */
    abstract protected function rules();

    /**
     * Specify rules you want to ignore for the following Validation
     *
     * @param ...$ignore
     *
     * @return $this
     */
    public function ignore(...$ignore)
    {
        $this->ignore = $ignore;

        return $this;
    }

    /**
     * Specify custom messages
     *
     * @return array
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * Specify custom attributes
     *
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    public static function validateExcelFunction($value)
    {
        $value = trim($value);
        if (str_starts_with($value, '=')) {
            $first3Letter = substr($value, 1, 3);
            if ($first3Letter == strtoupper($first3Letter)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Perform Validation
     *
     * @param array|Request $input
     *
     * @throws \App\Exceptions\Errors
     */
    public function run($input)
    {
        if ($input instanceof Request) {
            $input = $input->all();
        }

        if (!is_array($input)) {
            $input = (array)$input;
        }

        Validator::extend('integer_or_string', function($attribute, $value){
            return is_int($value) || is_string($value);
        });

        $validator = Validator::make($input, $this->rules(), $this->messages(), $this->attributes());

        return $validator->fails() ? $validator->errors()->first() : false;
    }
}

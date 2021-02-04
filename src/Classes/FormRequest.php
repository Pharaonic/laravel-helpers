<?php

namespace Pharaonic\Laravel\Helpers\Classes;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class FormRequest extends LaravelFormRequest
{
    /**
     * Error Message (depends on Locale)
     *
     * @var string
     */
    protected $message = 'error.form';

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException||\Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        if (request()->expectsJson()) {
            $msg = !empty($this->message) ? __($this->message) : null;
            throw new HttpResponseException(json(false, ($this->message == $msg ? 'The given data was invalid' : $msg), null, $validator->errors()->toArray()));
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}

<?php

namespace App\Exceptions;

use Exception;
use App\CPU\ResponseUtil;
use Illuminate\Contracts\Validation\Validator;

class MyValidationException extends Exception {
    protected $validator;

    protected $code = 422;

    public function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    public function render() {
        // return a json with desired format
        return response()->json(ResponseUtil::makeError("form validation error",
         [$this->validator->errors()->first()], $this->code), $this->code);

      
    }
}
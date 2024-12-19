<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator; 
use App\Exceptions\MyValidationException;
use Illuminate\Http\Request;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Auth::check(); 
    }
	
	
	protected function failedValidation(Validator $validator ) {
		if (Request::is('api/*')) {
			throw new MyValidationException($validator);
		}
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(isset(request()->id)){
            $id = request()->id;

            return [
                'title' => 'required',
                'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'date' => 'required|date_format:Y-m-d',
                'user_id' => 'required|int',
                'description' => 'required',
                'payment_status'=>['required','in:paid,unpaid'],
                'number'=>'required|unique:invoices,number,'.$id
            ]; 
        }else{
            return [
                'title' => 'required',
                'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'date' => 'required|date_format:Y-m-d',
                'user_id' => 'required|int',
                'description' => 'required',
                'payment_status'=>['required','in:paid,unpaid'],
                'number'=>'required|unique:invoices,number'
            ]; 
        }

    }
    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'Client',


        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array<string, string>
    */
    public function messages()
    {
        return [

        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Auth::check(); 
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
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_phone' => 'required',
                'office_phone' => 'required',
                'address' => 'required',
                'email'=>'required|email|unique:users,email,'.$id,
                'password' => 'nullable|min:6|max:10',
                "user_type"    => "required",
                'permissions'=>'required_if:user_type,employee,admin|array',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'status'=>'required'
            ]; 
        }else{
            return [
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_phone' => 'required',
                'office_phone' => 'required',
                'address' => 'required',
                'email'=>'required|email|unique:users,email',
                'password' => 'required|min:6|max:10',
                "user_type"    => "required",
                'permissions'=>'required_if:user_type,employee,admin|array',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'status'=>'required'
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
            'first_name' => 'User Name',
            'last_name' => 'User SurName',
            'mobile_phone' => 'Mobile Phone',
            'office_phone' => 'Office Name',

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

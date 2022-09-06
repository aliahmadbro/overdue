<?php

namespace App\Http\Requests\Admin\Service;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'exists:services,id,status,' . 0,
            'name'      => 'required|unique:services,name,' . request('id'),
            'parent_id' => 'required_if:sub_service,1',
            'sub_id' => 'required',
            'service_image' => 'image'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'sub_id.required' => "Sub Category is required",
            'parent_id.required_if' => "Parent Service is required"
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        if ($this->method() == 'POST') {
            return [
                'room_id' => 'required',
                'duration_in_month' => 'required'
            ];
        }

        if ($this->method() == 'PATCH' || $this->method() == 'PUT') {
            return [
                'status' => 'required'
            ];
        }
    }
}

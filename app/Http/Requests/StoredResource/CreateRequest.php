<?php

namespace App\Http\Requests\StoredResource;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
        return [
            'name' => ['string'],
            'protocol' => ['string', 'max:10'],
            'domain' => ['string', 'max:100'],
            'port' => ['sometimes', 'integer', 'max:1000000', 'nullable'],
        ];
    }
}

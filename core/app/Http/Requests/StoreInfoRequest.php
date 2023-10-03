<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInfoRequest extends FormRequest
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
            'foretsjachere' => 'required',
            'autresCultures'  => 'required|max:255',
            'autreActivite' => 'required|max:255',
            'travailleurs'  => 'required|max:255',
            'travailleurspermanents'  => 'required|max:255',
            'travailleurstemporaires'  => 'required|max:255',
            'mobileMoney'  => 'required|max:255',
            'compteBanque'=> 'required|max:255',
        ];
    }
}

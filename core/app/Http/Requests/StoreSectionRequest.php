<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
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
            'localite_id'    => 'required|exists:localites,id',
            'libelle' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return [
            'localite_id.required' => 'La localité est obligatoire',
            'localite_id.exists' => 'La localité est invalide',
            'libelle.required' => 'Le libellé est obligatoire',
            'libelle.max' => 'Le libellé ne doit pas dépasser 255 caractères',
        ];
    }
    public function attributes()
    {
        return [
            'localite_id' => 'localité',
            'libelle' => 'libellé',
        ];
    }
}

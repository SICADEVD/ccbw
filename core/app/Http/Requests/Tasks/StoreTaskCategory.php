<?php

namespace App\Http\Requests\Tasks;

use App\Http\Requests\CoreRequest;

class StoreTaskCategory extends CoreRequest
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
            'category_name' => 'required|unique:task_category,category_name,null,id,cooperative_id,' . cooperative()->id
        ];
    }

}

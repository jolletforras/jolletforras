<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
        return  [
				'name' => 'required|max:60',
				'description' => 'required',
        		'tag_list' => 'required',
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
				'name.required' => 'Kérlek add meg a csoport nevét',
				'name.max'  => 'A csoport név legfeljebb :max karakter lehet',
				'body.required' => 'Kérlek add meg a csoport bemutatkozását,célját',
    			'tag_list.required' => 'Kérlek adjál meg legalább egy témát a csoporttal kapcsolatban',
    	];
    }
}

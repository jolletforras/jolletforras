<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
				'title' => 'required|max:60',
				'body' => 'required',
                'my_undertake' => 'required',
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
				'title.required' => 'Kérlek add meg a vállalkozás megnevezését',
				'title.max'  => 'A megnevezés legfeljebb :max karakter lehet',
				'body.required' => 'Kérlek add meg a vállalkozás leírását',
                'my_undertake.required' => 'Kérlek add meg, hogy te kezdeményezőként mit tudsz beletenni a megvalósításba',
    			'tag_list.required' => 'Kérlek legalább egy területet adjál meg, amelyben keresel embert a vállalkozáshoz',
    	];
    }
}

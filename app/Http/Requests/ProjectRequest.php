<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class ProjectRequest extends Request
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
                'looking_for' => 'required',
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
                'looking_for.required' => 'Kérlek add meg milyen tudású/képességű embereket keresel',
    			'tag_list.required' => 'Kérlek legalább egy területet adjál meg, amelyben keresel embert a vállalkozáshoz',
    	];
    }
}

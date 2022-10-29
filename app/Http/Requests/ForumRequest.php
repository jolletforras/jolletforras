<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForumRequest extends FormRequest
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
				'title.required' => 'Kérlek add meg a téma címét',
				'title.max'  => 'A cím legfeljebb :max karakter lehet',
				'body.required' => 'Kérlek add meg a téma szövegét',
    			'tag_list.required' => 'Kérlek legalább egy címkét adjál meg',
    	];
    }
}

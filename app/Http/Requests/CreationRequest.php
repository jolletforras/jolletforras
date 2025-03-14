<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreationRequest extends FormRequest
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
        $rules =  [
				'title' => 'required|max:80',
				'body' => 'required',
                'image' => 'mimes:jpg,png,gif|max:8000'
		];

        return $rules;
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
    	return [
				'title.required' => 'Kérlek add meg az alkotás címét',
				'title.max'  => 'A cím legfeljebb :max karakter lehet',
				'body.required' => 'Kérlek add meg az ajánló szövegét',
                'image.mimes' => 'A kép fájltípusa .jpg, .png, .gif kell legyen',
                'image.max' => 'A kép nem lehet nagyobb mint :max KB',
    	];
    }
}

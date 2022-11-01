<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
			//'name' => 'required',
			//'introduction' => 'required|min:30',
        	//'zip_code' => 'required|numeric|min:1000',
        	//'city' => 'required',
        	//'tag_list' => 'required',
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
    			'name.required' => 'Kérlek add meg a teljes neved',
                'location.required' => 'Kérlek add meg a lakóhelyed',
    			'introduction.required'  => 'Kérlek add meg a bemutatkozásod',
    			'introduction.min'  => 'A bemutatkozásod leírása legalább :min karakter kell legyen',
    			'zip_code.required' => 'Kérlek add meg az irányítószámod. A térképen való feltűntetés miatt szükséges.',
    			'zip_code.numeric' => 'Az irányítószám 4 számjegyből kell álljon',
    			'zip_code.min' => 'Az irányítószám 4 számjegyből kell álljon',
    			'city.required' => 'Kérlek add meg a közeli nagy települést. A keresés miatt szükséges.',
    			'tag_list.required' => 'Kérlek legalább egy címkét adjál meg, miben van jártasságod, mit tudnál hozzáadni egy közösséghez',
    	];
    }
}
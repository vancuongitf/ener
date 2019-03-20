<?php

namespace App\Http\Requests\Admin\Post;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'route' => 'required',
            'summernote' => 'required'
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $isMatch = false;
            $array = array();
            preg_match('/[a-zA-Z](([-][a-zA-Z0-9])*([a-zA-Z0-9])*)*[.][h][t][m][l]/', $this->input('route'), $array);
            foreach ($array as $key => $value) {
                if ($value == $this->input('route')) {
                    $isMatch = true;
                }
            }
            if (!$isMatch) {
                $validator->errors()->add('route-regex', 'This tag is already exist!');                
            }
        });
    }
}

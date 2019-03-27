<?php

namespace App\Http\Requests\Admin\Post;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Post;
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
            'title' => 'required|max:300',
            'route' => 'required|max:300',
            'summernote' => 'required'
        ];
    }

    public function messages() {
        return [
            'title.required' => 'Post\'s name is required!',
            'title.max' => 'Post\'s name length must short than 300 chars!',
            'route.required'  => 'Post\'s route is required!',
            'route.max' => 'Post\'s route length must short than 300 chars!',
            'summernote.required' => 'Post\'s content is required!'
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $isMatch = false;
            $array = array();
            preg_match('/[a-zA-Z0-9](([-][a-zA-Z0-9])*([a-zA-Z0-9])*)*[.][h][t][m][l]/', $this->input('route'), $array);
            foreach ($array as $key => $value) {
                if ($value == $this->input('route')) {
                    $isMatch = true;
                }
            }
            if (!$isMatch) {
                $validator->errors()->add('route-regex', 'This route incorrect format!');                
            }
            $posts = Post::where('route', $this->input('route'))->get();
            foreach ($posts as $post) {
                if ($post->id != $this->input('id')) {
                    $validator->errors()->add('route-exist', 'This route is already exists!');                                
                    break;
                }
            }
        });
    }
}

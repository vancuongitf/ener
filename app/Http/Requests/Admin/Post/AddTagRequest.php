<?php

namespace App\Http\Requests\Admin\Post;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use App\Model\Tag\PostTag;
class AddTagRequest extends FormRequest {
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
    public function rules() {
        return [
            'tag_level_1' => 'required'
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $post_id = $this->input('post_id');
            $tag_level_1 = $this->input('tag_level_1');
            $tag_level_2 = $this->input('tag_level_2');
            $tag_level_3 = $this->input('tag_level_3');

            if ($tag_level_3 != null) {
                $tag = PostTag::where('post_id', $post_id)->where('tag_level_1_id', $tag_level_1)->where('tag_level_2_id', $tag_level_2)->where('tag_level_3_id', $tag_level_3)->first();
            } else {
                if ($tag_level_2 != null) {
                    $tag = PostTag::where('post_id', $post_id)->where('tag_level_1_id', $tag_level_1)->where('tag_level_2_id', $tag_level_2)->first();
                } else {
                    $tag = PostTag::where('post_id', $post_id)->where('tag_level_1_id', $tag_level_1)->first();                    
                }
            }
            if ($tag != null) {
                $validator->errors()->add('tag-exist', 'This tag is already exist!');                
            }
        });
    }
}

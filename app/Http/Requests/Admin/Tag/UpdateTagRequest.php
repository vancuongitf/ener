<?php

namespace App\Http\Requests\Admin\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use App\Model\Tag\TagLevel1;
use App\Model\Tag\TagLevel2;
use App\Model\Tag\TagLevel3;

class UpdateTagRequest extends FormRequest
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
    public function rules() {
        return [
            'name' => 'required',
            'route' => 'required'
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $route = $this->input('route');
            $name = $this->input('name');
            $routeExist = false;
            $nameExist = false;
            $isMatch = false;
            $array = array();
            preg_match('/[a-zA-Z0-9](([-][a-zA-Z0-9])*([a-zA-Z0-9])*)*/', $route, $array);
            foreach ($array as $key => $value) {
                if ($value == $this->input('route')) {
                    $isMatch = true;
                }
            }
            if (!$isMatch) {
                $validator->errors()->add('route-regex', 'This route incorrect format!');                
            }
            switch($this->input('level')) {
                case '1':
                    $routeExist = TagLevel1::where('route',$route)->where('id', '!=', $this->input('id'))->first() != null;
                    $nameExist = TagLevel1::where('name', $name)->where('id', '!=', $this->input('id'))->first() != null;
                    break;
                case '2':
                    $routeExist = TagLevel2::where('route', $route)->where('tag_level_1_id', $this->input('parent_id'))->where('id', '!=', $this->input('id'))->first() != null;
                    $nameExist = TagLevel2::where('name', $name)->where('tag_level_1_id', $this->input('parent_id'))->where('id', '!=', $this->input('id'))->first() != null;
                    break;
                case '3':
                    $routeExist = TagLevel3::where('route', $route)->where('tag_level_2_id', $this->input('parent_id'))->where('id', '!=', $this->input('id'))->first() != null;                    
                    $nameExist = TagLevel3::where('name', $name)->where('tag_level_2_id', $this->input('parent_id'))->where('id', '!=', $this->input('id'))->first() != null;                                        
                    break;
            }
            if ($routeExist) {
                $validator->errors()->add('route-exist', 'This tag\'s route already exist!');                
            }
            if ($nameExist) {
                $validator->errors()->add('name-exist', 'This tag\'s name already exist!');                
            }
        });
    }
}

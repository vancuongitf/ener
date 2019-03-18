<?php

namespace App\Http\Requests\Admin\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use App\Model\Tag\TagLevel1;
use App\Model\Tag\TagLevel2;
use App\Model\Tag\TagLevel3;

class CreateTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        switch($this->input('level')) {
            case '2':
                return [
                    'name' => 'required',
                    'route' => 'required',
                    'level' => 'required',
                    'level_1_parent' => 'required'
                ];
            case '3': 
                return [
                    'name' => 'required',
                    'route' => 'required',
                    'level' => 'required',
                    'level_1_parent' => 'required',
                    'level_2_parent' => 'required'
                ];       
            default:
                return [
                    'name' => 'required',
                    'route' => 'required',
                    'level' => 'required',
                ];
        }
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $route = $this->input('route');
            $name = $this->input('name');
            $routeExist = false;
            $nameExist = false;

            switch($this->input('level')) {
                case '1':
                    $routeExist = TagLevel1::where('route', $route)->first() != null;
                    $nameExist = TagLevel1::where('name', $name)->first() != null;
                    break;
                case '2':
                    $routeExist = TagLevel2::where('route', $route)->where('tag_level_1_id', $this->input('level_1_parent'))->first() != null;
                    $nameExist = TagLevel2::where('name', $name)->where('tag_level_1_id', $this->input('level_1_parent'))->first() != null;
                    break;
                case '3':
                    $routeExist = TagLevel3::where('route', $route)->where('tag_level_2_id', $this->input('level_2_parent'))->first() != null;                    
                    $nameExist = TagLevel3::where('name', $name)->where('tag_level_2_id', $this->input('level_2_parent'))->first() != null;                                        
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

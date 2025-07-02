<?php
namespace Modules\Menu\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->method() == 'POST' ? '' : ',' . $this->segment(3);
        return [
            'slug' => 'required|min:2|max:255|unique:menus,slug' . $id,
            'description' => '',
        ];
    }
}

<?php
namespace Modules\Acl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->method() == 'POST' ? '' : ',' . $this->segment(4);
        return [
            'slug' => 'required|min:2|max:255|unique:permissions,slug' . $id,
        ];
    }
}

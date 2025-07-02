<?php
namespace Modules\Acl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->method() == 'POST' ? '' : ',' . $this->segment(4);
        return [
            'slug' => 'required|alpha_dash|min:2|max:255|unique:roles,slug' . $id,
        ];
    }
}

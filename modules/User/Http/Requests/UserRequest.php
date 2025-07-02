<?php
namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->method() == 'POST' ? '' : ',' . $this->segment(3);

        return [
            'username' => 'required|min:3|max:255|alpha_dash|unique:users,username' . $id,
            'email' => 'required|min:3|max:255|email|unique:users,username' . $id,
            'password' => 'confirmed',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg,bmp,gif',
        ];
    }
}

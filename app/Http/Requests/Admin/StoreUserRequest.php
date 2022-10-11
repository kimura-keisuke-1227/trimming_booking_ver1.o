<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
    return [
        'last_name' => ['required', 'string', 'max:255'],
        'last_name_kana' => ['required', 'string', 'max:255','regex:/\A[ァ-ヴー]+\z/u'],
        'first_name' => ['required', 'string', 'max:255'],
        'first_name_kana' => ['required', 'string', 'max:255','regex:/\A[ァ-ヴー]+\z/u'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'phone' => ['required', 'string', 'max:255','regex:/^0(\d-?\d{4}|\d{2}-?\d{3}|\d{3}-?\d{2}|\d{4}-?\d|\d0-?\d{4})-?\d{4}$/'],
        'default_salon' => ['integer'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'beforeCame'=>['required']
        /*
        'image' => [
            'required',
            'file', // ファイルがアップロードされている
            'image', // 画像ファイルである
            'max:2000', // ファイル容量が2000kb以下である
            'mimes:jpeg,jpg,png', // 形式はjpegかpng
            'dimensions:min_width=100,min_height=100,max_width=300,max_height=300', // 画像の解像度が100px * 100px ~ 300px * 300px
        ],
        'introduction' => ['required', 'string', 'max:255'],
        */
    ];
    }
}

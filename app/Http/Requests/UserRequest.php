<?php

namespace App\Http\Requests;
use Request;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $id = Request::segment(2) != null ? Request::segment(2) : null;
        if ($this->getMethod() == 'POST') {
            return [
                'full_name' => 'bail|required',
                'email' => 'required|email|unique:users,email',
                'captcha' => 'required|captcha',
                'password' => [
                    'required',
                    'string',
                    'min:12',
                    'regex:/[A-Z]/', // Harus ada huruf besar
                    'regex:/[a-z]/', // Harus ada huruf kecil
                    'regex:/[0-9]/', // Harus ada angka
                    'regex:/[!@#$%^&*()_+\-]/', // Harus ada karakter spesial
                ],
            ];
        } else {
            return [
                'full_name' => 'bail|required',
                'email' => 'bail|required|unique:users,email,' . $id . ',id_user',
                'password' => [
                    'required',
                    'string',
                    'min:12',
                    'regex:/[A-Z]/', // Harus ada huruf besar
                    'regex:/[a-z]/', // Harus ada huruf kecil
                    'regex:/[0-9]/', // Harus ada angka
                    'regex:/[!@#$%^&*()_+\-]/', // Harus ada karakter spesial
                ],
            ];
        }
    }

    public function messages()
    {
        return [
            'email' => 'Email sudah digunakan user lain, mohon untuk menggunakan email yang lain',
            'captcha' => 'captcha tidak sesuai',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password harus memiliki minimal 12 karakter.',
            'password.regex' => 'Password harus mengandung minimal satu huruf besar, satu huruf kecil, satu angka, dan satu karakter spesial (!@#$%^&*()_+-).',
        ];
    }
}

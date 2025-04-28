<?php

namespace Modules\Permohonan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenguranganIptRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nik' => 'required|numeric',
            'nama_pemohon' => 'required',
            'alamat_persil' => 'required',
            'penggunaan' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}

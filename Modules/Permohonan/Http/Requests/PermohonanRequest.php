<?php

namespace Modules\Permohonan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Permohonan\Entities\LayananDocument;
use Modules\Permohonan\Entities\LayananForm;

class PermohonanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $idTipe = $_REQUEST['tipe'];
        $form = LayananForm::where('id_layanan', $idTipe)->get();
        $document = LayananDocument::where('id_layanan', $idTipe)->get();
        $rules = [
            'tipe' => 'required',
        ];
        foreach ($form as $validation) {
            $rules[change_form($validation->nama_form)] = $validation->status;
        }
        // foreach ($document as $validation) {
        //     if (isset($validation->status)) {
        //         $rules[change_form($validation->nama_document)] = $validation->status;
        //     }
        // }
        return $rules;
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

<?php

namespace Modules\Permohonan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Permohonan\Entities\PenguranganIpt;
use Modules\Permohonan\Entities\PenguranganIptBap;
use Modules\Permohonan\Entities\PenguranganIptSurat;
use Auth;
use DB;
use Modules\Permohonan\Entities\PermohonanHistory;
use Illuminate\Support\Facades\Validator;

class SuratKeteranganController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $surat = PenguranganIptSurat::leftJoin('ipt_pengurangan', 'ipt_pengurangan.id_surat', '=', 'ipt_pengurangan_surat.id')
            ->select('ipt_pengurangan_surat.*', 'ipt_pengurangan.*', 'ipt_pengurangan.id as id_permohonan', 'ipt_pengurangan.no_permohonan')
            ->orderBy('ipt_pengurangan_surat.id', 'DESC');

        if (isset($request->id_surat)) {
            $surat = $surat->where('ipt_pengurangan_surat.id', $request->id_surat);
        }

        if (isset($request->id_permohonan)) {
            $surat = $surat->where('ipt_pengurangan.id', $request->id_permohonan);
        }

        if (isset($request->nm_pemohon)) {
            $surat = $surat->where('ipt_pengurangan.nama_pemohon', 'LIKE', "%$request->nm_pemohon%");
        }

        if (isset($request->alamat_persil)) {
            $surat = $surat->where('ipt_pengurangan.alamat_persil', 'LIKE', "%$request->alamat_persil%");
        }

        $data["data"] = $surat->paginate(10);
        $data['filter'] = [
            'id_surat' => isset($request->id_surat) ? PenguranganIptSurat::findOrFail($request->id_surat) : NULL,
            'id_permohonan' => isset($request->id_permohonan) ? PenguranganIpt::findOrFail($request->id_permohonan) : NULL,
            'nm_pemohon' => $request->nm_pemohon,
            'alamat_persil' => $request->alamat_persil,
        ];
        return view('permohonan::surat-keterangan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request, $id)
    {
        $data["data"] = PenguranganIpt::with('layanan')->findOrFail($id);
        $data["surat"] = PenguranganIptSurat::where('id_permohonan', $id)->first();
        $data["bap"] = PenguranganIptBap::where('id_permohonan', $id)->orderBy('id', 'DESC')->first();
        if (isset($data["bap"])) {
            $url = "https://sigasda.surabaya.go.id/api/DbPenetapanRetribusi";
            $headers = [
                "username: Sekre@admin2025!",
                "password: S3kr3@4dm1n",
                "Content-Type: application/json"
            ];
            $variable = [
                'id_persil' => $data['data']->no_sk,
                'nop' => '0',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($variable));
            $response = curl_exec($ch);
            $response = json_decode($response, true);

            // Cek error
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            } else {
                $skrd = json_decode(json_encode($response['data'][0]));
            }
            curl_close($ch);
        }
        // dd($data);
        $data["skrd"] = $skrd;
        $data['keterangan'] = PenguranganIpt::$keteranganType;
        $nominalPengurangan = PenguranganIpt::$potonganType;
        $data['nominal_pengurangan'] = $request->nominal_pengurangan ?? $nominalPengurangan[$data['data']->type];
        $data['page_plugin_js'] = [
            'cuba/js/editor/ckeditor/ckeditor.js',
            'cuba/js/editor/ckeditor/adapters/jquery.js',
            'cuba/js/editor/ckeditor/styles.js',
            'cuba/js/editor/ckeditor/ckeditor.custom.js',
        ];

        return view('permohonan::surat-keterangan.create-new', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $array = [
            'id_permohonan' => 'required',
            'nominal_pengurangan' => 'required',
            'periode' => 'required',
        ];
        $validator = Validator::make($request->all(), $array);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            DB::beginTransaction();
            $surat = new PenguranganIptSurat();
            $surat->isi = $request->isi;
            $surat->no_persil = $request->no_persil;
            $surat->tgl_ipt = $request->tgl_ipt;
            $surat->nama_pemegang_ipt = $request->nama_pemegang_ipt;
            $surat->alamat_persil = $request->alamat_persil;
            $surat->bukti = $request->bukti;
            $surat->periode = $request->periode;
            $surat->no_skrd = $request->no_skrd;
            $surat->list_nama = $request->list_nama;
            $surat->nominal_pengurangan = $request->nominal_pengurangan;
            $surat->created_by = Auth::user()->id_user;

            if (isset($request->file_surat) and $request->file('file_surat') != null) {
                $doc = $request->file('file_surat');
                $path_doc = $doc->store('file_surat');
                $surat->file = $path_doc;
            }
            $surat->save();

            $permohonan = PenguranganIpt::findOrFail($request->id_permohonan);
            PenguranganIpt::where("id", $request->id_permohonan)->update([
                'id_status' => 3,
                'id_surat' => $surat->id,
            ]);

            $history = new PermohonanHistory();
            $history->id_permohonan = $request->id_permohonan;
            $history->id_status = 3;
            $history->nm_status = 'PEMBUATAN KONSEP SURAT';
            $history->id_verifikator = Auth::user()->id_user;
            $history->nama_verifikator = Auth::user()->nm_user;
            $history->tgl_status = date('Y-m-d');
            $history->type = 'ipt-pengurangan';
            $history->keterangan = 'Pembuatan Konsep Surat Keputusan';
            $history->save();

            // dd($surat);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Konsep Surat Gagal Dibuat ' . $e->getMessage());
        }
        return redirect('ipt-pengurangan/' . $request->id_permohonan . '/verifikasi')->with('success', 'Konsep Surat Keputusan Berhasil Dibuat');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('permohonan::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
    {
        $data["surat"] = PenguranganIptSurat::findOrFail($id);
        $data["data"] = PenguranganIpt::where('id_surat', $id)->first();
        $data["bap"] = PenguranganIptBap::where('id_permohonan', $data["data"]->id)->orderBy('id', 'DESC')->first();
        if (isset($data["bap"])) {
            $url = "https://sigasda.surabaya.go.id/api/DbPenetapanRetribusi";
            $headers = [
                "username: Sekre@admin2025!",
                "password: S3kr3@4dm1n",
                "Content-Type: application/json"
            ];
            $variable = [
                'id_persil' => $data['data']->no_sk,
                'nop' => '0',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($variable));
            $response = curl_exec($ch);
            $response = json_decode($response, true);

            // Cek error
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            } else {
                $skrd = json_decode(json_encode($response['data'][0]));
            }
            curl_close($ch);
        }
        // dd($data);
        $data["skrd"] = $skrd;$data['keterangan'] = PenguranganIpt::$keteranganType;
        $nominalPengurangan = PenguranganIpt::$potonganType;
        $data['nominal_pengurangan'] = $request->nominal_pengurangan ?? $nominalPengurangan[$data['data']->type];
        $data['page_plugin_js'] = [
            'cuba/js/editor/ckeditor/ckeditor.js',
            'cuba/js/editor/ckeditor/adapters/jquery.js',
            'cuba/js/editor/ckeditor/styles.js',
            'cuba/js/editor/ckeditor/ckeditor.custom.js',
        ];

        return view('permohonan::surat-keterangan.create-new', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $array = [
            'id_permohonan' => 'required',
            'nominal_pengurangan' => 'required',
            'periode' => 'required',
        ];
        $validator = Validator::make($request->all(), $array);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            DB::beginTransaction();
            $surat = PenguranganIptSurat::findOrFail($id);
            $surat->isi = $request->isi;
            $surat->no_persil = $request->no_persil;
            $surat->tgl_ipt = $request->tgl_ipt;
            $surat->nama_pemegang_ipt = $request->nama_pemegang_ipt;
            $surat->alamat_persil = $request->alamat_persil;
            $surat->bukti = $request->bukti;
            $surat->periode = $request->periode;
            $surat->no_skrd = $request->no_skrd;
            $surat->list_nama = $request->list_nama;
            $surat->nominal_pengurangan = $request->nominal_pengurangan;
            $surat->created_by = Auth::user()->id_user;

            if (isset($request->file_surat) and $request->file('file_surat') != null) {
                $doc = $request->file('file_surat');
                $path_doc = $doc->store('file_surat');
                $surat->file = $path_doc;
            }
            $surat->save();

            $permohonan = PenguranganIpt::findOrFail($request->id_permohonan);
            PenguranganIpt::where("id", $request->id_permohonan)->update([
                'id_status' => 3,
                'id_surat' => $surat->id,
            ]);

            $history = new PermohonanHistory();
            $history->id_permohonan = $request->id_permohonan;
            $history->id_status = 3;
            $history->nm_status = 'PEMBUATAN KONSEP SURAT';
            $history->id_verifikator = Auth::user()->id_user;
            $history->nama_verifikator = Auth::user()->nm_user;
            $history->tgl_status = date('Y-m-d');
            $history->type = 'ipt-pengurangan';
            $history->keterangan = 'Update Konsep Surat Keputusan';
            $history->save();

            // dd($surat);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Konsep Surat Gagal Dibuat ' . $e->getMessage());
        }
        return redirect('ipt-pengurangan/' . $request->id_permohonan . '/verifikasi')->with('success', 'Konsep Surat Keputusan Berhasil Dibuat');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function cetak($id)
    {
        $data["surat"] = PenguranganIptSurat::findOrFail($id);
        $permohonan = PenguranganIpt::where('id_surat', $id)->first();
        $data['data'] = $permohonan;
        $data['bap'] = PenguranganIptBap::where('id_permohonan', $permohonan->id)->orderBy('id', 'desc')->first();
        $noSk = $permohonan->no_sk;
        $url = "https://sigasda.surabaya.go.id/api/DbPenetapanRetribusi";
        $headers = [
            "username: Sekre@admin2025!",
            "password: S3kr3@4dm1n",
            "Content-Type: application/json"
        ];
        $variable = [
            'id_persil' => $noSk,
            'nop' => '0',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($variable));
        $response = curl_exec($ch);
        $response = json_decode($response, true);

        // Cek error
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            $skrd = json_decode(json_encode($response['data'][0]));
        }
        curl_close($ch);

        $data['skrd'] = $skrd;
        $data['keterangan'] = PenguranganIpt::$keteranganType;

        $pdf = \PDF::loadview("permohonan::surat-keterangan.cetak-new", $data)
            ->setOptions(['defaultFont' => 'Arial'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

}

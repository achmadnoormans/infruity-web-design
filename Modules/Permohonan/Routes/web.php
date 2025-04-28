<?php

use Illuminate\Support\Facades\Route;
use Modules\Permohonan\Http\Controllers\LayananController;
use Modules\Permohonan\Http\Controllers\LayananDocumentController;
use Modules\Permohonan\Http\Controllers\LayananFormController;
use Modules\Permohonan\Http\Controllers\PermohonanController;
use Modules\Permohonan\Http\Controllers\SelectController;
use Modules\Permohonan\Http\Controllers\StatusDokumenController;
use Modules\Permohonan\Http\Controllers\SuratPermohonanController;
use Modules\Permohonan\Http\Controllers\PenguranganIptController;
use Modules\Permohonan\Http\Controllers\SuratKeteranganController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::group(['prefix' => '/', 'middleware' => ['auth', 'role']], function () {
    Route::resource('permohonan', PermohonanController::class)->names('permohonan')->except('show');
    Route::get('permohonan-submit', [PermohonanController::class, 'permohonan_filter']);
    Route::get('permohonan-bap', [PermohonanController::class, 'permohonan_filter']);
    Route::get('permohonan-konsep-surat', [PermohonanController::class, 'permohonan_filter']);
    Route::get('permohonan-verifikasi-ketua', [PermohonanController::class, 'permohonan_filter']);
    Route::get('penyelia-surat', [PermohonanController::class, 'permohonan_filter']);
    Route::get('permohonan-verifikasi-kabid', [PermohonanController::class, 'permohonan_filter']);
    Route::get('permohonan-verifikasi-sekretaris', [PermohonanController::class, 'permohonan_filter']);
    Route::get('permohonan-verifikasi-kaban', [PermohonanController::class, 'permohonan_filter']);
    Route::get('permohonan-proses-selesai', [PermohonanController::class, 'permohonan_filter']);
    Route::get('permohonan-proses', [PermohonanController::class, 'monitoring_berkas']);
    Route::get('permohonan-proses/{id}/show', [PermohonanController::class, 'monitoring_berkas_show']);
    Route::get('permohonan-selesai', [PermohonanController::class, 'monitoring_berkas']);
    Route::get('permohonan/{id}/detail', [PermohonanController::class, 'show']);
    Route::post('permohonan/{id}/submit-data', [PermohonanController::class, 'submit_data']);
    Route::get('permohonan/{id}/history', [PermohonanController::class, 'history']);
    Route::get('permohonan/show-document', [PermohonanController::class, 'get_doc'])->name('show-document');
    Route::get('permohonan/{id}/cetak-permohonan', [PermohonanController::class, 'cetak'])->name('cetak-permohonan');
    Route::get('permohonan/{id}/cetak-formulir', [PermohonanController::class, 'cetak_formulir'])->name('cetak-formulir');
    Route::get('permohonan/{id}/cetak-bap', [PermohonanController::class, 'cetak_bap'])->name('cetak-bap');
    Route::get('permohonan/{id}/show-bap', [PermohonanController::class, 'show_bap'])->name('show-bap');
    Route::get('permohonan/{id}/verifikasi', [PermohonanController::class, 'verifikasi']);
    Route::post('permohonan/do-verifikasi', [PermohonanController::class, 'do_verifikasi']);
    Route::post('permohonan/verifikasi-arsip', [PermohonanController::class, 'verifikasi_arsip']);
    Route::post('permohonan/verifikasi-berkas', [PermohonanController::class, 'verifikasi_berkas']);
    Route::get('permohonan/{id}/show-keterangan-arsip', [PermohonanController::class, 'show_keterangan_arsip']);
    Route::post('permohonan/upload-formulir', [PermohonanController::class, 'upload_formulir']);
    Route::post('permohonan/upload-file', [PermohonanController::class, 'upload_file']);
    Route::post('permohonan/submit-ulang', [PermohonanController::class, 'submit']);
    Route::post('permohonan/upload-bap', [PermohonanController::class, 'upload_bap']);
    Route::post('permohonan/verifikasi-surat', [PermohonanController::class, 'verifikasi_surat']);
    Route::post('permohonan/verifikasi-kaban', [PermohonanController::class, 'verifikasi_kaban']);
    Route::post('permohonan/selesaikan-proses', [PermohonanController::class, 'selesaikan_proses']);

    Route::resource('surat', SuratPermohonanController::class)->names('surat')->except('show');
    Route::get('surat/{id}/create-surat', [SuratPermohonanController::class, 'create']);
    Route::get('surat/create-surat-kolektif', [SuratPermohonanController::class, 'create_kolektif']);
    Route::post('surat/save-surat-kolektif', [SuratPermohonanController::class, 'save_kolektif']);
    Route::post('surat/save-list', [SuratPermohonanController::class, 'save_list']);
    Route::put('surat/update-surat-kolektif/{id}', [SuratPermohonanController::class, 'update_kolektif']);
    Route::get('surat/{id}/cetak-surat', [SuratPermohonanController::class, 'cetak']);

    Route::resource('layanan', LayananController::class)->names('layanan')->except('show');
    Route::resource('status-dokumen', StatusDokumenController::class)->names('status-dokumen')->except('show');
    Route::resource('layanan-form', LayananFormController::class)->names('layanan-form')->except('show');
    Route::get('layanan-form/{id}/detail', [LayananFormController::class, 'show']);
    Route::resource('layanan-dokumen', LayananDocumentController::class)->names('layanan-dokumen')->except('show');
    Route::get('layanan-dokumen/{id}/detail', [LayananDocumentController::class, 'show']);

    // ipt-pengurangan
    Route::resource('ipt-pengurangan', PenguranganIptController::class)->names('ipt-pengurangan')->except('show');
    Route::get('ipt-pengurangan/{id}/detail', [PenguranganIptController::class, 'show']);
    Route::get('ipt-pengurangan/{id}/history', [PenguranganIptController::class, 'history']);
    Route::post('ipt-pengurangan/upload-file', [PenguranganIptController::class, 'upload_file']);
    Route::post('ipt-pengurangan/{id}/submit-data', [PenguranganIptController::class, 'submit_data']);
    Route::get('ipt-pengurangan/{id}/cetak-permohonan', [PenguranganIptController::class, 'cetak']);
    Route::get('ipt-pengurangan/{id}/verifikasi', [PenguranganIptController::class, 'verifikasi']);
    Route::post('ipt-pengurangan/upload-bap', [PenguranganIptController::class, 'upload_bap']);
    Route::post('ipt-pengurangan/verifikasi-surat', [PenguranganIptController::class, 'verifikasi_surat']);
    Route::post('ipt-pengurangan/do-verifikasi', [PenguranganIptController::class, 'do_verifikasi']);
    Route::post('ipt-pengurangan/verifikasi-kaban', [PenguranganIptController::class, 'verifikasi_kaban']);
    Route::post('ipt-pengurangan/selesaikan-proses', [PenguranganIptController::class, 'selesaikan_proses']);

    // surat-keterangan
    Route::resource('surat-keterangan', SuratKeteranganController::class)->names('surat-keterangan')->except('show');
    Route::get('surat-keterangan/{id}/create-surat', [SuratKeteranganController::class, 'create']);
    Route::get('surat-keterangan/{id}/cetak-surat', [SuratKeteranganController::class, 'cetak']);
});

Route::group(['prefix' => '/', 'middleware' => ['auth']], function () {
    Route::get('/ajax-search-permohonan/{id?}', [SelectController::class, 'search'])->name('ajax.search');
    Route::get('/ajax-search-surat/{id?}', [SelectController::class, 'search_surat'])->name('ajax.search.surat');
    Route::get('/ajax-search-persil/{id?}', [SelectController::class, 'search_persil'])->name('ajax.search.persil');
    Route::get('/ajax-search-pemohon/{id?}', [SelectController::class, 'search_nama_pemohon'])->name('ajax.search.pemohon');
    Route::get('permohonan-proses/data', [PermohonanController::class, 'monitoring_berkas_get_data'])->name('monitoring-proses-data');
    Route::get('cetak-data-dashboard', [PermohonanController::class, 'monitoring_berkas_get_data_cetak'])->name('cetak-data-dashboard');
    Route::get('cetak-berkas', [PermohonanController::class, 'cetak_monitoring_berkas'])->name('cetak-berkas-selesai');
    Route::post('/qrcode/store', [SuratPermohonanController::class, 'store_qr'])->name('qrcode.store');
    Route::get('surat/{id}/cetak-surat-qrcode', [SuratPermohonanController::class, 'cetak_dengan_qr'])->name('cetak-qr-code');
    Route::get('surat/data', [SuratPermohonanController::class, 'get_data_surat'])->name('surat-data');
    Route::get('permohonan-ditolak', [PermohonanController::class, 'monitoring_berkas']);
});

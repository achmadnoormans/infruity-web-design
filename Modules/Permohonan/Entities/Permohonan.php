<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permohonan extends Model
{
    use HasFactory;

    protected $fillable = [
        'deleted_at'
    ];
    protected $table = "t_permohonan";
    protected $primaryKey = 'id';

    public function layanan()
    {
        return $this->belongsTo('Modules\Permohonan\Entities\Layanan', 'id_layanan', 'id_layanan');
    }

    public function status()
    {
        return $this->belongsTo('Modules\Permohonan\Entities\StatusDokumen', 'id_status', 'id_status');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public function histories()
    {
        return $this->hasMany('Modules\Permohonan\Entities\PermohonanHistory', 'id_permohonan', 'id_permohonan');
    }

    public static function getStatusVerifikasi($id_layanan, $id_status)
    {
        if (in_array($id_layanan, [7])) {
            switch ($id_status) {
                case 1:
                    $data['status'] = [
                        '99' => 'Kembalikan Data Ke Pemohon (Reject)',
                        '2' => 'Lanjutkan Proses Selanjutnya'
                    ];
                    break;

                case 2:
                    $data['status'] = [
                        '1' => 'Kembalikan Data Ke Petugas Arsip / Berkas',
                        '8' => 'Lanjutkan Proses Ke Kepala Badan'
                    ];
                    break;

                case 4:
                    $data['status'] = [
                        '2' => 'Kembalikan Data ke BAP',
                        '3' => 'Kembalikan Data Ke Petugas SK',
                        '5' => 'Lanjutkan Proses Ke Kabid',
                    ];
                    break;

                case 5:
                    $data['status'] = [
                        '1' => 'Kembalikan Data Ke Petugas Arsip / Berkas',
                        '6' => 'Kembalikan Data Ke Ketua Tim',
                        '3' => 'Lanjutkan Proses Ke SK',
                    ];
                    break;

                case 6:
                    $data['status'] = [
                        '1' => 'Kembalikan Data Ke Petugas Arsip / Berkas',
                        '5' => 'Lanjutkan Proses Ke BAP',
                    ];
                    break;

                case 7:
                    $data['status'] = [
                        '1' => 'Kembalikan Data Ke Petugas Arsip / Berkas',
                        '2' => 'Kembalikan Data Ke Kaban',
                        '6' => 'Lanjutkan Proses Ke Ketua Tim',
                    ];
                    break;

                case 8:
                    $data['status'] = [
                        '1' => 'Kembalikan Data Ke Petugas Arsip / Berkas',
                        '2' => 'Kembalikan Data Ke Sekretaris',
                        '7' => 'Lanjutkan Proses Ke Kepala Bidang',
                    ];
                    break;

                case 10:
                    $data['status'] = [
                        '11' => 'Kirim Surat Jawaban ke Pemohon',
                    ];
                    break;

                default:
                    $data['status'] = [
                        '2' => 'Belum Disetting',
                    ];
                    break;
            }
        } else {
            switch ($id_status) {
                case 1:
                    $data['status'] = [
                        '99' => 'Kembalikan Data Ke Pemohon (Reject)',
                        '2' => 'Lanjutkan Proses Selanjutnya'
                    ];
                    break;

                case 2:
                    $data['status'] = [
                        '99' => 'Kembalikan Data Ke Pemohon (Reject)',
                        '1' => 'Kembalikan Data Ke Petugas Arsip',
                        '3' => 'Lanjutkan Proses Ke Petugas SK'
                    ];
                    break;

                case 4:
                    $data['status'] = [
                        '2' => 'Kembalikan Data ke BAP',
                        '3' => 'Kembalikan Data Ke Petugas SK',
                        '5' => 'Lanjutkan Proses Ke Ketua Tim',
                    ];
                    break;

                case 5:
                    $data['status'] = [
                        '2' => 'Kembalikan Data ke BAP',
                        '3' => 'Kembalikan Data Ke Petugas SK',
                        '4' => 'Kembalikan Data Ke Penyelia',
                        '6' => 'Lanjutkan Proses Ke Kabid',
                    ];
                    break;

                case 6:
                    $data['status'] = [
                        '5' => 'Kembalikan Data Ke Ketua',
                        '7' => 'Lanjutkan Proses Ke Sekretaris',
                    ];
                    break;

                case 7:
                    $data['status'] = [
                        '6' => 'Kembalikan Data Ke Kabid',
                        '8' => 'Lanjutkan Proses Ke Verifikasi KA BPKAD',
                    ];
                    break;

                default:
                    $data['status'] = [
                        '2' => 'Belum Disetting',
                    ];
                    break;
            }
        }

        return $data['status'];
    }
}

<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenguranganIpt extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'ipt_pengurangan';

    public static $types = [
        'VETERAN',
        'PENSIUNAN PNS/TNI/POLRI',
        'MBR',
        '20 TAHUN',
        'RUMAH TINGGAL < 200',
        'USAHA',
        'PENDIDIKAN',
        'KESEHATAN',
        'SOSIAL',
        'KEPENTINGAN UMUM',
        'KEAGAMAAN',
        'PENUNJANG PROGRAM PEMDA',
        'RUMAH USAHA',
        'SUAMI/ISTRI/JANDA/DUDA VETERAN',
        'SUAMI/ISTRI/JANDA/DUDA PENSIUNAN',

    ];

    public static $keteranganType = [
        'VETERAN' => 'veteran pejuang kemerdekaan, veteran pembela kemerdekaan atau penerima tanda jasa bintang gerilya',
        'PENSIUNAN PNS/TNI/POLRI' => 'pensiunan Pegawai Negeri Sipil/Prajurit Tentara Nasional Indonesia/Anggota Kepolisian Negara Republik Indonesia',
        'MBR' => 'masyarakat yang berpenghasilan di bawah atau sama dengan Upah Minimum Regional (UMR) Daerah',
        '20 TAHUN' => 'pemegang Izin Pemakaian Tanah selama 20 tahun secara berturut-turut dan dalam hal terjadi pewarisan, jangka waktu 20 (dua puluh) tahun berturut-turut dihitung sejak pewaris memperoleh Izin Pemakaian Tanah untuk pertama kali',
        'RUMAH TINGGAL < 200' => 'orang pribadi pemegang Izin Pemakaian Tanah dengan luas maksimal 200 m2 (dua ratus meter persegi)',
        'USAHA' => 'bahwa pengurangan retribusi dengan penggunaan untuk kegiatan yang tidak berorientasi mencari keuntungan antara lain pelayanan kepentingan umum, kegiatan sosial, kegiatan keagamaan, kegiatan penunjang penyelenggaraan program Pemerintah Daerah, diberikan pengurangan sebesar',
        'PENDIDIKAN' => 'bahwa pengurangan retribusi dengan dengan penggunaan untuk kegiatan penyelenggaraan pendidikan nasional diberikan pengurangan sebesar',
        'KESEHATAN' => 'bahwa pengurangan retribusi dengan penggunaan untuk kegiatan pelayanan kesehatan yang terdaftar menerima layanan Badan Penyelenggara Jaminan Sosial (BPJS), diberikan pengurangan sebesar',
        'SOSIAL' => 'bahwa pengurangan retribusi dengan penggunaan untuk kegiatan yang tidak berorientasi mencari keuntungan antara lain pelayanan kepentingan umum, kegiatan sosial, kegiatan keagamaan, kegiatan penunjang penyelenggaraan program Pemerintah Daerah, diberikan pengurangan sebesar',
        'KEPENTINGAN UMUM' => 'bahwa pengurangan retribusi dengan penggunaan untuk kegiatan yang tidak berorientasi mencari keuntungan antara lain pelayanan kepentingan umum, kegiatan sosial, kegiatan keagamaan, kegiatan penunjang penyelenggaraan program Pemerintah Daerah, diberikan pengurangan sebesar',
        'KEAGAMAAN' => 'bahwa pengurangan retribusi dengan penggunaan untuk kegiatan yang tidak berorientasi mencari keuntungan antara lain pelayanan kepentingan umum, kegiatan sosial, kegiatan keagamaan, kegiatan penunjang penyelenggaraan program Pemerintah Daerah, diberikan pengurangan sebesar',
        'PENUNJANG PROGRAM PEMDA' => 'bahwa pengurangan retribusi dengan penggunaan untuk kegiatan yang tidak berorientasi mencari keuntungan antara lain pelayanan kepentingan umum, kegiatan sosial, kegiatan keagamaan, kegiatan penunjang penyelenggaraan program Pemerintah Daerah, diberikan pengurangan sebesar',
        'RUMAH USAHA' => 'bahwa pengurangan retribusi dengan penggunaan untuk kegiatan yang tidak berorientasi mencari keuntungan antara lain pelayanan kepentingan umum, kegiatan sosial, kegiatan keagamaan, kegiatan penunjang penyelenggaraan program Pemerintah Daerah, diberikan pengurangan sebesar',
        'SUAMI/ISTRI/JANDA/DUDA VETERAN' => 'suami/isteri/janda/duda veteran pejuang kemerdekaan, veteran pembela kemerdekaan atau penerima tanda jasa bintang gerilya',
        'SUAMI/ISTRI/JANDA/DUDA PENSIUNAN' => 'suami/isteri/janda/duda pensiunan Pegawai Negeri Sipil/Prajurit Tentara Nasional Indonesia/Anggota Kepolisian Negara Republik Indonesia',
    ];

    public static $potonganType = [
        'VETERAN' => 50,
        'PENSIUNAN PNS/TNI/POLRI' => 50,
        'MBR' => 50,
        '20 TAHUN' => 50,
        'RUMAH TINGGAL < 200' => 75,
        'USAHA' => 30,
        'PENDIDIKAN' => 50,
        'KESEHATAN' => 50,
        'SOSIAL' => 50,
        'KEPENTINGAN UMUM' => 50,
        'KEAGAMAAN' => 50,
        'PENUNJANG PROGRAM PEMDA' => 50,
        'RUMAH USAHA' => 50,
        'SUAMI/ISTRI/JANDA/DUDA VETERAN' => 50,
        'SUAMI/ISTRI/JANDA/DUDA PENSIUNAN' => 50,
        '' => 0,
    ];

    public function layanan()
    {
        return $this->belongsTo('Modules\Permohonan\Entities\Layanan', 'id_layanan', 'id_layanan');
    }

    public function status()
    {
        return $this->belongsTo('Modules\Permohonan\Entities\PenguranganIptStatus', 'id_status', 'id_status');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user', 'id_user');
    }

    public function histories()
    {
        return $this->hasMany('Modules\Permohonan\Entities\PermohonanHistory', 'id_permohonan', 'id_permohonan');
    }
}

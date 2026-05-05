<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TransferDetailCorrection extends Model
{
    protected $table = 'transfer_detail_correction';
    protected $fillable = [
        'transfer_detail_id',
        'old_quantity',
        'new_quantity',
        'note',
        'created_by',
    ];

    public $timestamps = false;

    public function detail()
    {
        return $this->belongsTo(TransferDetail::class, 'transfer_detail_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }
}

<?php

namespace Modules\Crm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Tier\Entities\Tier;

class CustomerTier extends Model
{
    use HasFactory;

    protected $table = 'vw_customer_tier';
    protected $fillable = [];

    public function tier()
    {
        return $this->belongsTo(Tier::class, 'tier_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
	protected $table = TB_SALES;
    protected $primaryKey = 'sale_id';
    const CREATED_AT = 'sale_created_at';
    const UPDATED_AT = 'sale_updated_at';

    protected $fillable = [
        'sale__cus_id',
        'sale__user_id',
        'sale_subtotal',
        'sale_total',
        'pro_price',
        'sale_state'
    ];
}

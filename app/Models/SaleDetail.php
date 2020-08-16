<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
	protected $table = TB_SALES_DETAIL;
    protected $primaryKey = 'det_id';
    const CREATED_AT = 'det_created_at';
    const UPDATED_AT = 'det_updated_at';

    protected $fillable = [
        'det__sale_id',
        'det__pro_id',
        'det_lot',
        'det_price',
        'det_subtotal',
        'det_state'
    ];
}

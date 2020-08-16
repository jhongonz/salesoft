<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $table = TB_PRODUCT;
    protected $primaryKey = 'pro_id';
    const CREATED_AT = 'pro_created_at';
    const UPDATED_AT = 'pro_updated_at';

    protected $fillable = [
        'pro__cate_id',
        'pro_code',
        'pro_name',
        'pro_description',
        'pro_price',
        'pro_price_cut',
        'pro_state',
        'pro_search'
    ];
}

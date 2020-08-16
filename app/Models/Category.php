<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $table = TB_CATEGORIES;
    protected $primaryKey = 'cate_id';
    const CREATED_AT = 'cate_created_at';
    const UPDATED_AT = 'cate_updated_at';

    protected $fillable = [
        'cate_name',
        'cate_state'
    ];
}

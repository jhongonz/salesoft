<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
	protected $table = TB_PRIVILEGES;
    protected $primaryKey = 'pri_id';
    const CREATED_AT = 'pri_created_at';
    const UPDATED_AT = 'pri_updated_at';

    protected $fillable = [
        'pri__pro_id',
        'pri__mod_id',
        'pri_state'
    ];
}

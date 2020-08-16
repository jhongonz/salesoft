<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
	protected $table = TB_PROFILES;
    protected $primaryKey = 'pro_id';
    const CREATED_AT = 'pro_created_at';
    const UPDATED_AT = 'pro_updated_at';

    protected $fillable = [
        'pro_name',
        'user__pro_id'
        'pro_state'
    ];
}

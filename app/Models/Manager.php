<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
	protected $table = TB_MANAGERS;
    protected $primaryKey = 'admin_id';
    const CREATED_AT = 'admin_created_at';
    const UPDATED_AT = 'admin_updated_at';

    protected $fillable = [
        'admin_identifier_type',
        'admin_identifier',
        'admin_name',
        'admin_lastname',
        'admin_address',
        'admin_phone',
        'admin_email',
        'admin_search',
        'admin_state',
    ];
}

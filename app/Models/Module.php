<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
	protected $table = TB_MODULES;
    protected $primaryKey = 'mod_id';
    const CREATED_AT = 'mod_created_at';
    const UPDATED_AT = 'mod_updated_at';

    protected $fillable = [
        'mod__mod_id',
        'mod_link',
        'mod_icon_menu',
        'mod_icon_panel',
        'mod_position',
        'mod_hidden',
        'mod_state'
    ];
}

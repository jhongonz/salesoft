<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	protected $table = TB_CUSTOMERS;
    protected $primaryKey = 'cus_id';
    const CREATED_AT = 'cus_created_at';
    const UPDATED_AT = 'cus_updated_at';

    protected $fillable = [
        'cus_document_type',
        'cus_document_number',
        'cus_name',
        'cus_lastname',
        'cus_birthdate',
        'cus_phone',
        'cus_email',
        'cus_gender',
        'cus_address',
        'cus_search',
        'cus_state',
    ];
}

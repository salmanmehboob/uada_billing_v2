<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    
     protected $fillable = [
        'name',
        'email',
        'phone_no' ,
        'address',
        'sub_charges',
        'invoice_prefix',
        'receipt_footer',
        'dept_logo',
        'govt_logo',

    ];

    public static function getSetting(): Setting
    {
        return self::first();
    }
}

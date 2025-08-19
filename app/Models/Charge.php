<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = ['name'];

//    public function charges()
//    {
//        return $this->hasMany(PlotCharges::class, 'charge_id', 'id');
//
//    }
}

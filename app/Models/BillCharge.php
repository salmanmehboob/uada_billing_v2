<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id' ,
        'charge_id',
        'from_month',
        'from_year',
        'to_month',
        'to_year',
        'total_months',
        'no_of_transfer',
        'total_violation',
        'amount',
        'total',
        'issue_date',
        'due_date',
        'reference_bill_id',
    ];

    public function referenceBill(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bill::class,'reference_bill_id');
    }

    // Canonical relation
    public function charge(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Charge::class,'charge_id')->withDefault();
    }

    // Backward-compatible aliases so $billCharge->PlotCharges or ->plotCharges still work
    public function PlotCharges(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Charge::class,'charge_id')->withDefault();
    }
 
    public function fromMonth(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Month::class,'from_month')->withDefault();
    }

    public function toMonth(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Month::class,'to_month')->withDefault();
    }
}

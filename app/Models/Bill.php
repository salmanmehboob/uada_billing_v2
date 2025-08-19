<?php

namespace App\Models;

use App\Helpers\GeneralHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'allotee_id',
        'bank_id',
        'sector_id',
        'size_id',
        'year',
        'from_month',
        'to_month',
        'total_months',
        'issue_date',
        'due_date',
        'is_paid',
        'generated_by',
         'bill_total',
        'arrears',
        'total',
        'sub_charges',
        'sub_total',
        'due_amount',
        'is_active',
        'is_generated_combine'
    ];

   

    public static function generateBillNumber()
    {
        $currentYear = date('Y');
        $lastBill = self::orderBy('id', 'desc')
            ->first();

        if ($lastBill) {
            $lastBillNumber = (int)substr($lastBill->bill_number, strlen( GeneralHelper::getSettingValue('invoice_prefix') . $currentYear . '-'));
            $nextBillNumber = $lastBillNumber + 1;
        } else {
            // If there are no bills in the current year, start with a default number
            $nextBillNumber = 1; // Change this to any starting number you prefer
        }

        return GeneralHelper::getSettingValue('invoice_prefix') . $currentYear . '-' . str_pad($nextBillNumber, 6, '0', STR_PAD_LEFT);
    }
 
    public function allotee(): BelongsTo
    {
        return $this->belongsTo(Allotee::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function fromMonth(): BelongsTo
    {
        return $this->belongsTo(Month::class, 'from_month')->withDefault();
    }

    public function toMonth()
    {
        return $this->belongsTo(Month::class, 'to_month')->withDefault();
    }

    public function billCharges()
    {
        return $this->hasMany(BillCharge::class);
    }

    public function transaction()
    {
        return $this->hasOne(BillTransaction::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by', 'id');

    }


}

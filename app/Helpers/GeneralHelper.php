<?php

namespace App\Helpers;

use App\Models\Allotee;
use App\Models\PosSetting;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GeneralHelper
{

    public static function getImageUrl($path, $default = 'default.png')
    {
        if (!$path) {
            return asset('storage/' . $default);
        }

        return Storage::disk('public')->exists($path)
            ? asset('storage/' . $path)
            : asset('storage/' . $default);
    }

    /**
     * Get a setting value from PosSetting table.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function posSetting($key, $default = null)
    {
        return Cache::remember("pos_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = PosSetting::first();
            return $setting ? ($setting->$key ?? $default) : $default;
        });
    }

    /**
     * Generate a unique slug for a given model and field.
     *
     * @param string $model
     * @param string $title
     * @param string $field
     * @return string
     */
    public static function generateSlug(string $model, string $title, string $field = 'slug'): string
    {
        $slug = Str::slug($title);
        $count = $model::where($field, 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Format currency.
     *
     * @param float $amount
     * @return string
     */
    public static function formatCurrency(float $amount): string
    {
        $currency = self::posSetting('currency', 'USD');
        return number_format($amount, 2) . " " . strtoupper($currency);
    }

    /**
     * Upload a file and return its path.
     *
     * @param mixed $file
     * @param string $folder
     * @return string|null
     */
    public static function uploadFile(mixed $file, string $folder = 'uploads'): ?string
    {
        if ($file) {
            $path = $file->store($folder, 'public');
            return 'storage/' . $path;
        }
        return null;
    }

    /**
     * Delete a file from storage.
     *
     * @param string|null $filePath
     * @return bool
     */
    public static function deleteFile($filePath): bool
    {
        if ($filePath && Storage::exists(str_replace('storage/', '', $filePath))) {
            return Storage::delete(str_replace('storage/', '', $filePath));
        }
        return false;
    }

    /**
     * Check if installation lock file exists.
     *
     * @return bool
     */
    public static function isInstalled(): bool
    {
        return File::exists(storage_path('install.lock'));
    }


    public static function showStatus($status): void
    {

        if ($status === 1) {
            echo '<span class="badge badge-success">Active</span>';

        } else {
            echo '<span class="badge badge-danger">InActive</span>';

        }

    }

    public static function showBooleanStatus($status): string
    {

        if ($status == 1) {
            return '<span class="badge badge-success">YES</span>';

        } else {
            return '<span class="badge badge-danger">NO</span>';

        }

    }


    public static function getSettingValue($val)
    {
        $settings = Setting::getSetting();
        return $settings->$val;
    }

    public static function generateBillNumber($allotteeId): string
    {

        $allottee = Allotee::find($allotteeId);
        return  self::getSettingValue('invoice_prefix') . time() . '-' . $allottee->id;

    }


    public static function currentDate()
    {
        return date('d-M-Y');
    }

    public static function currentDatePicker(): string
    {
        return date('m/d/Y');
    }

    public static function currentDateInput()
    {
        return date('mm/dd/yyyy');
    }

    public static function currentDateInsert()
    {
        return date('Y-m-d');
    }

    public static function currentDateTimeInsert()
    {
        return date('Y-m-d h:i:s');
    }


    public static function currentYear()
    {
        return date('Y');
    }

    public static function currentMonthStart()
    {
        return date('Y-m-01');
    }

    public static function currentMonthEnd()
    {
        return date('Y-m-t');
    }

    public static function currentMonth()
    {
        return date('m');
    }

    public static function dateInsert($obj)
    {
        return date('Y-m-d', strtotime($obj,));
    }

    public static function monthInsert($obj)
    {
        return date('m', strtotime($obj));
    }

    public static function yearInsert($obj)
    {
        return date('Y', strtotime($obj));
    }

    public static function showDatePicker($obj)
    {
//    return date('d/m/Y', strtotime($obj));
        return date('m/d/Y', strtotime($obj));
    }

    public static function showDate($obj)
    {
        if ($obj != '0000-00-00') {

            return date('d-M-Y', strtotime($obj));
        } else {
            return '';
        }
    }

    public static function showDateTime($obj)
    {
        return date('d-M-Y h:i:s', strtotime($obj));
    }

    public static function showMonth($obj)
    {
        return date('M', strtotime($obj));
    }


    public static function ShowFullName($firstName, $lastName)
    {
        return $firstName . ' ' . $lastName;
    }
}

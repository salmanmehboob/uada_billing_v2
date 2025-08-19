<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
use App\Http\Requests\UpdateSecurityRequest;
use App\Models\Setting;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    
    public function settings()
    {
        $settings = Setting::first();
         return view('settings.index', compact('settings'));
    }

    public function updateSettings(UpdateSettingsRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
             $settings = Setting::firstOrCreate([]);
            $settings->fill($request->validated());

            if ($request->hasFile('dept_logo')) {
                $logoPath = $request->file('dept_logo')->store('logos', 'public');
                $settings->dept_logo = $logoPath;
            }

            if ($request->hasFile('govt_logo')) {
                $logoPath = $request->file('govt_logo')->store('logos', 'public');
                $settings->govt_logo = $logoPath;
            }   

            $settings->save();

            DB::commit();

            return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update settings. ' . $e->getMessage()]);
        }
    }

    
}

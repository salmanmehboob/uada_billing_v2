<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\ChargeRequest;
use App\Models\Charge;
use Illuminate\Support\Facades\DB;
use Exception;

#[AllowDynamicProperties]
class ChargeController extends Controller
{
    protected string $route;
    protected string $viewFolder;

    public function __construct()
    {
        $this->title = 'Charge';
        $this->route = 'admin.dropdown.charges';
        $this->viewFolder = 'charges';
    }

    public function index()
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;
        $data['charges'] = Charge::all();

        return view($this->viewFolder . '.index', $data);
    }


    public function store(ChargeRequest $request): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            Charge::create($data);

            DB::commit();
            return redirect()->back()->with('success', 'Charge created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create charge: ' . $e->getMessage()]);
        }
    }


    public function edit(Charge $charge)
    {
        return view('charges.edit', compact('charge'));
    }

    public function update(ChargeRequest $request, Charge $charge): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $charge->update($data);

            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Charge updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update charge: ' . $e->getMessage()]);
        }
    }


    public function destroy(Charge $charge): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $charge->delete();
            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Charge deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete charge: ' . $e->getMessage()]);
        }
    }
}

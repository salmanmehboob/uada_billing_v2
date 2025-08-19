<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\SectorRequest;
use App\Models\Sector;
use Illuminate\Support\Facades\DB;
use Exception;

#[AllowDynamicProperties]
class SectorController extends Controller
{
    protected string $route;
    protected string $viewFolder;

    public function __construct()
    {
        $this->title = 'Sector';
        $this->route = 'admin.dropdown.sectors';
        $this->viewFolder = 'sectors';
    }

    public function index()
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;
        $data['sectors'] = Sector::all();

        return view($this->viewFolder . '.index', $data);
    }


    public function store(SectorRequest $request): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            Sector::create($data);

            DB::commit();
            return redirect()->back()->with('success', 'Sector created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create sector: ' . $e->getMessage()]);
        }
    }


    public function edit(Sector $sector)
    {
        return view('sectors.edit', compact('sector'));
    }

    public function update(SectorRequest $request, Sector $sector): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $sector->update($data);

            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Sector updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update sector: ' . $e->getMessage()]);
        }
    }


    public function destroy(Sector $sector): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $sector->delete();
            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Sector deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete sector: ' . $e->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\SizeRequest;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use Exception;

#[AllowDynamicProperties]
class SizeController extends Controller
{
    protected string $route;
    protected string $viewFolder;

    public function __construct()
    {
        $this->title = 'Size';
        $this->route = 'admin.dropdown.sizes';
        $this->viewFolder = 'sizes';
    }

    public function index()
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;
        $data['sizes'] = Size::all();

        return view($this->viewFolder . '.index', $data);
    }


    public function store(SizeRequest $request): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            Size::create($data);

            DB::commit();
            return redirect()->back()->with('success', 'Size created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create size: ' . $e->getMessage()]);
        }
    }


    public function edit(Size $size)
    {
        return view('sizes.edit', compact('size'));
    }

    public function update(SizeRequest $request, Size $size): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $size->update($data);

            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Size updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update size: ' . $e->getMessage()]);
        }
    }


    public function destroy(Size $size): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $size->delete();
            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Size deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete size: ' . $e->getMessage()]);
        }
    }
}

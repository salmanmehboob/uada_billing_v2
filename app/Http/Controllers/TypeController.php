<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\TypeRequest;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Exception;

#[AllowDynamicProperties]
class TypeController extends Controller
{
    protected string $route;
    protected string $viewFolder;

    public function __construct()
    {
        $this->title = 'Type';
        $this->route = 'admin.dropdown.types';
        $this->viewFolder = 'types';
    }

    public function index()
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;
        $data['types'] = Type::all();

        return view($this->viewFolder . '.index', $data);
    }


    public function store(TypeRequest $request): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            Type::create($data);

            DB::commit();
            return redirect()->back()->with('success', 'Type created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create type: ' . $e->getMessage()]);
        }
    }


    public function edit(Type $type)
    {
        return view('types.edit', compact('type'));
    }

    public function update(TypeRequest $request, Type $type): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $type->update($data);

            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Type updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update type: ' . $e->getMessage()]);
        }
    }


    public function destroy(Type $type): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $type->delete();
            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Type deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete type: ' . $e->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\BankRequest;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;
use Exception;

#[AllowDynamicProperties]
class BankController extends Controller
{
    protected string $route;
    protected string $viewFolder;

    public function __construct()
    {
        $this->title = 'Bank';
        $this->route = 'admin.dropdown.banks';
        $this->viewFolder = 'banks';
    }

    public function index()
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;
        $data['banks'] = Bank::all();

        return view($this->viewFolder . '.index', $data);
    }


    public function store(BankRequest $request): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            Bank::create($data);

            DB::commit();
            return redirect()->back()->with('success', 'Bank created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create bank: ' . $e->getMessage()]);
        }
    }


    public function edit(Bank $bank)
    {
        return view('banks.edit', compact('bank'));
    }

    public function update(BankRequest $request, Bank $bank): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $bank->update($data);

            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Bank updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update bank: ' . $e->getMessage()]);
        }
    }


    public function destroy(Bank $bank): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $bank->delete();
            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Bank deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete bank: ' . $e->getMessage()]);
        }
    }
}

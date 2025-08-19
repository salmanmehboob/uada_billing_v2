<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    protected $route;
    protected $viewFolder;

    public function __construct()
    {
        $this->route = 'admin.users';
        $this->viewFolder = 'users';
    }

    public function index(Request $request)
    {
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;
        $data['users'] = User::with('roles')->get(); // Eager load roles
        $data['roles'] = Role::all();

        return view($this->viewFolder . '.index', $data);
    }


    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create($request->validated());
            if ($request->filled('role_id')) {
                $role = Role::findById($request->input('role_id'));

                if ($role) {
                    $user->assignRole($role);

                } else {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Role not found.');
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'User created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create user: ' . $e->getMessage()]);
        }
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();
            $user->update($request->validated());

              if ($request->filled('password')) {

                $validatedData['password'] = ($request->input('password'));
            }


             if ($request->has('role_id')) {
                $role = Role::find($request->input('role_id'));

                if ($role) {
                    $user->syncRoles($role);
                } else {
                    DB::rollBack();
                    return redirect()->route($this->route . '.index')->with('error', 'Role not found.');
                }
            }

            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'User updated successfully.');
        } catch (Exception $e) {
             DB::rollBack();
            return redirect()->route($this->route . '.index')->with('error', 'Failed to update user: ' . $e->getMessage());

         }
    }

    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete user: ' . $e->getMessage()]);
        }
    }
}

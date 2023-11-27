<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::has('roles')->orderBy('name')->get();
        return(view('admin.index', compact('users')));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return(view('admin.create', compact('roles')));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:100'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', Password::min(8)->numbers()],
            'roles' => ['required', 'array', 'min:1']
        ]);

        $newAdmin = new User();

        $newAdmin->name = $request->name;
        $newAdmin->email = $request->email;
        $newAdmin->password = Hash::make($request->password);

        $newAdmin->save();

        $newAdmin->roles()->sync($request->roles);

        // redirect back to the main view for this controller
        return redirect(route('admin.index'))->with('status', 'Admin user added');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user_id)
    {
        $user = User::find($user_id);
        $roles = Role::orderBy('name')->get();
        return(view('admin.edit', compact( 'user', 'roles')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $request->validate([
            'name' => ['required', 'max:100'],
            'email' => ['required', 'email', `unique:users,email,{$user->id}`],
            'password' => ['nullable', Password::min(8)->numbers()],
            'roles' => ['required', 'array', 'min:1']
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $user->roles()->sync($request->roles);

        // redirect back to the main view for this controller
        return redirect(route('admin.index'))->with('status', 'Admin user updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        $user = User::find($user_id);
        $user->delete();

        $user->deleted_by = Auth::id();
        $user->save();

        // redirect back to the main view for this controller
        return redirect(route('admin.index'))->with('status', 'Admin user deleted');
    }
}

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
        // get all users who have roles in alphabetical order
        // return that data with the index view
        $users = User::has('roles')->orderBy('name')->get();
        return(view('admin.index', compact('users')));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // get the roles list (for the checkboxes)
        // return roles data with create view
        $roles = Role::orderBy('name')->get();
        return(view('admin.create', compact('roles')));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validator function will not allow the request to continue if it fails the checks
        $request->validate([
            'name' => ['required', 'max:100'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', Password::min(8)->numbers()],
            'roles' => ['required', 'array', 'min:1']
        ]);

        // generate a user instance based on the model
        $newAdmin = new User();

        // feed the data from the request into the user
        $newAdmin->name = $request->name;
        $newAdmin->email = $request->email;
        $newAdmin->password = Hash::make($request->password);

        // save it to the database
        $newAdmin->save();

        // sync the roles
        $newAdmin->roles()->sync($request->roles);

        // redirect back to the main view for this controller
        return redirect(route('admin.index'))->with('status', 'Admin user added');
    }

    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        // since users don't have more info than is displayed on index
        // i reused the index view for show
        // that view is expecting an array of users, but it can be an array of 1 item
        $users = [User::find($user_id)];
        return(view('admin.index', compact('users')));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user_id)
    {
        // i used "admin" instead of "user" because there could be a controller for end-users
        // since it's named unconventionally, it doesn't pass in the id the same way
        // so i need to manually find the user by id
        $user = User::find($user_id);

        // get the roles
        $roles = Role::orderBy('name')->get();

        // send the edit view with user and role info
        return(view('admin.edit', compact( 'user', 'roles')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id)
    {
        // find user by id
        $user = User::find($user_id);

        // validate the input
        // differences between store and update:
        // * email is unique with an exception for the currently edited row
        // * password is nullable (since we can't and shouldn't pre-populate it)
        $request->validate([
            'name' => ['required', 'max:100'],
            'email' => ['required', 'email', `unique:users,email,{$user->id}`],
            'password' => ['nullable', Password::min(8)->numbers()],
            'roles' => ['required', 'array', 'min:1']
        ]);

        // once the input has passed validation, update the object
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // save the changes
        $user->save();

        // sync the roles
        // this will delete previous roles before filling in currently selected roles
        $user->roles()->sync($request->roles);

        // redirect back to the main view for this controller
        return redirect(route('admin.index'))->with('status', 'Admin user updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        // find the user and delete them
        // soft deletes means this only adds a timestamp to the deleted at column
        $user = User::find($user_id);
        $user->delete();

        // even though the user is "deleted" we can still modify their information
        // in this case to say who deleted them
        $user->deleted_by = Auth::id();

        // remember to save our changes
        $user->save();

        // redirect back to the main view for this controller
        return redirect(route('admin.index'))->with('status', 'Admin user deleted');
    }
}

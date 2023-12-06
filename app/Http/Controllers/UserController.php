<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ? $request->per_page : 10;
        $users = User::with('role')->paginate($perPage);

        return Inertia::render('User/Index', [
            'data' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roleOptions = $this->getRoleOption();
        return Inertia::render('User/CreateEdit', [
            'roleOptions' => $roleOptions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        event(new Registered($user));

        return Redirect::route('user.index');
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
    public function edit(string $id)
    {
        $user = User::find($id);

        $roleOptions = $this->getRoleOption();

        return Inertia::render('User/CreateEdit', [
            'data' => $user,
            'roleOptions' => $roleOptions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255',
            'password' => 'confirmed',
            'role_id' => 'required',
        ];

        if (isset($request->password)) {
          $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        $user = User::find($id);

        $user->name = $request->name;
        $user->email = $request->email;
        if (isset($request->password)) {
          $user->password = Hash::make($request->password);
        }
        $user->role_id = $request->role_id;

        $user->save();

        return Redirect::route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        $user->delete();

        return Redirect::route('user.index');
    }

    private function getRoleOption()
    {
        return collect(Role::all())->map(function ($role) {
            return [
                'label' => $role->name,
                'value' => $role->id
            ];
        });
    }
}

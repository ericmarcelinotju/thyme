<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ? $request->per_page : 10;
        $roles = Role::paginate($perPage);

        return Inertia::render('Role/Index', [
            'data' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Role/CreateEdit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return Redirect::route('role.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);
        return Inertia::render('Role/CreateEdit', [
            'data' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
        ]);

        $role = Role::find($id);

        $role->name = $request->name;

        $role->save();

        return Redirect::route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        $role->delete();

        return Redirect::route('role.index');
    }
}

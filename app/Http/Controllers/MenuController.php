<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Inertia\Inertia;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::orderBy('sequence', 'asc')->paginate(10);

        return Inertia::render('Menu/Index', [
            'data' => $menus
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Menu/CreateEdit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'label' => 'required',
            'route' => 'required',
        ]);

        Menu::create([
            'name' => $request->name,
            'label' => $request->label,
            'icon' => $request->icon,
            'route' => $request->route,
        ]);

        return Redirect::route('menu.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menu = Menu::find($id);
        return Inertia::render('Menu/CreateEdit', [
            'data' => $menu
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'  => 'required',
            'label' => 'required',
            'route' => 'required',
        ]);

        $menu = Menu::find($id);

        $menu->name = $request->name;
        $menu->label = $request->label;
        $menu->icon = $request->icon;
        $menu->route = $request->route;

        $menu->save();

        return Redirect::route('menu.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::find($id);

        $menu->delete();

        return Redirect::route('menu.index');
    }
}

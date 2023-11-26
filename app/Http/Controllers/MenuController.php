<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

class MenuController extends Controller
{
    private $typeOption = [
        [
            'label' => 'Link',
            'value' => 'link'
        ],
        [
            'label' => 'Title',
            'value' => 'title'
        ],
        [
            'label' => 'Dropdown',
            'value' => 'dropdown'
        ]
    ];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ? $request->per_page : 10;
        $menus = Menu::orderBy('sequence', 'asc')->paginate($perPage);

        return Inertia::render('Menu/Index', [
            'data' => $menus
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $routeOptions = $this->getRouteOption();

        return Inertia::render('Menu/CreateEdit', [
            'typeOptions' => $this->typeOption,
            'routeOptions' => $routeOptions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'type' => 'required',
            'route' => 'required',
        ]);

        Menu::create([
            'name' => $request->name,
            'label' => $request->label,
            'type' => $request->type,
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

        $routeOptions = $this->getRouteOption();

        return Inertia::render('Menu/CreateEdit', [
            'data' => $menu,
            'typeOptions' => $this->typeOption,
            'routeOptions' => $routeOptions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'type' => 'required',
            'route' => 'required',
        ]);

        $menu = Menu::find($id);

        $menu->name = $request->name;
        $menu->label = $request->label;
        $menu->type = $request->type;
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

    private function getRouteOption()
    {
        $routeCollection = Route::getRoutes();
        return collect($routeCollection)
            ->filter(function ($route) {
                return in_array("GET", $route->methods()) && !empty($route->getName());
            })
            ->map(function ($route) {
                return [
                    'label' => $route->getName(),
                    'value' => $route->getName(),
                ];
            })
            ->toArray();
    }
}

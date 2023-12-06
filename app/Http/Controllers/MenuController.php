<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Role;
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
        $menus = Menu::with('children')->whereNull('parent_id')->orderBy('sequence', 'asc')->get();

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
        $parentOptions = $this->getParentOption();
        $roleOptions = $this->getRoleOption();

        return Inertia::render('Menu/CreateEdit', [
            'typeOptions'   => $this->typeOption,
            'routeOptions'  => $routeOptions,
            'parentOptions' => $parentOptions,
            'roleOptions'   => $roleOptions
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
            'type'  => 'required|string|max:255',
            'route' => 'max:255',
        ]);

        $menu = Menu::create([
            'name'     => $request->name,
            'label'    => $request->label,
            'type'     => $request->type,
            'icon'     => $request->icon,
            'route'    => $request->route,
            'sequence' => $this->getNextSequence(),
            'parent_id'=> $request->parent_id
        ]);

        if (isset($request->roles)) {
          $menu->roles()->sync($request->roles);
        }

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
        $menu = Menu::with('roles')->find($id);

        $routeOptions = $this->getRouteOption();
        $parentOptions = $this->getParentOption();
        $roleOptions = $this->getRoleOption();

        return Inertia::render('Menu/CreateEdit', [
            'data'          => $menu,
            'typeOptions'   => $this->typeOption,
            'routeOptions'  => $routeOptions,
            'parentOptions' => $parentOptions,
            'roleOptions'   => $roleOptions
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
            'type'  => 'required|string|max:255',
            'route' => 'max:255',
        ]);

        $menu = Menu::find($id);

        $menu->name = $request->name;
        $menu->label = $request->label;
        $menu->type = $request->type;
        $menu->icon = $request->icon;
        $menu->route = $request->route;
        $menu->parent_id = $request->parent_id;

        if (isset($request->roles)) {
          $menu->roles()->sync($request->roles);
        }

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

    public function moveUp(string $id)
    {
        $element = Menu::where('id', '=', $id)->first();
        $switchElement = Menu::where('sequence', '<', $element->sequence)
            ->orderBy('sequence', 'desc')->first();
        if (!empty($switchElement)) {
            $temp = $element->sequence;
            $element->sequence = $switchElement->sequence;
            $switchElement->sequence = $temp;
            $element->save();
            $switchElement->save();
        }
        return Redirect::route('menu.index');
    }

    public function moveDown(string $id)
    {
        $element = Menu::where('id', '=', $id)->first();
        $switchElement = Menu::where('sequence', '>', $element->sequence)
            ->orderBy('sequence', 'asc')->first();
        if (!empty($switchElement)) {
            $temp = $element->sequence;
            $element->sequence = $switchElement->sequence;
            $switchElement->sequence = $temp;
            $element->save();
            $switchElement->save();
        }
        return Redirect::route('menu.index');
    }

    public function getNextSequence()
    {
        $result = Menu::select('sequence')->orderBy('sequence', 'desc')->first();
        if (empty($result)) {
            $result = 1;
        } else {
            $result = $result->sequence + 1;
        }
        return $result;
    }

    private function getParentOption()
    {
        return collect(Menu::where('type', 'dropdown')->get())->map(function ($menu) {
            return [
                'label' => $menu->name,
                'value' => $menu->id
            ];
        });
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

<?php

namespace App\Http\Controllers\Content;

use App\Models\Asset;
use App\Models\Asset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perAsset = $request->per_page ? $request->per_page : 10;
        $assets = Asset::with('assets')->paginate($perAsset);

        return Inertia::render('Asset/Index', [
            'data' => $assets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Asset/CreateEdit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255'
        ]);

        $asset = Asset::create([
            'type' => $request->type,
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return Redirect::route('asset.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $asset = Asset::find($id);

        return Inertia::render('Asset/CreateEdit', [
            'data' => $asset,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
          'type' => 'required|string|max:255',
          'name' => 'required|string|max:255',
          'title' => 'required|string|max:255',
        ]);

        $asset = Asset::find($id);

        $asset->type = $request->type;
        $asset->name = $request->name;
        $asset->title = $request->title;
        $asset->description = $request->description;

        $asset->save();

        return Redirect::route('asset.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $asset = Asset::find($id);

        $asset->delete();

        return Redirect::route('asset.index');
    }
}

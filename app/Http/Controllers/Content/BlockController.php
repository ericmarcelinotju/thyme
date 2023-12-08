<?php

namespace App\Http\Controllers\Content;

use App\Models\Block;
use App\Models\Asset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perBlock = $request->per_page ? $request->per_page : 10;
        $blocks = Block::with('blocks')->paginate($perBlock);

        return Inertia::render('Block/Index', [
            'data' => $blocks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assetOptions = $this->getAssetOption();
        return Inertia::render('Block/CreateEdit', [
            'assetOptions' => $assetOptions
        ]);
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

        $block = Block::create([
            'type' => $request->type,
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if (isset($request->assets)) {
          $block->assets()->sync($request->assets);
        }

        return Redirect::route('block.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Block $block)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $block = Block::find($id);

        $assetOptions = $this->getAssetOption();

        return Inertia::render('Block/CreateEdit', [
            'data' => $block,
            'assetOptions' => $assetOptions
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

        $block = Block::find($id);

        $block->type = $request->type;
        $block->name = $request->name;
        $block->title = $request->title;
        $block->description = $request->description;

        if (isset($request->assets)) {
          $block->assets()->sync($request->assets);
        }

        $block->save();

        return Redirect::route('block.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $block = Block::find($id);

        $block->delete();

        return Redirect::route('block.index');
    }

    private function getAssetOption()
    {
        return collect(Asset::all())->map(function ($asset) {
            return [
                'label' => $asset->name,
                'value' => $asset->id
            ];
        });
    }
}

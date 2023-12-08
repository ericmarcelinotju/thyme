<?php

namespace App\Http\Controllers\Content;

use App\Models\Page;
use App\Models\Block;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ? $request->per_page : 10;
        $pages = Page::with('blocks')->paginate($perPage);

        return Inertia::render('Page/Index', [
            'data' => $pages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $blockOptions = $this->getBlockOption();
        return Inertia::render('Page/CreateEdit', [
            'blockOptions' => $blockOptions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
        ]);

        $page = Page::create([
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => $request->keywords,
        ]);

        if (isset($request->blocks)) {
          $page->blocks()->sync($request->blocks);
        }

        return Redirect::route('page.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = Page::find($id);

        $blockOptions = $this->getBlockOption();

        return Inertia::render('Page/CreateEdit', [
            'data' => $page,
            'blockOptions' => $blockOptions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
          'name' => 'required|string|max:255',
          'title' => 'required|string|max:255',
        ]);

        $page = Page::find($id);

        $page->name = $request->name;
        $page->title = $request->title;
        $page->description = $request->description;
        $page->keywords = $request->keywords;

        if (isset($request->blocks)) {
          $page->blocks()->sync($request->blocks);
        }

        $page->save();

        return Redirect::route('page.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = Page::find($id);

        $page->delete();

        return Redirect::route('page.index');
    }

    private function getBlockOption()
    {
        return collect(Block::all())->map(function ($block) {
            return [
                'label' => $block->name,
                'value' => $block->id
            ];
        });
    }
}

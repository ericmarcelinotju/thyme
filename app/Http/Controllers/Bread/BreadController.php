<?php

namespace App\Http\Controllers\Bread;

use Illuminate\Http\Request;
use App\Enums\FieldType;
use App\Models\Bread;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class BreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ? $request->per_page : 10;
        $breads = Bread::paginate($perPage);

        return Inertia::render('Bread/Index', [
            'data' => $breads
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fieldTypeOptions = $this->getFieldTypeOption();
        $relationOptions = $this->getRelationOption();

        return Inertia::render('Bread/CreateEdit', [
            'fieldTypeOptions' => $fieldTypeOptions,
            'relationOptions' => $relationOptions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'table_name' => 'required|string|max:255',
            'columns'  => 'required',
        ]);

        DB::beginTransaction();

        try {
          $bread = Bread::create([
              'name'              => $request->name,
              'table_name'        => $request->table_name,
              'is_allow_browse'   => $request->is_allow_browse,
              'is_allow_read'     => $request->is_allow_read,
              'is_allow_edit'     => $request->is_allow_edit,
              'is_allow_add'      => $request->is_allow_add,
              'is_allow_delete'   => $request->is_allow_delete,
              'columns'           => json_encode($request->columns)
          ]);

          Schema::create($request->table_name, function (Blueprint $table) use ($request) {
            $table->id();
            foreach ($request->columns as $column) {
              if ($column['field_type'] == FieldType::RelationSelect || $column['field_type'] == FieldType::RelationRadio) {
                $bread = Bread::find($column['relation_id']);
                $table->foreignId($bread->table_name.'_id')
                  ->constrained()
                  ->onDelete('cascade');
              } else {
                $table->string($column['name']);
              }
            }
            $table->timestamps();
          });
        } catch (Exception $e) {
          DB::rollBack();
          throw $e;
        }

        DB::commit();

        return Redirect::route('bread.index');
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
        $bread = Bread::find($id);

        $fieldTypeOptions = $this->getFieldTypeOption();
        $relationOptions = $this->getRelationOption();

        return Inertia::render('Bread/CreateEdit', [
            'data'             => $bread,
            'fieldTypeOptions' => $fieldTypeOptions,
            'relationOptions'  => $relationOptions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
          'name'        => 'required|string|max:255',
          'table_name'  => 'required|string|max:255',
          'columns'     => 'required',
        ]);

        $bread = Bread::find($id);

        $bread->name            = $request->name;
        $bread->table_name      = $request->table_name;
        $bread->is_allow_browse = $request->is_allow_browse;
        $bread->is_allow_read   = $request->is_allow_read;
        $bread->is_allow_edit   = $request->is_allow_edit;
        $bread->is_allow_add    = $request->is_allow_add;
        $bread->is_allow_delete = $request->is_allow_delete;
        $bread->columns         = json_encode($request->columns);

        $bread->save();

        return Redirect::route('bread.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bread = Bread::find($id);

        $bread->delete();

        // Delete table

        return Redirect::route('bread.index');
    }

    private function getFieldTypeOption()
    {
        return collect(FieldType::getConstants())->map(function ($fieldType) {
            return [
                'label' => Str::title(str_replace('_', ' ', $fieldType)),
                'value' => $fieldType
            ];
        });
    }

    private function getRelationOption()
    {
        return collect(Bread::all())->map(function ($bread) {
            return [
                'label' => $bread->name,
                'value' => $bread->id,
                'object' => $bread
            ];
        });
    }
}

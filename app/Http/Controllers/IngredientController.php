<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Http\Requests\StoreIngredientRequest;
use App\Http\Requests\UpdateIngredientRequest;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;


class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ingredients = Ingredient::query()
            ->when($request->query('search'), function ($query) use ($request) {
                return $query
                    ->where(function ($query) use ($request) {
                        return $query->where('name', 'LIKE', "%" . $request->query('search') . "%")->orWhere('description', 'LIKE', "%" . $request->query('search') . "%");
                    });
            })
            ->paginate(10);
        return view('ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('ingredients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIngredientRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = str($validated['name'])->slug();
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        Ingredient::create($validated);
        return redirect()->route('ingredients.index')->with('success', 'Ingredient created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredient $ingredient)
    {
        return view('ingredients.modal.edit', compact('ingredient'));
    }
    public function stockEdit(Ingredient $ingredient)
    {
        return view('ingredients.modal.stockEdit', compact($ingredient));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIngredientRequest $request, Ingredient $ingredient)
    {
        $validated = $request->validated();
        if (isset($validated['name'])) {
            $validated['slug'] = str($validated['name'])->slug();
        }
        $validated['updated_at'] = now();
        $ingredient->update($validated);
        return redirect()->route('ingredients.index')->with('success', 'Ingredient updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {

        // Check if the ingredient is used in any menu
        if ($ingredient->menus()->exists()) {
            Alert::error('Error', 'Ingredient cannot be deleted as it is associated with a menu.');
            return redirect()->back();
        } else {
            $ingredient->delete();
            Alert::success('Success', 'Ingredient deleted successfully.');
            return redirect()->route('ingredients.index')->with('success', 'Ingredient deleted successfully.');
        }
    }
}

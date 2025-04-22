<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Http\Requests\StoreMenuCategoryRequest;
use App\Http\Requests\UpdateMenuCategoryRequest;
use App\Models\Menu;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menuCategories = MenuCategory::query()
            ->when(
                request('search'),
                fn($query) =>
                $query->where('title', 'LIKE', "%" . request('search') . "%")
            )->paginate(10);
        return view('menu.category.index', compact('menuCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menu.category.model.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuCategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = str($validated['title'])->slug();
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        MenuCategory::create($validated);
        return redirect()->route('menuCategories.index')->with('success', 'Menu category created successfully.');
    }


    public function show(MenuCategory $menuCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuCategory $menuCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuCategoryRequest $request, MenuCategory $menuCategory)
    {
        $validated = $request->validated();
        $validated['slug'] = str($validated['title'])->slug();
        $validated['updated_at'] = now();

        $menuCategory->update($validated);
        return redirect()->route('menuCategories.index')->with('success', 'Menu category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuCategory $menuCategory)
    {
        $menuCategory->delete();
        return redirect()->route('menuCategories.index')->with('success', 'Menu category deleted successfully.');
    }
}

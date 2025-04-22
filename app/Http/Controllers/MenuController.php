<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $menus = Menu::with('category')
            ->when($request->query('search'), function ($query) use ($request) {
                return $query->where('name', 'LIKE', "%" . $request->query('search') . "%");
            })
            ->when($request->query('categories'), function ($query) use ($request) {
                return $query->where('category_id', $request->query('categories'));
            })->paginate(12);
        $categories = MenuCategory::all();
        return view('menu.item.index', compact(['menus', 'categories']));
    }
    public function indexByCat($category_id)
    {
        $menus = Menu::with('category')
            ->where('category_id', $category_id)
            ->paginate(12);
        $categories = MenuCategory::all();
        return view('menu.item.index', compact(['menus', 'categories']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = MenuCategory::all();
        return view('menu.item.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        $validatedData = $request->validated();

        // Convert price from string to integer
        $validatedData['price'] = (int) $validatedData['price'];
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/menu'), $imageName);
            $validatedData['image'] = 'images/menu/' . $imageName;
        }

        Menu::create($validatedData);

        Alert::success('Success', 'Menu created successfully.');
        return redirect()->route('menus.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $categories = MenuCategory::all();
        return view('menu.item.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $validatedData = $request->validated();
        $validatedData['updated_at'] = now();
        $menu->update($validatedData);
        return view(route('menus.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        Alert::success('Menu deleted successfully.');
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use function PHPSTORM_META\map;

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

    public function getMenuIngredients()
    {
        $menus = Menu::with('ingredients')->get();
        $menuIngredients = [];
        foreach ($menus as $menu) {
            $menuIngredients[$menu->id] = $menu->ingredients->map(function ($ingredient) {
                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'stock' => $ingredient->stock,
                    'pivot_quantity' => $ingredient->pivot->quantity

                ];
            });
        }
        $ingredientStocks = [];
        $ingredients = Ingredient::select('id', 'name', 'stock')->get();
        foreach ($ingredients as $ingredient) {
            $ingredientStocks[$ingredient->id] = [
                'name' => $ingredient->name,
                'stock' => $ingredient->stock
            ];
        }
        return response()->json([
            'menuIngredients' => $menuIngredients,
            'ingredientStocks' => $ingredientStocks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = MenuCategory::all();
        $ingredients = Ingredient::all();
        return view('menu.item.create', compact(['categories', 'ingredients']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        $validatedData = $request->validated();

        // Convert price from string to integer
        $validatedData['price'] = (int) str_replace('.', '', $validatedData['price']);
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/menu'), $imageName);
            $validatedData['image'] = 'images/menu/' . $imageName;
        }

        $menu = Menu::create($validatedData);

        // Handle ingredients
        if ($request->has('ingredients')) {
            $pivotData = [];
            foreach ($request->input('ingredients') as $ingredientId => $data) {
                if (!empty($data['quantity'])) {
                    $pivotData[$ingredientId] = [
                        'quantity' => $data['quantity']
                    ];
                }
            }
            if (!empty($pivotData)) {
                $menu->ingredients()->attach($pivotData);
            }
        }

        Alert::success('Success', 'Menu created successfully.');
        return redirect()->route('menus.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)

    {
        $menu->load(['ingredients', 'category']);
        return view('menu.item.modal.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $menu->load('ingredients');
        $categories = MenuCategory::all();
        $ingredients = Ingredient::all();
        $selectedIngredients = $menu->ingredients->mapWithKeys(function ($item) {
            return [$item->id => $item->pivot->quantity];
        });
        return view('menu.item.edit', compact('menu', ["menu", 'categories', 'ingredients', 'selectedIngredients']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $validatedData = $request->validated();
        $validatedData['updated_at'] = now();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/menu'), $imageName);
            $validatedData['image'] = 'images/menu/' . $imageName;
        }

        $menu->update($validatedData);

        // Fix: handle ingredient quantities correctly
        $pivotData = [];
        dd($request->all());
        if ($request->has('ingredients')) {
            foreach ($request->input('ingredients') as $ingredientId => $ingredientData) {
                if (isset($ingredientData['selected']) && isset($ingredientData['quantity'])) {
                    $pivotData[$ingredientId] = [
                        'quantity' => $ingredientData['quantity'],
                    ];
                }
            }
        }
        $menu->ingredients()->sync($pivotData);

        // Fix: redirect to menus.index instead of returning a view
        Alert::success('Success', 'Menu updated successfully.');
        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
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

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Ingredient;
use App\Models\Table;
use App\Models\Menu;
use RealRashid\SweetAlert\Facades\Alert;

use function PHPUnit\Framework\returnSelf;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all orders from the database
        $orders = Order::with(relations: ['table'])->paginate(10);
        return view('order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tables = Table::all();
        $menus = Menu::all();
        return (view('order.create', compact(['tables', 'menus'])));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreorderRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();
        $validatedData['order_status_id'] = 1;
        $validatedData['payment_method'] = 'cash';
        $validatedData['payment_status'] = 'pending';
        $order = Order::create($validatedData);
        if ($request->has('menus')) {
            $pivotData = [];
            foreach ($request->input('menus') as $menuId => $data) {
                if (!empty($data['quantity'])) {
                    $pivotData[$menuId] = [
                        'quantity' => $data['quantity'],

                    ];
                }
            }
            if (!empty($pivotData)) {
                $order->menus()->attach($pivotData);
            }
        }
        Alert::success('Success', 'Order Created Succesfully.');
        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
        return view('order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load('menus');
        $selectedMenus = $order->menus->mapWithKeys(function ($item) {
            return [$item->id => $item->pivot->quantity];
        });
        $tables = Table::all();
        $menus = Menu::all();
        return view('order.edit', compact(['tables', 'menus', 'order', 'selectedMenus']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validatedData = $request->validated();
        $validatedData['updated_at'] = now();
        $order->update($validatedData);
        $pivotData = [];
        // dd($request->all());
        if ($request->has('menus')) {
            foreach ($request->input('menus') as $menuId => $menuData) {
                if (isset($menuData['quantity'])) {
                    $pivotData[$menuId] = [
                        'quantity' => $menuData['quantity'],
                    ];
                }
            }
        }
        $order->menus()->sync($pivotData);
        Alert::success('Success', 'Order Updated Successfully');
        return redirect()->route('orders.index')->with('success', 'Order Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Delete the order from the database
        $order->delete();

        // Redirect back to the orders index with a success message
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}

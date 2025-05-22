<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\TableStatus;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use function Pest\Laravel\json;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tables = Table::with('tableStatus')
            ->when($request->query('search'), function ($query) use ($request) {
                return $query->where('number', 'LIKE', "%" . $request->query('search') . "%");
            })
            ->when($request->query('status'), function ($query) use ($request) {
                return $query->where('status_id', $request->query('status'));
            })
            ->when($request->query('start_date'), function ($query) use ($request) {
                return $query->whereDate('created_at', '>=', $request->query('start_date'));
            })
            ->when($request->query('end_date'), function ($query) use ($request) {
                return $query->whereDate('created_at', '<=', $request->query('end_date'));
            })
            ->paginate(10);;

        $statuses = (new Table())->getStatusName();
        return view('table.index', data: compact('tables', 'statuses'));
    }

    public function homePageTableIndex()
    {
        return view('homepage.DiningTables.index');
    }
    public function getAllTables()
    {
        $tables = Table::with(['tableStatus', 'tableOrders'])->get();
        return response()->json([
            'tables' => $tables,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = (new Table())->getStatusName();
        return view('table.modal.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'number' => 'required|string|unique:tables,number',
        //     'capacity' => 'required|integer|min:2|max:8',
        //     'status_id' => 'required|exists:table_statuses,id'
        // ]);
        $check = Table::where('number', $request->number)->exists();
        if (!$check) {
            Table::create([
                'number' => $request->number,
                'capacity' => $request->capacity,
                'status_id' => $request->status_id
            ]);
            Alert::success('Success', 'Table number already exists.');
            return redirect()->route('tables.index');
        } else {
            Alert::error('Error', 'Table number already exists.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $table = Table::findOrFail($id);
        $statuses = (new Table())->getStatusName();
        return view('table.modal.edit', compact('table', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Table $table)
    {
        $validated = $request->validate([
            'number' => 'required|string|unique:tables,number,' . $table->id,
            'capacity' => 'required|integer|min:2|max:8',
            'status_id' => 'required|exists:table_statuses,id'
        ]);

        $table->update($validated);

        return redirect()
            ->route('tables.index')
            ->with('success', 'Table updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        $table->delete();
        Alert::success('Table deleted successfully.');
        return redirect()->route('tables.index')->with('success', 'Table deleted successfully.');
    }
}

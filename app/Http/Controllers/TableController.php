<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\TableStatus;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::with('tableStatus')->paginate();
        $statuses = (new Table())->getStatusName();
        return view('table.index', data: compact('tables', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(Table $table)
    {
        $statuses = (new Table())->getStatusName();
        return view('table.edit', compact('table', 'statuses'));
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
        return redirect()->route('tables.index')->with('success', 'Table deleted successfully.');
    }
}

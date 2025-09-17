<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{

    /**
     * Display a listing of the divisions.
     */
    public function index()
    {
        $divisions = Division::all();
        return view('admin.divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new division.
     */
    public function create()
    {
        return view('admin.divisions.create');
    }

    /**
     * Store a newly created division in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisions,nama_divisi',
        ]);

        Division::create([
            'nama_divisi' => $request->nama_divisi,
        ]);

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified division.
     */
    public function edit(Division $division)
    {
        return view('admin.divisions.edit', compact('division'));
    }

    /**
     * Update the specified division in storage.
     */
    public function update(Request $request, Division $division)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisions,nama_divisi,' . $division->id,
        ]);

        $division->update([
            'nama_divisi' => $request->nama_divisi,
        ]);

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Remove the specified division from storage.
     */
    public function destroy(Division $division)
    {
        $division->delete();

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi berhasil dihapus.');
    }
}

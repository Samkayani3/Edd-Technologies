<?php

namespace App\Http\Controllers;

use App\Models\SparePartSupplier;
use Illuminate\Http\Request;

class SparePartSupplierController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        SparePartSupplier::create($validatedData);

        return redirect('/admin/dashboard');
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
    ]);

    $supplier = SparePartSupplier::findOrFail($id);
    $supplier->update($validated);

    return redirect('/admin/dashboard');
    // return redirect()->route('admin.manage-suppliers')->with('success', 'Supplier updated successfully.');
}

public function destroy($id)
{
    $supplier = SparePartSupplier::findOrFail($id);
    $supplier->delete();
    return redirect('/admin/dashboard');
    // return redirect()->route('admin.manage-suppliers')->with('success', 'Supplier deleted successfully.');
}

    // Other methods ...
}

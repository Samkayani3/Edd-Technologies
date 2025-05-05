<?php

namespace App\Http\Controllers;

use App\Models\Equipments;
use App\Models\RepairJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function index()
    {
        try {
            $equipments = Equipments::with('repairJob')->get();
            return view('admin.equipment.index', compact('equipments'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load equipment list: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ];

            if (Auth::user()->role === 'admin') {
                $rules['customer_id'] = 'required|exists:users,id';
            }

            $request->validate($rules);

            $customerId = Auth::user()->role === 'admin' 
                ? $request->customer_id 
                : Auth::id();

            Equipments::create([
                'customer_id' => $customerId,
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            $route = Auth::user()->role === 'admin' ? 'admin.dashboard' : 'customer.dashboard';

            return redirect()->route($route)->with('success', 'Equipment added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add equipment: ' . $e->getMessage());
        }
    }
}

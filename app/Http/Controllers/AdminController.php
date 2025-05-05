<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Equipments;
use App\Models\SparePartSupplier;
use Illuminate\Http\Request;
use App\Models\RepairJob;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ReceiptFormatter\ReceiptFormatter;
use App\Services\ReceiptFormatter\CsvReceiptFormatter; 

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::where('role', '!=', 'admin')->get();

        $technicians = User::where('role', 'technician')->get();

        $equipments = Equipments::all();

        $suppliers = SparePartSupplier::all();

        return view('admin.dashboard', compact('users', 'technicians', 'equipments', 'suppliers'));
    }

public function storeUser(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,technician,customer',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User added successfully.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while adding the user: ' . $e->getMessage());
    }
}


public function deleteUser($id)
{
    // Find the user by ID and delete it
    $user = User::findOrFail($id);
    $user->delete();

    // Redirect back with a success message
    return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
}



public function assignEquipmentToCustomer(Request $request)
{
    $validatedData = $request->validate([
        'equipment_id' => 'required|exists:equipments,id',
        'customer_id' => 'required|exists:users,id', 
    ]);

    $equipment = Equipments::findOrFail($validatedData['equipment_id']);
    $customer = User::findOrFail($validatedData['customer_id']);


    $equipment->customer_id = $customer->id; 
    $equipment->status = 'New';
    $equipment->save();

    return redirect()->route('admin.dashboard')->with('success', 'Equipment successfully assigned to customer.');
}


public function updateEquipment(Request $request)
{
    try {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'technician_id' => 'nullable|exists:users,id',
            'status' => 'required|in:New,InRepair,Completed'
        ]);
        $equipment = Equipments::findOrFail($validated['equipment_id']);
        $equipment->technician_id = $validated['technician_id'];
        $equipment->status = $validated['status'];
        $equipment->save();
        if ($validated['technician_id']) {
            RepairJob::updateOrCreate(
                ['equipment_id' => $equipment->id],
                [
                    'technician_id' => $validated['technician_id'],
                    'status' => $this->mapEquipmentStatusToRepairJobStatus($validated['status']),
                ]
            );
        } else {
            RepairJob::where('equipment_id', $equipment->id)->delete();
        }
        return redirect()->back()->with('success', 'Equipment and Repair Job updated successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to update equipment: ' . $e->getMessage());
    }
}
private function mapEquipmentStatusToRepairJobStatus($equipmentStatus)
{
    switch ($equipmentStatus) {
        case 'New':
            return 'New';
        case 'InRepair':
            return 'InRepair';
        case 'Completed':
            return 'Completed';
        default:
            return 'New';
    }
}

public function deleteEquipment($id)
{
    $equipment = Equipments::findOrFail($id);
    $equipment->delete();

    return redirect()->route('admin.dashboard')->with('success', 'Equipment deleted successfully.');
}

public function manageSuppliers()
{
    $suppliers = SparePartSupplier::all();
    return view('admin.manage-suppliers', compact('suppliers'));
}



public function downloadReceipt($id)
{
    try {
        $equipment = Equipments::with(['technician', 'customer'])->findOrFail($id);

        $formatter = new CsvReceiptFormatter(); // or new HtmlReceiptFormatter();
        $content = $formatter->format($equipment);

        $extension = $formatter instanceof CsvReceiptFormatter ? 'csv' : 'html';
        $contentType = $formatter instanceof CsvReceiptFormatter ? 'text/csv' : 'text/html';

        return response($content)
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', 'attachment; filename="receipt_' . $equipment->id . '.' . $extension . '"');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to generate receipt: ' . $e->getMessage());
    }
}




 public function addPrice(Request $request, $equipmentId)
{
    $validated = $request->validate([
        'price' => 'required|numeric|min:0',
    ]);

    $equipment = Equipments::findOrFail($equipmentId);
    $equipment->price = $validated['price'];
    $equipment->save();

    return redirect()->back()->with('success', 'Price added successfully.');
}

    
}

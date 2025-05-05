<?php

namespace App\Http\Controllers;

use App\Models\RepairJob;
use Illuminate\Http\Request;

class RepairJobController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'technician_id' => 'required|exists:users,id',
        ]);

        $repairJob = RepairJob::create([
            'equipment_id' => $validatedData['equipment_id'],
            'technician_id' => $validatedData['technician_id'],
            'status' => 'New',
        ]);

        return redirect()->route('repair-jobs.index');
    }

    public function updateStatus(Request $request, RepairJob $repairJob)
    {
        dd($request);
        $validatedData = $request->validate([
            'status' => 'required|in:New,InRepair,Completed',
            'cost' => 'nullable|numeric|min:0',
        ]);

        
        $repairJob->update([
            'status' => $validatedData['status'],
            'cost' => $validatedData['cost'],  // Update cost
        ]);

        if ($repairJob->equipment) {
            $repairJob->equipment->update([
                'status' => $this->mapRepairJobStatusToEquipmentStatus($validatedData['status']),
            ]);
        }

        return redirect()->route('repair-jobs.show', $repairJob);
    }

    private function mapRepairJobStatusToEquipmentStatus($repairJobStatus)
{
    switch ($repairJobStatus) {
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


    // Other methods ...
}

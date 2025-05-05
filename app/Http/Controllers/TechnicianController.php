<?php

namespace App\Http\Controllers;

use App\Models\RepairJob;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function dashboard()
    {
        // Fetch all repair jobs assigned to the logged-in technician
        $repairJobs = auth()->user()->repairJobs;
        return view('technician.dashboard', compact('repairJobs'));
    }

    public function updateJob(Request $request, RepairJob $repairJob)
    {
        $validated = $request->validate([
            'status' => 'required|in:New,InRepair,Completed',
            'tasks' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);
    
        $repairJob->update([
            'status' => $validated['status'],
            'tasks' => $validated['tasks'],
            'cost' => 0,
        ]);
    
        // Sync equipment status
        if ($repairJob->equipment) {
            $repairJob->equipment->update([
                'status' => $this->mapRepairJobStatusToEquipmentStatus($validated['status']),
            ]);
        }
    
        return redirect()->back()->with('success', 'Repair job updated successfully.');
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
    


    
}

@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="mb-4" style="color: #800020;">🛠️ Technician Dashboard</h1>

    <!-- Section Header -->
    <div class="card shadow-sm rounded-4">
        <div class="card-header" style="background-color: #800020; color: white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
            <h5 class="mb-0">Your Assigned Repair Jobs</h5>
        </div>

        <div class="card-body bg-light">
            @if ($repairJobs->isEmpty())
                <div class="alert alert-info">No repair jobs assigned yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead style="background-color: #806000; color: white;">
                            <tr>
                                <th>Equipment Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Tasks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($repairJobs as $repairJob)
                            <tr>
                                <td>{{ $repairJob->equipment->name ?? 'N/A' }}</td>
                                <td>{{ $repairJob->equipment->description ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge 
                                        @if($repairJob->status == 'Completed') bg-success
                                        @elseif($repairJob->status == 'Job Assessed') bg-warning
                                        @elseif($repairJob->status == 'Under Repair') bg-primary
                                        @else bg-secondary @endif">
                                        {{ $repairJob->status }}
                                    </span>
                                </td>
                                <td>{{ $repairJob->tasks ?? 'No tasks assigned' }}</td>
                                <td>
                                    <button class="btn btn-sm" style="background-color: #806000; color: white;" data-bs-toggle="modal" data-bs-target="#updateJobModal{{ $repairJob->id }}">
                                        ✏️ Update
                                    </button>
                                </td>
                            </tr>

                            <!-- Update Modal -->
                            <div class="modal fade" id="updateJobModal{{ $repairJob->id }}" tabindex="-1" aria-labelledby="updateJobModalLabel{{ $repairJob->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow-lg border-0 rounded-4">
                                        <form action="{{ route('repair-job.update-job', $repairJob->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header" style="background-color: #800020; color: white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                                                <h5 class="modal-title" id="updateJobModalLabel{{ $repairJob->id }}">🔧 Update Repair Job</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4 bg-light">
                                                <div class="mb-3">
                                                    <label for="status{{ $repairJob->id }}" class="form-label fw-bold" style="color: #800020;">Status</label>
                                                    <select name="status" id="status{{ $repairJob->id }}" class="form-select" required>
                                                        <option value="New" {{ $repairJob->status == 'New' ? 'selected' : '' }}>New</option>
                                                        <option value="InRepair" {{ $repairJob->status == 'InRepair' ? 'selected' : '' }}>InRepair</option>
                                                        <option value="Completed" {{ $repairJob->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tasks{{ $repairJob->id }}" class="form-label fw-bold" style="color: #800020;">Tasks</label>
                                                    <textarea name="tasks" id="tasks{{ $repairJob->id }}" class="form-control" rows="3" required>{{ $repairJob->tasks }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light rounded-bottom-4">
                                                <button type="submit" class="btn rounded-pill px-4" style="background-color:#806000; color:white">💾 Update Job</button>
                                                <button type="button" class="btn rounded-pill px-4" data-bs-dismiss="modal" style="background-color:#800020; color:white;">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

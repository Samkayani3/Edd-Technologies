@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0" style="color: #800020;">📋 Customer Dashboard</h1>

        <!-- Add Equipment Button (Aligned to the Right) -->
        <button class="btn text-white" style="background-color: #806000;" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
            ➕ Add Equipment
        </button>
    </div>

    <!-- Equipment Table -->
    <div class="card shadow-sm rounded-4">
        <div class="card-header text-white rounded-top-4" style="background-color: #800020;">
            <h5 class="mb-0">Your Equipment</h5>
        </div>
        <div class="card-body bg-light">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead style="background-color: #806000; color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipments as $equipment)
                        <tr>
                            <td>{{ $equipment->id }}</td>
                            <td>{{ $equipment->name }}</td>
                            <td>{{ $equipment->description }}</td>
                            <td>{{ $equipment->status ?? 'Not Assigned' }}</td>
                            <td>
                                <button class="btn btn-sm text-white" style="background-color: #800020;" data-bs-toggle="modal" data-bs-target="#viewEquipmentModal{{ $equipment->id }}">
                                    👁️ View
                                </button>
                            </td>
                        </tr>

                        <!-- View Equipment Modal -->
                        <div class="modal fade" id="viewEquipmentModal{{ $equipment->id }}" tabindex="-1" aria-labelledby="viewEquipmentModalLabel{{ $equipment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content shadow-lg border-0 rounded-4">
                                    <div class="modal-header text-white" style="background-color: #800020; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                                        <h5 class="modal-title fw-semibold" id="viewEquipmentModalLabel{{ $equipment->id }}">
                                            👀 Equipment Details
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4 bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold" style="color: #800020;">Equipment ID</label>
                                                <div class="form-control bg-white">{{ $equipment->id }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold" style="color: #800020;">Name</label>
                                                <div class="form-control bg-white">{{ $equipment->name }}</div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-bold" style="color: #800020;">Description</label>
                                                <div class="form-control bg-white" style="min-height: 80px;">
                                                    {{ $equipment->description }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-bold" style="color: #800020;">Status</label>
                                                <div class="form-control bg-white">
                                                    {{ $equipment->status ?? 'Not Assigned' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4">
                                        <button type="button" class="btn text-white px-4" style="background-color: #806000;" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No equipment found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Equipment Modal -->
<div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header text-white" style="background-color: #806000; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title fw-semibold" id="addEquipmentModalLabel">➕ Add New Equipment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light p-4">
                <form action="{{ route('equipment') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold" style="color: #800020;">Equipment Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold" style="color: #800020;">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description"></textarea>
                    </div>
                    <input type="hidden" name="status" value="Available">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn text-white px-4" style="background-color: #800020;">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

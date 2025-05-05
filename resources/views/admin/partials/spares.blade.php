
<div class="container py-4">
    <div class="card shadow-sm rounded-4 mb-4">
        <div class="card-header" style="background-color: #800020;">
            <h5 class="mb-0 text-white">🏭 Spare Part Suppliers</h5>
        </div>

        <div class="card-body">
            {{-- Add Supplier Form --}}
            <form action="{{ route('spare-part-supplier.store') }}" method="POST" class="row g-3 mb-4 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label for="supplierName" class="form-label">Supplier Name</label>
                    <input type="text" name="name" id="supplierName" class="form-control rounded-3 shadow-sm" placeholder="Enter supplier name" required>
                </div>
                <div class="col-md-6">
                    <label for="supplierLocation" class="form-label">Location</label>
                    <input type="text" name="location" id="supplierLocation" class="form-control rounded-3 shadow-sm" placeholder="Enter location" required>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn fw-semibold shadow-sm" style="background-color: #806000; color: white;">
                        ➕ Add
                    </button>
                </div>
            </form>

            {{-- Supplier List Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #806000; color: white;">
                        <tr>
                            <th scope="col">🆔 ID</th>
                            <th scope="col">🏷️ Name</th>
                            <th scope="col">📍 Location</th>
                            <th scope="col" class="text-center">✏️ Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td class="fw-semibold">{{ $supplier->name }}</td>
                                <td>{{ $supplier->location }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $supplier->id }}">✏️ Edit</button>
                                    <form action="{{ route('spare-part-supplier.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-3" onclick="return confirm('Are you sure?')">🗑️ Delete</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $supplier->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $supplier->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 shadow-sm">
                                        <div class="modal-header" style="background-color: #800020;">
                                            <h5 class="modal-title text-white" id="editModalLabel{{ $supplier->id }}">Edit Supplier</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('spare-part-supplier.update', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="name{{ $supplier->id }}" class="form-label">Name</label>
                                                    <input type="text" class="form-control" name="name" id="name{{ $supplier->id }}" value="{{ $supplier->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="location{{ $supplier->id }}" class="form-label">Location</label>
                                                    <input type="text" class="form-control" name="location" id="location{{ $supplier->id }}" value="{{ $supplier->location }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-3">💾 Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    🚫 No suppliers yet. Add one above!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


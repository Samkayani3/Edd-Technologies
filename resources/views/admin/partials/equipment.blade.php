@php
    use Illuminate\Support\Str;
@endphp

<!-- Equipment Management Card -->
<div class="card mb-4">
    <div class="card-header" style="background-color: #800020;">
        <div class="row align-items-center g-2 flex-nowrap">
            <div class="col-auto">
                <h5 class="text-white mb-0">🔧 All Equipment</h5>
            </div>
            <div class="col">
                <div class="d-flex gap-2 flex-wrap">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="🔍 Search name..." style="max-width: 180px;">
                    <select id="technicianFilter" class="form-select form-select-sm" style="max-width: 180px;">
                        <option value="">🔧 All Technicians</option>
                        @foreach($technicians as $tech)
                            <option value="{{ strtolower($tech->name) }}">{{ $tech->name }}</option>
                        @endforeach
                    </select>
                    <select id="customerFilter" class="form-select form-select-sm" style="max-width: 180px;">
                        <option value="">👤 All Customers</option>
                        @foreach($users->where('role', 'customer') as $cust)
                            <option value="{{ strtolower($cust->name) }}">{{ $cust->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-secondary btn-sm" id="resetFiltersBtn">♻️ Reset</button>
                    <button type="button" class="btn btn-sm" style="background-color: #806000; color: white;" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">➕ Add Equipment</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-striped mb-0">
            <thead style="background-color: #806000; color: white;">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Technician</th>
                    <th>Customer</th>
                    <th>Price</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="equipmentTable">
                @foreach($equipments as $index => $equipment)
                    @php
                        $repair = $equipment->repair;
                        $status = $repair ? $repair->status : $equipment->status;
                        $displayStatus = match($status) {
                            'New' => 'New',
                            'InRepair' => 'InRepair',
                            'Completed' => 'Completed',
                            default => $status
                        };
                        $badgeColor = match($displayStatus) {
                            'New' => 'success',
                            'InRepair' => 'warning',
                            'Completed' => 'danger',
                            default => 'secondary'
                        };
                    @endphp
                    <tr 
                        class="equipment-row" 
                        data-name="{{ strtolower($equipment->name) }}"
                        data-technician="{{ strtolower($equipment->technician?->name ?? '') }}"
                        data-customer="{{ strtolower($equipment->customer?->name ?? '') }}"
                    >
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $equipment->name }}</td>
                        <td>
                            <span class="badge bg-{{ $badgeColor }}">
                                {{ $displayStatus }}
                            </span>
                        </td>
                        <td>{{ $equipment->description }}</td>
                        <td>{{ $equipment->technician?->name ?? '—' }}</td>
                        <td>{{ $equipment->customer?->name ?? '—' }}</td>
                        <td>
            @if($equipment->price)
                <span class="badge bg-success">₱{{ number_format($equipment->price, 2) }}</span>
            @else
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#addPriceModal{{ $equipment->id }}">💸 Add Price</button>
            @endif
        </td>
                        <td class="text-end">
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editEquipmentModal{{ $equipment->id }}">✏️</button>
                            <!-- Delete Button -->
                            <form action="{{ route('admin.delete-equipment', $equipment->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this equipment?')">🗑️</button>
                            </form>
                            <!-- Generate Receipt Button (if Completed status) -->
                            @if($displayStatus == 'Completed' && $equipment->price)
    <a href="{{ route('admin.download-receipt', $equipment->id) }}" class="btn btn-sm btn-info">📄 Generate Slip</a>
@endif
                        </td>
                    </tr>

                    <!-- Edit Equipment Modal -->
                    <div class="modal fade" id="editEquipmentModal{{ $equipment->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.update-equipment') }}" method="POST" class="modal-content">
                                @csrf
                                <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                                <div class="modal-header" style="background-color: #800020;">
                                    <h5 class="modal-title text-white">Edit Equipment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body bg-light">
                                    <div class="mb-3">
                                        <label class="form-label">Equipment Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $equipment->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" required>{{ $equipment->description }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Assign Technician</label>
                                        <select name="technician_id" class="form-select">
                                            <option value="">-- None --</option>
                                            @foreach($technicians as $technician)
                                                <option value="{{ $technician->id }}" {{ $equipment->technician_id == $technician->id ? 'selected' : '' }}>{{ $technician->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Assigned Customer</label>
                                        <input type="text" class="form-control" value="{{ $equipment->customer?->name ?? '—' }}" readonly>
                                        <input type="hidden" name="customer_id" value="{{ $equipment->customer_id }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="New" {{ $equipment->status == 'New' ? 'selected' : '' }}>New</option>
                                            <option value="InRepair" {{ $equipment->status == 'InRepair' ? 'selected' : '' }}>InRepair</option>
                                            <option value="Completed" {{ $equipment->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn" style="background-color: #806000; color: white;">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Add Price Modal -->
<div class="modal fade" id="addPriceModal{{ $equipment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.add-price', $equipment->id) }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
            <div class="modal-header" style="background-color: #800020;">
                <h5 class="modal-title text-white">Add Price for {{ $equipment->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" class="form-control" required step="0.01">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Add Price</button>
            </div>
        </form>
    </div>
</div>


                    <!-- Generate Receipt Modal (for Completed status) -->
                    <div class="modal fade" id="generateReceiptModal{{ $equipment->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.generate-receipt', $equipment->id) }}" method="POST" class="modal-content">
                                @csrf
                                <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                                <div class="modal-header" style="background-color: #800020;">
                                    <h5 class="modal-title text-white">Generate Receipt for {{ $equipment->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body bg-light">
                                    <div class="mb-3">
                                        <label class="form-label">Price</label>
                                        <input type="number" name="price" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Generate Receipt</button>
                                    <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('admin.download-receipt', $equipment->id) }}'">📄 Download Receipt</button>
                                </div>
                            </form>
                        </div>
                    </div>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination Controls -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <button class="btn btn-sm btn-secondary" id="prevPage">⬅️ Prev</button>
    <span id="pageIndicator"></span>
    <button class="btn btn-sm btn-secondary" id="nextPage">Next ➡️</button>
</div>

<!-- Filtering + Pagination Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('.equipment-row');
    const searchInput = document.getElementById('searchInput');
    const technicianFilter = document.getElementById('technicianFilter');
    const customerFilter = document.getElementById('customerFilter');
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    const pageIndicator = document.getElementById('pageIndicator');

    let currentPage = 1;
    const rowsPerPage = 10;

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const technicianValue = technicianFilter.value.toLowerCase();
        const customerValue = customerFilter.value.toLowerCase();
        
        rows.forEach(row => {
            const name = row.dataset.name;
            const technician = row.dataset.technician;
            const customer = row.dataset.customer;

            const matchesSearch = name.includes(searchValue);
            const matchesTechnician = !technicianValue || technician === technicianValue;
            const matchesCustomer = !customerValue || customer === customerValue;

            row.style.display = (matchesSearch && matchesTechnician && matchesCustomer) ? '' : 'none';
        });

        currentPage = 1;
        paginateTable();
    }

    function paginateTable() {
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        const totalPages = Math.ceil(visibleRows.length / rowsPerPage);

        visibleRows.forEach((row, index) => {
            row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? '' : 'none';
        });

        pageIndicator.innerText = `Page ${currentPage} of ${totalPages}`;
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
    }

    searchInput.addEventListener('input', filterTable);
    technicianFilter.addEventListener('change', filterTable);
    customerFilter.addEventListener('change', filterTable);
    resetFiltersBtn.addEventListener('click', () => {
        searchInput.value = '';
        technicianFilter.value = '';
        customerFilter.value = '';
        filterTable();
    });
    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            paginateTable();
        }
    });
    nextBtn.addEventListener('click', () => {
        currentPage++;
        paginateTable();
    });

    paginateTable();
});
</script>


<!-- Modal Styling -->
<style>
.modal-backdrop {
    pointer-events: none;
}
.modal-dialog {
    pointer-events: all;
}
</style>

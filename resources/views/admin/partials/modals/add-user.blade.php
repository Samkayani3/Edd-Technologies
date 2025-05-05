<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-sm border-0">
            <form action="{{ route('admin.store-user') }}" method="POST">
                @csrf

                <div class="modal-header" style="background-color: #800020; color: white;">
                    <h5 class="modal-title" id="addUserModalLabel">➕ Add New User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control shadow-sm" placeholder="Enter name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control shadow-sm" placeholder="Enter email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control shadow-sm" placeholder="Create password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control shadow-sm" placeholder="Re-type password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select name="role" class="form-select shadow-sm" required>
                            <option disabled selected>Select a role</option>
                            <option value="admin">Admin</option>
                            <option value="technician">Technician</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer" style="background-color: #f8f9fa; border-bottom-left-radius: 0.25rem; border-bottom-right-radius: 0.25rem;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #806000; color: white;">💾 Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

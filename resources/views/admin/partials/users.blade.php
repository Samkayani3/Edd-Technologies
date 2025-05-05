<div class="card shadow-sm rounded-4 mb-4">
    <div class="card-header text-white rounded-top-4 d-flex justify-content-between align-items-center" style="background-color:#800020;"> 
        <h5 class="mb-0">👥 All Users</h5>
        <button class="btn btn-sm text-primary fw-bold" style="background-color:#806000; color:#800020 !important;" data-bs-toggle="modal" data-bs-target="#addUserModal">
            ➕ Add User
        </button>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #806000; color: white;">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">👤 Name</th>
                        <th scope="col">📧 Email</th>
                        <th scope="col">🛡️ Role</th>
                        <th scope="col" class="text-end">⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge 
                                @if($user->role == 'admin') bg-danger
                                @elseif($user->role == 'technician') bg-warning text-dark
                                @else bg-info text-dark @endif 
                                text-capitalize shadow-sm">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="text-end">
                            <form action="{{ route('admin.delete-user', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger rounded-circle" title="Delete User">
                                    🗑️
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

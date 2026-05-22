@extends('layouts.admin')

@section('title', 'Manage Users')
@section('header', 'User Management')

@section('content')
<div class="header" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <h2 style="font-size: 1.25rem;">User List</h2>
    <button onclick="toggleModal('addUserModal')" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Add New User
    </button>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td><strong>{{ $user->name }}</strong></td>
                <td>{{ $user->email }}</td>
                <td style="color: var(--text-muted); font-size: 0.875rem;">{{ $user->created_at->format('M d, Y') }}</td>
                <td>
                    <div style="display: flex; gap: 0.5rem;">
                        <button onclick='openEditModal(@json($user))' class="btn btn-primary" style="padding: 0.5rem; background: #f59e0b;">
                            <i class="fas fa-edit"></i>
                        </button>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem;"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addUserModal" class="modal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem;">Add New User</h2>
            <button onclick="toggleModal('addUserModal')" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" name="name" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                <i class="fas fa-plus"></i> Create User
            </button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem;">Edit User</h2>
            <button onclick="toggleModal('editUserModal')" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form id="editUserForm" action="" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" name="name" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">New Password (leave blank to keep current)</label>
                <input type="password" name="password" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.75rem;" placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; background: #f59e0b;">
                <i class="fas fa-save"></i> Update User
            </button>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function openEditModal(user) {
        const form = document.getElementById('editUserForm');
        form.action = `{{ url('/admin/users') }}/${user.id}`;
        form.elements['name'].value = user.name;
        form.elements['email'].value = user.email;
        form.elements['password'].value = '';
        toggleModal('editUserModal');
    }
</script>
@endsection
@endsection

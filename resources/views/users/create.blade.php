@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Create New User</h4></div>
            <div class="card-body">
                <form id="createUserForm">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" id="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                    <div id="alertMessage" class="mt-3"></div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.getElementById('createUserForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const alertBox = document.getElementById('alertMessage');
        alertBox.innerHTML = '';

        const payload = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            role: document.getElementById('role').value
        };

        try {
            const response = await API.fetch('/v1/users', {
                method: 'POST',
                body: JSON.stringify(payload)
            });
            const data = await response.json();

            if (response.ok) {
                window.location.href = "{{ route('users.index') }}";
            } else {
                alertBox.innerHTML = `<div class="alert alert-danger">${data.message || 'Error'}</div>`;
            }
        } catch (error) {
            alertBox.innerHTML = `<div class="alert alert-danger">Error</div>`;
        }
    });
</script>
@endsection
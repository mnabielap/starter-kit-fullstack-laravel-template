@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Edit User</h4></div>
            <div class="card-body">
                <form id="editUserForm">
                    <input type="hidden" id="userId">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password (Leave blank to keep current)</label>
                        <input type="password" class="form-control" id="password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" id="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
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
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id');

    async function loadUser() {
        if(!userId) return;
        const response = await API.fetch(`/v1/users/${userId}`);
        if(response.ok) {
            const data = await response.json();
            document.getElementById('userId').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('role').value = data.role;
        }
    }

    document.getElementById('editUserForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const alertBox = document.getElementById('alertMessage');
        alertBox.innerHTML = '';

        const payload = {};
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const role = document.getElementById('role').value;

        if (name) payload.name = name;
        if (email) payload.email = email;
        if (password) payload.password = password;
        if (role) payload.role = role;
        
        // Laravel needs _method: PATCH if using POST, but fetch supports PATCH directly
        try {
            const response = await API.fetch(`/v1/users/${userId}`, {
                method: 'PATCH',
                body: JSON.stringify(payload)
            });
            
            if (response.ok) {
                window.location.href = "{{ route('users.index') }}";
            } else {
                const data = await response.json();
                alertBox.innerHTML = `<div class="alert alert-danger">${data.message || 'Error'}</div>`;
            }
        } catch (error) {
            alertBox.innerHTML = `<div class="alert alert-danger">Error</div>`;
        }
    });

    loadUser();
</script>
@endsection
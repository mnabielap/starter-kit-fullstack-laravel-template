@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">User Management</h5>
                    <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-lg"></i> Create New User
                    </a>
                </div>
            </div>
            
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form id="filterForm">
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-6">
                            <input type="text" class="form-control" id="searchName" placeholder="Search...">
                        </div>
                        <div class="col-xxl-2 col-sm-4">
                            <select class="form-select" id="filterRole">
                                <option value="">All Roles</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-xxl-1 col-sm-4">
                            <button type="button" class="btn btn-primary w-100" onclick="resetPageAndLoad()">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle" id="usersTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody><tr><td colspan="6" class="text-center">Loading...</td></tr></tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="row align-items-center mt-4">
                    <div class="col-sm">
                        <div class="text-muted">Showing page <span id="currentPageDisplay">1</span> of <span id="totalPages">1</span></div>
                    </div>
                    <div class="col-sm-auto">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item" id="prevBtn"><a class="page-link" href="#" onclick="changePage(-1)">Prev</a></li>
                            <li class="page-item" id="nextBtn"><a class="page-link" href="#" onclick="changePage(1)">Next</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let currentPage = 1;
    let lastPage = 1;

    function resetPageAndLoad() {
        currentPage = 1;
        loadUsers();
    }

    function changePage(delta) {
        const newPage = currentPage + delta;
        if (newPage >= 1 && newPage <= lastPage) {
            currentPage = newPage;
            loadUsers();
        }
    }

    async function loadUsers() {
        const search = document.getElementById('searchName').value;
        const role = document.getElementById('filterRole').value;
        
        const params = new URLSearchParams({
            page: currentPage,
            limit: 10,
            search: search,
            role: role
        });

        try {
            const response = await API.fetch(`/v1/users?${params.toString()}`);
            const data = await response.json();

            if (response.ok) {
                const tbody = document.querySelector('#usersTable tbody');
                tbody.innerHTML = '';
                
                // Laravel Paginator Structure mapped in Controller:
                // { results: [], page: 1, limit: 10, totalPages: 5, ... }
                
                lastPage = data.totalPages;
                document.getElementById('currentPageDisplay').innerText = data.page;
                document.getElementById('totalPages').innerText = lastPage;

                if (data.results.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No users found.</td></tr>';
                    return;
                }

                data.results.forEach(user => {
                    // Note: Use Laravel route() in blade if possible, or build string
                    const editUrl = `{{ url('/users/edit') }}?id=${user.id}`;
                    
                    const tr = `
                        <tr>
                            <td>#${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                            <td>${new Date(user.created_at).toLocaleDateString()}</td>
                            <td>
                                <a href="${editUrl}" class="btn btn-sm btn-primary">Edit</a>
                                <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += tr;
                });
            } else {
                 if(response.status === 403) alert('You do not have permission.');
            }
        } catch (error) {
            console.error(error);
        }
    }

    async function deleteUser(id) {
        if(!confirm('Delete user?')) return;
        const response = await API.fetch(`/v1/users/${id}`, { method: 'DELETE' });
        if(response.ok) loadUsers();
        else alert('Failed to delete');
    }

    document.addEventListener('DOMContentLoaded', loadUsers);
</script>
@endsection
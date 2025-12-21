<header id="page-topbar" class="bg-white border-bottom mb-4 p-3 d-flex justify-content-between align-items-center">
    <div class="d-flex">
        <!-- Toggle button could go here -->
    </div>
    <div class="d-flex align-items-center">
        <div class="dropdown ms-sm-3 header-item topbar-user">
            <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="d-flex align-items-center">
                    <div class="bg-light rounded-circle p-2 me-2">
                        <i class="bi bi-person"></i>
                    </div>
                    <span class="text-start ms-xl-2">
                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">User</span>
                    </span>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <h6 class="dropdown-header">Welcome!</h6>
                <button class="dropdown-item" onclick="API.logout()"><i class="bi bi-box-arrow-right text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Logout</span></button>
            </div>
        </div>
    </div>
</header>
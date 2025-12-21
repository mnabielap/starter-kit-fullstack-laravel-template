<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box text-center mt-3 mb-3">
        <a href="{{ route('dashboard') }}" class="text-white text-decoration-none fw-bold fs-4">
            StarterKit
        </a>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> <span class="ms-2">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-people"></i> <span class="ms-2">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <!-- FIXED LINK HERE -->
                    <a class="nav-link" href="{{ url('api/documentation') }}" target="_blank">
                        <i class="bi bi-file-earmark-code"></i> <span class="ms-2">API Docs</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const token = localStorage.getItem('accessToken');
        if (token) {
            const role = localStorage.getItem('userRole'); 
            if (role !== 'admin') {
                const userLink = document.querySelector('a[href*="/users"]');
                if(userLink) userLink.parentElement.style.display = 'none';
            }
        }
    });
</script>
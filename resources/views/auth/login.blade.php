@extends('layouts.guest') 

@section('content')
<form id="loginForm">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" placeholder="Enter email" required value="admin@example.com">
    </div>

    <div class="mb-3">
        <label class="form-label" for="password">Password</label>
        <input type="password" class="form-control" id="password" placeholder="Enter password" required value="password123">
    </div>

    <div class="mt-4">
        <button class="btn btn-primary w-100" type="submit">Sign In</button>
    </div>
    
    <div class="mt-4 text-center">
        <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
        <p class="mb-0"><a href="{{ route('password.request') }}" class="text-muted">Forgot Password?</a></p>
    </div>

    <div id="alertMessage" class="mt-3"></div>
</form>
@endsection

@section('script')
<script>
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const alertBox = document.getElementById('alertMessage');

        alertBox.innerHTML = '';

        try {
            const response = await API.fetch('/v1/auth/login', {
                method: 'POST',
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (response.ok) {
                API.saveTokens(data);
                window.location.href = "{{ route('dashboard') }}";
            } else {
                alertBox.innerHTML = `<div class="alert alert-danger">${data.message || 'Login failed'}</div>`;
            }
        } catch (error) {
            console.error(error);
            alertBox.innerHTML = `<div class="alert alert-danger">Connection Error</div>`;
        }
    });
</script>
@endsection
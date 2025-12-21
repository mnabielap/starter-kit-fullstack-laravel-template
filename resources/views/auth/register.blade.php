@extends('layouts.guest')

@section('content')
<form id="registerForm">
    <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="password">Password</label>
        <input type="password" class="form-control" id="password" required>
        <div class="form-text">Minimum 8 characters, at least one letter and one number.</div>
    </div>

    <div class="mt-4">
        <button class="btn btn-success w-100" type="submit">Register</button>
    </div>
    
    <div class="mt-4 text-center">
        <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
    </div>

    <div id="alertMessage" class="mt-3"></div>
</form>
@endsection

@section('script')
<script>
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const alertBox = document.getElementById('alertMessage');
        alertBox.innerHTML = '';

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const response = await API.fetch('/v1/auth/register', {
                method: 'POST',
                body: JSON.stringify({ name, email, password })
            });

            const data = await response.json();

            if (response.ok) {
                API.saveTokens(data);
                window.location.href = "{{ route('dashboard') }}";
            } else {
                // Laravel Validation Errors are usually in data.errors or data.message
                let errorHtml = data.message;
                if(data.errors) {
                     errorHtml = Object.values(data.errors).flat().join('<br>');
                }
                alertBox.innerHTML = `<div class="alert alert-danger">${errorHtml}</div>`;
            }
        } catch (error) {
            alertBox.innerHTML = `<div class="alert alert-danger">An error occurred</div>`;
        }
    });
</script>
@endsection
@extends('layouts.guest')

@section('content')
<form id="forgotForm">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" placeholder="Enter email" required>
    </div>

    <div class="mt-4">
        <button class="btn btn-warning w-100" type="submit">Send Reset Link</button>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-underline"> Back to Login </a>
    </div>
    
    <div id="alertMessage" class="mt-3"></div>
</form>
@endsection

@section('script')
<script>
    document.getElementById('forgotForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const alertBox = document.getElementById('alertMessage');
        alertBox.innerHTML = '<div class="alert alert-info">Sending...</div>';

        try {
            const response = await API.fetch('/v1/auth/forgot-password', {
                method: 'POST',
                body: JSON.stringify({ email })
            });

            if (response.ok) {
                alertBox.innerHTML = `<div class="alert alert-success">If the email exists, a reset link has been sent.</div>`;
            } else {
                const data = await response.json();
                alertBox.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        } catch (error) {
            alertBox.innerHTML = `<div class="alert alert-danger">An error occurred</div>`;
        }
    });
</script>
@endsection
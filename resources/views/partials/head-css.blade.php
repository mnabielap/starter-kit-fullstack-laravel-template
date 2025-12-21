<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- GLOBAL BASE URL CONFIGURATION -->
<script>
    window.APP_URL = "{{ url('/') }}";
</script>
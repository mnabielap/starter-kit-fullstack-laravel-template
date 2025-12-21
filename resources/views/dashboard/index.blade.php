@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Dashboard</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Welcome</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">This is the Fullstack Laravel Starter Kit.</p>
                <div class="alert alert-info">
                    <strong>Status:</strong> Authenticated via Sanctum.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
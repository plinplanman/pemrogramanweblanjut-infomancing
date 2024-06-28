@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3>{{ __('Dashboard') }}</h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <h4 class="mb-3">{{ __('Selamat Datang') }}</h4>
                    
                    <!-- Additional Content -->
                    <h5 class="text-muted">Selamat datang di Halaman {{ Auth::user()->role }}</h5>
                    

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

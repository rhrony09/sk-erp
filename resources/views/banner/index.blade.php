@extends('layouts.admin')
@section('page-title')
    {{__('Manage Banner')}}
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">{{ __('All Banners') }}</h2>
            <span class="text-muted small">{{ __('Manage your promotional banners below.') }}</span>
        </div>
        <a href="{{ route('ecommerce.banners.create') }}" class="btn btn-success"><i class="ti ti-plus"></i> {{ __('Add Banner') }}</a>
    </div>
    <div class="row g-4">
        @forelse($banners as $banner)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card shadow-sm h-100 border-0 banner-card position-relative">
                    <img src="{{ $banner->image ? asset('storage/' . $banner->image) : 'https://via.placeholder.com/600x250?text=No+Image' }}" class="card-img-top rounded-top" alt="{{ $banner->title }}">
                    <div class="card-body bg-dark text-white rounded-bottom">
                        <h5 class="card-title mb-1">{{ $banner->title }}</h5>
                        <p class="card-text small mb-1">{{ $banner->sub_title }}</p>
                        <p class="card-text small mb-2">{{ $banner->description }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="{{ $banner->link }}" class="btn btn-primary btn-sm" target="_blank"><i class="ti ti-eye"></i> {{ __('View') }}</a>
                            <div>
                                <a href="{{ route('ecommerce.banners.edit', $banner) }}" class="btn btn-warning btn-sm me-1"><i class="ti ti-edit"></i></a>
                                <form action="{{ route('ecommerce.banners.destroy', $banner) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this banner?')"><i class="ti ti-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center mb-0">{{ __('No banners found.') }}</div>
            </div>
        @endforelse
    </div>
</div>
<style>
    .banner-card {
        transition: box-shadow 0.2s;
    }
    .banner-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
    .banner-card .card-img-top {
        object-fit: cover;
        height: 160px;
    }
    .banner-card .card-body {
        background: #23242a;
        min-height: 140px;
    }
    .banner-card .card-title {
        color: #6fd943;
        font-size: 1.1rem;
    }
    .banner-card .btn {
        font-size: 0.85rem;
        padding: 0.3rem 0.7rem;
    }
    .banner-card .btn-warning {
        color: #fff;
    }
    .banner-card .btn-warning:hover {
        background: #ffb300;
        color: #23242a;
    }
    .banner-card .btn-danger:hover {
        background: #ff5252;
        color: #fff;
    }
</style>
@endsection

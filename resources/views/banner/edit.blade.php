@extends('layouts.admin')
@section('page-title')
    {{__('Edit Banner')}}
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">{{ __('Edit Banner') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('ecommerce.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('Title') }}</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $banner->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="sub_title" class="form-label">{{ __('Sub Title') }}</label>
                            <input type="text" name="sub_title" id="sub_title" class="form-control" value="{{ $banner->sub_title }}">
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">{{ __('Link') }}</label>
                            <input type="text" name="link" id="link" class="form-control" value="{{ $banner->link }}">
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">{{ __('Banner Image') }}</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*" onchange="previewBannerImage(event)">
                            <div class="mt-2">
                                <img id="banner-preview" src="{{ $banner->image ? asset('storage/' . $banner->image) : 'https://via.placeholder.com/600x250?text=No+Image' }}" alt="Preview" style="max-width:100%;height:120px;object-fit:cover;border-radius:8px;display:block;">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('ecommerce.banners') }}" class="btn btn-secondary me-2">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-success">{{ __('Update Banner') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script-page')
<script>
    function previewBannerImage(event) {
        const [file] = event.target.files;
        if (file) {
            document.getElementById('banner-preview').src = URL.createObjectURL(file);
        }
    }
</script>
@endpush
@endsection 
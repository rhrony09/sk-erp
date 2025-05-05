@php
    $banners = \App\Models\Banner::where('status', 1)->orderByDesc('id')->get();
@endphp

<div class="slider-area">
    <div class="hero-slider-active slick-dot-style slider-arrow-style">
        @forelse($banners as $banner)
            <div class="single-slider d-flex align-items-center" style="background-image: url({{ $banner->image ? asset('storage/' . $banner->image) : asset('assets/img-ecom/slider/slider1-home1.jpg') }});">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6 col-sm-8">
                            <div class="slider-text">
                                <h1>{{ $banner->title }}</h1>
                                <p>{{ $banner->sub_title }}</p>
                                <a class="btn-1 home-btn" href="{{ $banner->link }}">shop now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="single-slider d-flex align-items-center" style="background-image: url({{ asset('assets/img-ecom/slider/slider1-home1.jpg') }});">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6 col-sm-8">
                            <div class="slider-text">
                                <h1>No Banners Available</h1>
                                <p>Please add some banners from the admin panel.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
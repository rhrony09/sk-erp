@extends('ecommerce.layouts.master')
@section('content')
<div class="breadcrumb-area mb-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-wrap">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('ecommerce.home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('ecommerce.blog.list') }}">Blog</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $blog->title }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="product-details-area blog-details-area pb-60">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <!-- blog post title -->
                <div class="single-product-content blog-post-title mb-20">
                    <h2>{{ $blog->title }}</h2>
                    <div class="product-meta">
                        <span><i class="fa fa-calendar"></i> {{ $blog->published_at ? $blog->published_at->format('d M Y') : '' }}</span>
                        <span><i class="fa fa-user"></i> {{ $blog->author ? $blog->author->name : 'Admin' }}</span>
                        <span><i class="fa fa-eye"></i> {{ $blog->view_count }} Views</span>
                    </div>
                </div>
                
                <!-- blog image -->
                <div class="product-large-slider mb-30">
                    <div class="blog-featured-image">
                        @if($blog->featured_image)
                            <img src="{{ asset('storage/'.$blog->featured_image) }}" alt="{{ $blog->title }}" class="img-fluid">
                        @elseif($blog->thumb)
                            <img src="{{ asset('storage/'.$blog->thumb) }}" alt="{{ $blog->title }}" class="img-fluid">
                        @else
                            <img src="{{ asset('assets/img/blog/blog-placeholder.jpg') }}" alt="{{ $blog->title }}" class="img-fluid">
                        @endif
                    </div>
                </div>
                
                <!-- blog short description -->
                <div class="product-desc-content mb-30">
                    <p class="short-desc">{{ $blog->description }}</p>
                </div>
                
                <!-- blog content -->
                <div class="product-full-info-content mb-40">
                    <div class="entry-content">
                        {!! $blog->content !!}
                    </div>
                </div>
                
                <!-- social sharing -->
                <div class="product-share mb-40">
                    <div class="share-title">
                        <h5>Share this post:</h5>
                    </div>
                    <div class="social-share">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('ecommerce.blog.show', $blog->slug)) }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('ecommerce.blog.show', $blog->slug)) }}&text={{ urlencode($blog->title) }}" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('ecommerce.blog.show', $blog->slug)) }}" target="_blank"><i class="fab fa-linkedin"></i></a>
                        <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(route('ecommerce.blog.show', $blog->slug)) }}&media={{ urlencode(asset('storage/'.$blog->featured_image)) }}&description={{ urlencode($blog->title) }}" target="_blank"><i class="fab fa-pinterest"></i></a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' - ' . route('ecommerce.blog.show', $blog->slug)) }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="sidebar-wrapper">
                    <!-- Products widget -->
                    <div class="sidebar-single mb-30">
                        <h3 class="sidebar-title">RelatedProducts</h3>
                        <div class="sidebar-product">
                            @foreach($relatedProducts->take(5) as $product)
                            <div class="single-product-item d-flex mb-3">
                                <div class="product-thumb">
                                    <a href="{{ route('ecommerce.singleProduct', $product->slug ?? 'product') }}">
                                        @if(!empty($product->image) && \Storage::exists($product->image))
                                            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="max-width: 80px;">
                                        @else
                                            <img src="{{ asset('assets/img/product/product-placeholder.jpg') }}" alt="{{ $product->name }}" style="max-width: 80px;">
                                        @endif
                                    </a>
                                </div>
                                <div class="product-info">
                                    <h5><a href="{{ route('ecommerce.singleProduct', $product->slug ?? 'product') }}">{{ Str::limit($product->name, 30) }}</a></h5>
                                    <div class="price-box">
                                        @if($product->sale_price && $product->sale_price < $product->purchase_price)
                                            <span class="current-price">৳ {{ number_format($product->sale_price, 2) }}</span>
                                        @else
                                            <span class="current-price">৳ {{ number_format($product->purchase_price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Recent posts widget -->
                    @if(isset($recentPosts) && count($recentPosts) > 0)
                    <div class="sidebar-single mb-30">
                        <h3 class="sidebar-title">Recent Posts</h3>
                        <div class="blog-post-list">
                            @foreach($recentPosts as $post)
                            <div class="blog-post-item d-flex mb-3">
                                <div class="post-thumb">
                                    <a href="{{ route('ecommerce.blog.show', $post->slug) }}">
                                        @if($post->thumb)
                                            <img src="{{ asset('storage/'.$post->thumb) }}" alt="{{ $post->title }}" style="max-width: 80px;">
                                        @else
                                            <img src="{{ asset('assets/img/blog/blog-placeholder-small.jpg') }}" alt="{{ $post->title }}" style="max-width: 80px;">
                                        @endif
                                    </a>
                                </div>
                                <div class="post-info">
                                    <h5><a href="{{ route('ecommerce.blog.show', $post->slug) }}">{{ Str::limit($post->title, 30) }}</a></h5>
                                    <span><i class="fa fa-calendar"></i> {{ $post->published_at ? $post->published_at->format('d M Y') : '' }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product sections wrapper -->
<div class="product-area pb-60">
    <div class="container-fluid">
        <!-- related products -->
        @if(count($relatedProducts) > 0)
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h3>Related Products</h3>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($relatedProducts as $product)
            <div class="col-md-3 col-sm-6">
                <div class="product-item mb-30">
                    <div class="product-thumb">
                        <a href="{{ route('ecommerce.singleProduct', $product->slug ?? 'product') }}">
                            @if(!empty($product->image) && \Storage::exists($product->image))
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('assets/img/product/product-placeholder.jpg') }}" alt="{{ $product->name }}">
                            @endif
                        </a>
                        @if($product->sale_price && $product->sale_price < $product->purchase_price)
                            <div class="box-label">
                                <div class="label-product label_sale">
                                    <span>{{ round(($product->purchase_price - $product->sale_price) / $product->purchase_price * 100) }}%</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="product-caption">
                        <div class="product-name">
                            <h4><a href="{{ route('ecommerce.singleProduct', $product->slug ?? 'product') }}">{{ $product->name }}</a></h4>
                        </div>
                        <div class="price-box">
                            @if($product->sale_price && $product->sale_price < $product->purchase_price)
                                <span class="regular-price">৳ {{ number_format($product->sale_price, 2) }}</span>
                                <span class="old-price"><del>৳ {{ number_format($product->purchase_price, 2) }}</del></span>
                            @else
                                <span class="regular-price">৳ {{ number_format($product->purchase_price, 2) }}</span>
                            @endif
                        </div>
                        <div class="product-action-link">
                            <a href="{{ route('ecommerce.singleProduct', $product->slug ?? 'product') }}" data-toggle="tooltip" title="View Product"><i class="fa fa-eye"></i></a>
                            <a href="javascript:void(0)" class="add-to-cart-btn" data-product-id="{{ $product->id }}" data-toggle="tooltip" title="Add to Cart"><i class="fa fa-shopping-cart"></i></a>
                            <a href="javascript:void(0)" class="add-to-wishlist-btn" data-product-id="{{ $product->id }}" data-toggle="tooltip" title="Add to Wishlist"><i class="fa fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- You May Also Like products -->
        <div class="row mt-40">
            <div class="col-12">
                <div class="section-title">
                    <h3>You May Also Like</h3>
                </div>
            </div>
        </div>
        <div class="row">
            @php
                // Get random products that aren't in related products
                $relatedIds = $relatedProducts->pluck('id')->toArray();
                $randomProducts = \App\Models\ProductService::whereNotIn('id', $relatedIds)
                    ->inRandomOrder()
                    ->limit(8)
                    ->get();
            @endphp
            
            @foreach($randomProducts as $product)
            <div class="col-md-3 col-sm-6">
                <div class="product-item mb-30">
                    <div class="product-thumb">
                        <a href="{{ route('ecommerce.singleProduct', $product->slug ?? 'product') }}">
                            @if(!empty($product->image) && \Storage::exists($product->image))
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('assets/img/product/product-placeholder.jpg') }}" alt="{{ $product->name }}">
                            @endif
                        </a>
                        @if($product->sale_price && $product->sale_price < $product->purchase_price)
                            <div class="box-label">
                                <div class="label-product label_sale">
                                    <span>{{ round(($product->purchase_price - $product->sale_price) / $product->purchase_price * 100) }}%</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="product-caption">
                        <div class="product-name">
                            <h4><a href="{{ route('ecommerce.singleProduct', $product->slug ?? 'product') }}">{{ $product->name }}</a></h4>
                        </div>
                        <div class="price-box">
                            @if($product->sale_price && $product->sale_price < $product->purchase_price)
                                <span class="regular-price">৳ {{ number_format($product->sale_price, 2) }}</span>
                                <span class="old-price"><del>৳ {{ number_format($product->purchase_price, 2) }}</del></span>
                            @else
                                <span class="regular-price">৳ {{ number_format($product->purchase_price, 2) }}</span>
                            @endif
                        </div>
                        <div class="product-action-link">
                            <a href="{{ route('ecommerce.singleProduct', $product->slug ?? 'product') }}" data-toggle="tooltip" title="View Product"><i class="fa fa-eye"></i></a>
                            <a href="javascript:void(0)" class="add-to-cart-btn" data-product-id="{{ $product->id }}" data-toggle="tooltip" title="Add to Cart"><i class="fa fa-shopping-cart"></i></a>
                            <a href="javascript:void(0)" class="add-to-wishlist-btn" data-product-id="{{ $product->id }}" data-toggle="tooltip" title="Add to Wishlist"><i class="fa fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    $(document).ready(function() {
        // Add to cart functionality for related products
        $('.add-to-cart-btn').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');
            
            $.ajax({
                url: '{{ route("ecommerce.addToCart") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if(response.status === 'success') {
                        toastr.success(response.message);
                        // Update cart count
                        $.get('{{ route("ecommerce.getCartCount") }}', function(data) {
                            $('.cart-count').text(data.count);
                        });
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Something went wrong. Please try again.');
                }
            });
        });
        
        // Add to wishlist functionality
        $('.add-to-wishlist-btn').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');
            
            $.ajax({
                url: '{{ route("ecommerce.addWishlist", ":productId") }}'.replace(':productId', productId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.status === 'success') {
                        toastr.success(response.message);
                        // Update wishlist count
                        $.get('{{ route("ecommerce.getWishlistCount") }}', function(data) {
                            $('.wishlist-count').text(data.count);
                        });
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if(xhr.status === 401) {
                        toastr.warning('Please login to add products to wishlist');
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        });
    });
</script>
@endpush 
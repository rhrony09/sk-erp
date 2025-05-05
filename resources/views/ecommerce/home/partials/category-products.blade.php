@php
    // Get only the first 3 featured categories
    $categoriesToShow = $featuredCategories->take(3);
@endphp

@foreach($categoriesToShow as $category)
    @if($category->products && $category->products->count())
        <div class="home-module-four">
            <div class="container-fluid">
                <div class="section-title">
                    <h3>
                        <span>{{ $category->name }}</span>
                    </h3>
                </div>
                <div class="pro-module-four-active owl-carousel owl-arrow-style">
                    @foreach($category->products->take(8) as $product)
                        <div class="product-module-four-item">
                            <div class="product-module-caption">
                                <div class="manufacture-com">
                                    <p>
                                        <a href="#">{{ $category->name }}</a>
                                    </p>
                                </div>
                                <div class="product-module-name">
                                    <h4>
                                        <a href="{{ route('ecommerce.singleProduct', $product->slug) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h4>
                                </div>
                                {{-- Add ratings, price, etc. as needed --}}
                                <div class="price-box-module">
                                    <span class="regular-price">
                                        @if($product->discount_price)
                                            <span class="special-price">{{ $product->discount_price }}৳</span>
                                            <span class="old-price"><del>{{ $product->sale_price }}৳</del></span>
                                        @else
                                            {{ $product->sale_price }}৳
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="product-module-thumb">
                                <a href="{{ route('ecommerce.singleProduct', $product->slug) }}">
                                    <img src="{{ asset('storage/uploads/pro_image/' . $product->pro_image) }}" alt="{{ $product->name }}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach
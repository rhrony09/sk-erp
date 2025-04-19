<div class="breadcrumb-area mb-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-wrap">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$product->name}}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="product-details-main-wrapper pb-50">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5">
                <div class="product-large-slider mb-20">
                    <div class="pro-large-img">
                        <img src="{{asset('storage/uploads/pro_image/' . $product->pro_image)}}" alt="" />
                        <div class="img-view">
                            <a class="img-popup" href="{{asset('storage/uploads/pro_image/' . $product->pro_image)}}"><i
                                    class="fa fa-search"></i></a>
                        </div>
                    </div>
                    <div class="pro-large-img">
                        <img src="{{asset('storage/uploads/pro_image/' . $product->pro_image)}}" alt="" />
                        <div class="img-view">
                            <a class="img-popup" href="{{asset('storage/uploads/pro_image/' . $product->pro_image)}}"><i
                                    class="fa fa-search"></i></a>
                        </div>
                    </div>
                    <div class="pro-large-img">
                        <img src="assets/img/product/product-6.jpg" alt="" />
                        <div class="img-view">
                            <a class="img-popup" href="assets/img/product/product-6.jpg"><i
                                    class="fa fa-search"></i></a>
                        </div>
                    </div>
                    <div class="pro-large-img">
                        <img src="assets/img/product/product-7.jpg" alt="" />
                        <div class="img-view">
                            <a class="img-popup" href="assets/img/product/product-7.jpg"><i
                                    class="fa fa-search"></i></a>
                        </div>
                    </div>
                    <div class="pro-large-img">
                        <img src="assets/img/product/product-8.jpg" alt="" />
                        <div class="img-view">
                            <a class="img-popup" href="assets/img/product/product-8.jpg"><i
                                    class="fa fa-search"></i></a>
                        </div>
                    </div>
                    <div class="pro-large-img">
                        <img src="assets/img/product/product-9.jpg" alt="" />
                        <div class="img-view">
                            <a class="img-popup" href="assets/img/product/product-9.jpg"><i
                                    class="fa fa-search"></i></a>
                        </div>
                    </div>
                </div>
                <div class="pro-nav">
                    <div class="pro-nav-thumb"><img src="{{asset('storage/uploads/pro_image/' . $product->pro_image)}}"
                            alt="" /></div>
                    <div class="pro-nav-thumb"><img src="assets/img/product/product-6.jpg" alt="" /></div>
                    <div class="pro-nav-thumb"><img src="assets/img/product/product-7.jpg" alt="" /></div>
                    <div class="pro-nav-thumb"><img src="assets/img/product/product-8.jpg" alt="" /></div>
                    <div class="pro-nav-thumb"><img src="assets/img/product/product-9.jpg" alt="" /></div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="product-details-inner">
                    <div class="product-details-contentt">
                        <div class="pro-details-name mb-10">
                            <h3>{{$product->name}}</h3>
                        </div>
                        <div class="pro-details-review mb-20">
                            <ul>
                                @php
                                    $avgRating = $product->reviews->sum('rating') / $product->reviews->count();
                                @endphp
                                <li>
                                    @for ($i = 1; $i <= $avgRating; $i++)
                                        <span><i class="fa fa-star text-warning"></i></span>
                                    @endfor
                                </li>
                                <li><a href="#">{{ $product->reviews->count() }} Reviews</a></li>
                            </ul>
                        </div>
                        <div class="price-box mb-15">
                            @if($product->discount_price == NULL)
                                <span class="regular-price"><span
                                        class="special-price">{{$product->sale_price}}৳</span></span>
                            @else
                                <span class="regular-price"><span
                                        class="special-price">{{$product->discount_price}}৳</span></span>
                            @endif
                            @if($product->discount_price)
                                <span class="old-price"><del>{{$product->sale_price}}৳</del></span>
                            @endif
                        </div>
                        <div class="product-detail-sort-des pb-20">
                            <p>{{$product->description}}</p>
                        </div>
                        <div class="pro-details-list pt-20">
                            <ul>
                                <li><span>SKU :</span>{{$product->sku}}</li>
                                @foreach ($product->attributes as $attr)
                                    <li><span>{{ @$attr->attribute->name }} :</span>{{@$attr->attributeValue->name}}</li>
                                @endforeach
                                <li><span>Category :</span><a
                                        href="{{ route('ecommerce.categoryProducts', $product->category->slug) }}">{{ $product->category->name }}</a>
                                </li>
                                <li><span>Availability :</span> @if($product->quantity > 0)<span class="text-success">In
                                Stock</span>@else <span class="text-danger">Out Of Stock</span>@endif</li>
                            </ul>
                        </div>
                        <div class="pro-quantity-box mb-30">
                            <div class="qty-boxx">
                                <label>qty :</label>
                                <input type="text" id="qty-{{ $product->id }}" placeholder="0" value="1">
                                <button class="btn-cart add-to-cart-btn lg-btn" type="button"
                                    data-product-id="{{ $product->id }}" data-input-id="qty-{{ $product->id }}">add to
                                    cart</button>
                            </div>
                        </div>
                        <div class="useful-links mb-20">
                            <ul>
                                <li>
                                    <a href="#" class="wishlist-btn" data-product-id="{{ $product->id }}"><i
                                            class="fa fa-heart-o"></i>add to wish list</a>
                                </li>
                                <li>
                                    <a href="#" class="compare-btn" data-product-id="{{ $product->id }}"><i
                                            class="fa fa-refresh"></i>compare this product</a>
                                </li>
                            </ul>
                        </div>
                        {{-- <div class="tag-line mb-20">
                            <label>tag :</label>
                            <a href="#">Movado</a>,
                            <a href="#">Omega</a>
                        </div> --}}
                        <div class="pro-social-sharing">
                            <label>share :</label>
                            <ul>
                                <li class="list-inline-item">
                                    <a href="#" class="bg-facebook" title="Facebook">
                                        <i class="fa fa-facebook"></i>
                                        <span>like 0</span>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="bg-twitter" title="Twitter">
                                        <i class="fa fa-twitter"></i>
                                        <span>tweet</span>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="bg-google" title="Google Plus">
                                        <i class="fa fa-google-plus"></i>
                                        <span>google +</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="home-module-three hm-1 fix pb-40">
    <div class="container-fluid">
        <div class="section-title module-three">
            <h3><span>Featured
                </span> Products</h3>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="module-one" role="tabpanel" aria-labelledby="module-one-tab">
                <div class="module-four-wrapper custom-seven-column row">
                    @foreach ($featuredProducts as $product)
                        <div class="col mb-30">
                            <div class="product-item">
                                <div class="product-thumb">
                                    <a href="{{ route('ecommerce.singleProduct',$product->slug) }}">
                                        <img src="{{asset('storage/uploads/pro_image/'.$product->pro_image)}}" class="pri-img"
                                            alt="">
                                    </a>
                                    <div class="box-label">
                                        @if($product->created_at->addDays(10)->gt(\Carbon\Carbon::now()))
                                        <div class="label-product label_new">
                                            <span>new</span>
                                        </div>
                                        @endif
                                        @if($product->discount_price)
                                        <div class="label-product label_sale">
                                            <span>-{{number_format(100-(100*$product->discount_price/$product->sale_price),0)}}%</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="action-links">
                                        <a href="#" title="Wishlist" class="wishlist-btn @if($product->wishlist)active-action @endif" data-product-id="{{ $product->id }}"><i class="lnr lnr-heart"></i></a>
                                        <a href="#" title="Compare" class="compare-btn @if($product->compare)active-action @endif" data-product-id="{{ $product->id }}"><i class="lnr lnr-sync"></i></a>
                                        <a href="#" title="Quick view" data-bs-target="#quickk_view-{{$product->id}}"
                                            data-bs-toggle="modal"><i class="lnr lnr-magnifier"></i></a>
                                    </div>
                                </div>
                                <div class="product-caption">
                                    <div class="manufacture-product">
                                        <p><a href="{{ route('ecommerce.singleProduct',$product->slug) }}">{{@$product->category->name}}</a></p>
                                    </div>
                                    <div class="product-name">
                                        <h4><a href="{{ route('ecommerce.singleProduct',$product->slug) }}">{{$product->name}}</a></h4>
                                    </div>
                                    <div class="ratings">
                                        <span class="yellow"><i class="lnr lnr-star"></i></span>
                                        <span class="yellow"><i class="lnr lnr-star"></i></span>
                                        <span class="yellow"><i class="lnr lnr-star"></i></span>
                                        <span class="yellow"><i class="lnr lnr-star"></i></span>
                                        <span><i class="lnr lnr-star"></i></span>
                                    </div>
                                    <div class="price-box">
                                        @if($product->discount_price == NULL)
                                            <span class="regular-price"><span class="special-price">{{$product->sale_price}}৳</span></span>
                                        @else
                                            <span class="regular-price"><span class="special-price">{{$product->discount_price}}৳</span></span>
                                        @endif
                                        @if($product->discount_price)
                                            <span class="old-price"><del>{{$product->sale_price}}৳</del></span>
                                        @endif
                                    </div>
                                    <button class="btn-cart add-to-cart-btn" type="button" data-product-id="{{ $product->id }}" data-quantity="1">add to cart</button>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="quickk_view-{{$product->id}}">
                            <div class="container">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-5">
                                                    <div class="product-large-slider mb-20">
                                                        <div class="pro-large-img">
                                                            <img src="{{asset('storage/uploads/pro_image/'.$product->pro_image)}}" alt=""/>
                                                        </div>
                                                        <div class="pro-large-img">
                                                            <img src="{{asset('assets/img-ecom/product/product-5.jpg')}}" alt=""/>
                                                        </div>
                                                        <div class="pro-large-img">
                                                            <img src="{{asset('assets/img-ecom/product/product-6.jpg')}}" alt=""/>
                                                        </div>
                                                        <div class="pro-large-img">
                                                            <img src="{{asset('assets/img-ecom/product/product-7.jpg')}}" alt=""/>
                                                        </div>
                                                        <div class="pro-large-img">
                                                            <img src="{{asset('assets/img-ecom/product/product-8.jpg')}}" alt=""/>
                                                        </div>
                                                        <div class="pro-large-img">
                                                            <img src="{{asset('assets/img-ecom/product/product-9.jpg')}}" alt=""/>
                                                        </div>
                                                    </div>
                                                    <div class="pro-nav">
                                                        <div class="pro-nav-thumb"><img src="{{asset('storage/uploads/pro_image/'.$product->pro_image)}}" alt="" /></div>
                                                        <div class="pro-nav-thumb"><img src="{{asset('assets/img-ecom/product/product-5.jpg')}}" alt="" /></div>
                                                        <div class="pro-nav-thumb"><img src="{{asset('assets/img-ecom/product/product-6.jpg')}}" alt="" /></div>
                                                        <div class="pro-nav-thumb"><img src="{{asset('assets/img-ecom/product/product-7.jpg')}}" alt="" /></div>
                                                        <div class="pro-nav-thumb"><img src="{{asset('assets/img-ecom/product/product-8.jpg')}}" alt="" /></div>
                                                        <div class="pro-nav-thumb"><img src="{{asset('assets/img-ecom/product/product-9.jpg')}}" alt="" /></div>
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
                                                                    <li>
                                                                        <span><i class="fa fa-star"></i></span>
                                                                        <span><i class="fa fa-star"></i></span>
                                                                        <span><i class="fa fa-star"></i></span>
                                                                        <span><i class="fa fa-star"></i></span>
                                                                        <span><i class="fa fa-star"></i></span>
                                                                    </li>
                                                                    <li><a href="#">1 Reviews</a></li>
                                                                </ul>
                                                            </div>
                                                            <div class="price-box mb-15">
                                                                @if($product->discount_price == NULL)
                                                                    <span class="regular-price"><span class="special-price">{{$product->sale_price}}৳</span></span>
                                                                @else
                                                                    <span class="regular-price"><span class="special-price">{{$product->discount_price}}৳</span></span>
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
                                                                    <li><span>Availability :</span>In Stock</li>
                                                                </ul>
                                                            </div>
                                                            <div class="pro-quantity-box mb-30">
                                                                <div class="qty-boxx">
                                                                    <label>qty :</label>
                                                                    <input type="text" id="qty-{{ $product->id }}" placeholder="0" value="1">
                                                                    <button class="btn-cart add-to-cart-btn lg-btn" type="button" 
                                                                            data-product-id="{{ $product->id }}" 
                                                                            data-input-id="qty-{{ $product->id }}">add to cart</button>
                                                                </div>
                                                            </div>
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
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@extends('ecommerce.layouts.master')
@section('content')
<div class="breadcrumb-area mb-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-wrap">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$query}}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-wrapper pt-35">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                 <div class="product-shop-main-wrapper mb-50">
                    <div class="shop-top-bar mb-30">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="top-bar-left">
                                    <div class="product-view-mode">
                                        <a class="active" href="#" data-target="column_3"><i
                                                class="fas fa-th-large"></i></a>
                                        <a href="#" data-target="grid"><i class="fas fa-th"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="top-bar-right">
                                    <div class="per-page">
                                        <p>Show : </p>
                                        <select class="nice-select" name="sortbyPage" style="display: none;">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                            <option value="50">50</option>
                                            <option value="60">60</option>
                                            <option value="70">70</option>
                                            <option value="100">100</option>
                                        </select>
                                        <div class="nice-select" tabindex="0"><span class="current">10</span>
                                            <ul class="list">
                                                <li data-value="10" class="option selected">10</li>
                                                <li data-value="20" class="option">20</li>
                                                <li data-value="30" class="option">30</li>
                                                <li data-value="40" class="option">40</li>
                                                <li data-value="50" class="option">50</li>
                                                <li data-value="60" class="option">60</li>
                                                <li data-value="70" class="option">70</li>
                                                <li data-value="100" class="option">100</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-short">
                                        <p>Sort By : </p>
                                        <select class="nice-select" name="sortby" style="display: none;">
                                            <option value="trending">Relevance</option>
                                            <option value="aToZ">Name (A - Z)</option>
                                            <option value="zToA">Name (Z - A)</option>
                                            <option value="lowToHigh">Price (Low &gt; High)</option>
                                            <option value="highToLow">Price (High &gt; Low)</option>
                                        </select>
                                        <div class="nice-select" tabindex="0"><span class="current">Relevance</span>
                                            <ul class="list">
                                                <li data-value="trending" class="option selected">Relevance</li>
                                                <li data-value="aToZ" class="option">Name (A - Z)</li>
                                                <li data-value="zToA" class="option">Name (Z - A)</li>
                                                <li data-value="lowToHigh" class="option">Price (Low &gt; High)</li>
                                                <li data-value="highToLow" class="option">Price (High &gt; Low)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                     <div class="shop-product-wrap grid row">
                        @foreach ($products as $product)
                            
                         <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="product-item mb-30">
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
                                        <a href="#" title="Compare"><i class="lnr lnr-sync"></i></a>
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
                                                            <div class="product-availabily-option mt-15 mb-15">
                                                                <h3>Available Options</h3>
                                                                <div class="color-optionn">
                                                                    <h4><sup>*</sup>color</h4>
                                                                    <ul>
                                                                        <li>
                                                                            <a class="c-black" href="#" title="Black"></a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="c-blue" href="#" title="Blue"></a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="c-brown" href="#" title="Brown"></a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="c-gray" href="#" title="Gray"></a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="c-red" href="#" title="Red"></a>
                                                                        </li>
                                                                    </ul> 
                                                                </div>
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
                     <div class="paginatoin-area style-2 pt-35 pb-20">
                         <div class="row">
                             <div class="col-sm-6">
                                 <div class="pagination-area">
                                     <p>Showing 1 to 9 of 9 (1 Pages)</p>
                                 </div>
                             </div>
                             <div class="col-sm-6">
                                 <ul class="pagination-box pagination-style-2">
                                     <li><a class="Previous" href="#">Previous</a>
                                     </li>
                                     <li class="active"><a href="#">1</a></li>
                                     <li><a href="#">2</a></li>
                                     <li><a href="#">3</a></li>
                                     <li>
                                       <a class="Next" href="#"> Next </a>
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
@endsection
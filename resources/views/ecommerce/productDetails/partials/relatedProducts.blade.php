<div class="related-product-area mb-40">
    <div class="container-fluid">
        <div class="section-title">
            <h3><span>Related</span> product </h3>
        </div>
        <div class="flash-sale-active4 owl-carousel owl-arrow-style">
            @foreach ($relatedProducts as $product)
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
                        <a href="#" title="Wishlist"><i class="lnr lnr-heart"></i></a>
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
                    <button class="btn-cart" type="button">add to cart</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div> 
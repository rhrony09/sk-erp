<div class="featured-categories-area">
    <div class="container-fluid">
        <div class="section-title hm-12">
            <h3><span>Featured</span> category</h3>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="featured-cat-active owl-carousel owl-arrow-style">
                    @foreach ($featuredCategories as $category)
                        <div class="pro-layout-two-single-item">
                            <div class="product-layout-two mb-30">
                                <div class="product-layout-info">
                                    <h4 class="pro-name"><a href="shop-grid-left-sidebar.html">{{$category->name}}</a></h4>
                                    <p class="total-items"> {{@$category->products->count()}} products </p>
                                    <a href="{{route('ecommerce.categoryProducts',$category->slug)}}" class="shop-btn">+ shop now</a>
                                </div>
                                <div class="product-layout-thumb">
                                    <a href="{{route('ecommerce.categoryProducts',$category->slug)}}"><img src="{{asset('/storage/'.$category->thumbnail)}}" alt=""></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                </div>
            </div>
        </div>
    </div>
</div>
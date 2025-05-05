@php
    $logo = \App\Models\Utility::get_file('uploads/logo/');

    $company_logo = Utility::getValByName('company_logo_light');
    $setting = \App\Models\Utility::settings();

    $categories = \App\Models\ProductServiceCategory::where('type', 'product & service')
    ->select('id', 'name', 'slug', 'type')
    ->get();

@endphp


<header class="header-pos">
    <div class="header-top black-bg">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="header-top-left">
                        <ul>
                            <li><span>Email: </span>info@skcorporationbd.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="box box-right">
                        <ul>
                            <li class="settings">
                                <button type="button" class="ha-toggle">My Account<span class="lnr lnr-chevron-down"></span></button>
                                <ul class="box-dropdown ha-dropdown">
                                    @if(auth()->check())
                                    <li><a href="{{ route('ecommerce.myAccount') }}">Dashboard</a></li>
                                    <form action="{{ route('logout') }}" method="post" id="logout-form">
                                        @csrf
                                        <li><a href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();">Logout</a></li>
                                    </form>
                                    @else
                                    <li><a href="{{ route('ecom.register') }}">Register</a></li>
                                    <li><a href="{{ route('ecom.login') }}">Login</a></li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-middle">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-2 col-md-4 col-sm-4 col-12">
                    <div class="logo">
                        <a href="/">
                            <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}" alt="{{ config('app.name', 'SK Corporation') }}">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-12 order-sm-last">
                    <div class="header-middle-inner">
                        <form action="{{ route('ecommerce.searchProducts') }}" method="get">
                            <div class="top-cat hm1">
                                <div class="search-form">
                                    <select name="category">
                                        <option value="all" selected>Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            <input type="text" name="query" class="top-cat-field" placeholder="Search product here">
                            <button type="submit" class="top-search-btn text-white">Search</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 col-md-8 col-12 col-sm-8 order-lg-last">
                    <div class="mini-cart-option">
                        <ul>
                            <li class="compare">
                                <a class="ha-toggle" href="{{ route('ecommerce.comparePage') }}"><span class="lnr lnr-sync"></span><span id="nav-compare-count" class="count"></span>compare</a>
                            </li>
                            <li class="wishlist">
                                <a class="ha-toggle" href="{{ route('ecommerce.wishlistPage') }}"><span class="lnr lnr-heart"></span><span id="nav-wish-count" class="count"></span>wishlist</a>
                            </li>
                            <li class="my-cart">
                                <button type="button" class="ha-toggle"><span class="lnr lnr-cart"></span><span id="nav-cart-count" class="count"></span>my cart</button>
                                <ul class="mini-cart-drop-down ha-dropdown">
                                    
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-top-menu theme-bg sticker">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="top-main-menu">
                        <div class="categories-menu-bar">
                            <div class="categories-menu-btn ha-toggle">
                                <div class="left">
                                    <i class="lnr lnr-text-align-left"></i>
                                    <span>Browse categories</span>
                                </div>
                                <div class="right">
                                    <i class="lnr lnr-chevron-down"></i>
                                </div>
                            </div>
                            <nav class="categorie-menus ha-dropdown">
                                <ul id="menu2">
                                    @foreach ($categories as $index => $category)
                                    <li class="@if($index > 8) category-item-parent hidden @endif"><a href="{{ route('ecommerce.categoryProducts',$category->slug) }}">{{$category->name}}</a>
                                        
                                    </li>
                                    @endforeach
                                    @if($categories->count() > 9)
                                    <li class="category-item-parent"><a class="more-btn" href="#">More Categories</a></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        <div class="main-menu">
                            <nav id="mobile-menu">
                                <ul>
                                    <li><a href="/" class="text-white">HOME</a></li>
                                    
                                    <li><a href="#" class="text-white">ABOUT</a></li>
                                    <li><a href="#" class="text-white">BLOG</a></li>
                                    <li><a href="contact-us.html" class="text-white">CONTACT US</a></li>
                                </ul>
                            </nav>
                        </div> <!-- </div> end main menu -->
                        <div class="header-call-action">
                            <p class="text-white"><span class="lnr lnr-phone"></span>Hotline : <strong>{{$setting['company_telephone']}}</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-block d-lg-none">
                    <div class="mobile-menu"></div>
                </div>
            </div>
        </div>
    </div>
</header>
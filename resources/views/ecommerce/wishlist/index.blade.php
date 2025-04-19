@extends('ecommerce.layouts.master')
@section('content')
<div class="breadcrumb-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-wrap">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wishlist-wrapper mb-55">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <main id="primary" class="site-main">
                    <div class="wishlist">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="section-title">
                                    <h3>Wishlist</h3>
                                </div>
                                <form action="#">
                                    <div id="wishlist-container" class="table-responsive text-center wishlist-style">
                                        {{-- <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Image</td>
                                                    <td>Product Name</td>
                                                    <td>Stock</td>
                                                    <td>Unit Price</td>
                                                    <td>Action</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="product-details.html"><img src="assets/img/product/pro-layout-img6.jpg" alt="Wishlist Product Image" title="Compete Track Tote"></a>
                                                    </td>
                                                    <td>
                                                        <a href="product-details.html">Compete Track Tote</a>
                                                    </td>
                                                    <td>In Stock</td>
                                                    <td>
                                                        <div class="price"><small><del>$430.00</del></small> <strong>$100.00</strong></div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary"><i class="fa fa-shopping-cart"></i></button>
                                                        <a href="#" class="btn btn-danger"><i class="fa fa-times"></i></a>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table> --}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- end of wishlist -->
                </main> <!-- end of #primary -->
            </div>
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div>
@endsection
@extends('ecommerce.layouts.master')
@section('content')
@include('ecommerce.home.partials.banner')
@include('ecommerce.home.partials.featured-categories',['featuredCategories'=>$featuredCategories])
@include('ecommerce.home.partials.hot-collection',['featuredProducts' => $featuredProducts])
@include('ecommerce.home.partials.featured-options')
@include('ecommerce.home.partials.category-products')
@include('ecommerce.home.partials.brand-sale')
@endsection
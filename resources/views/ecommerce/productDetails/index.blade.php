@extends('ecommerce.layouts.master')

@section('content')
@include('ecommerce.productDetails.partials.details', ['product' => $product])
@include('ecommerce.productDetails.partials.reviewTab',['product' => $product,'description' => $product->description])
@include('ecommerce.productDetails.partials.relatedProducts',['relatedProducts' => $relatedProducts])
@endsection
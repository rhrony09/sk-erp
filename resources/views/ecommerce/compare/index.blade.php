@extends('ecommerce.layouts.master')
@section('content')
    @php
        // Collect unique attributes across compared products
        $allAttributes = $compares->flatMap(function ($compare) {
            return $compare->product->attributes->map(function ($productAttribute) {
                return [
                    'name' => $productAttribute->attributeValue->attribute->name,
                    'attribute_name' => $productAttribute->attributeValue->name
                ];
            });
        })->unique('name')->values();
    @endphp
    <div class="breadcrumb-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Compare</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="comparison-wrapper pb-50">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <main id="primary" class="site-main">
                        <div class="comparison">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="section-title">
                                        <h3>Product Comparison</h3>
                                    </div>
                                    <div>
                                        <div id="compare-container" class="table-responsive  text-center">
                                            <table class="table table-bordered compare-style">
                                                <thead>
                                                    <tr>
                                                        <td colspan="{{ count($compares) + 1 }}"><strong>Product
                                                                Details</strong></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="product-title">Product</td>
                                                        @foreach($compares as $compare)
                                                            <td><a
                                                                    href="{{ route('ecommerce.singleProduct', $compare->product->slug) }}"><strong>{{ $compare->product->name }}</strong></a>
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                    <tr>
                                                        <td class="product-title">Image</td>
                                                        @foreach($compares as $compare)
                                                            <td>
                                                                <img src="{{asset('storage/uploads/pro_image/' . $compare->product->pro_image)}}"
                                                                    alt="{{ $compare->product->name }}" class="img-thumbnail"
                                                                    style="max-width: 150px; width: 100%;">
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                    <tr>
                                                        <td class="product-title">Price</td>
                                                        @foreach($compares as $compare)
                                                            <td>
                                                                @if($compare->product->discount_price)
                                                                    <del>${{ number_format($compare->product->sale_price, 2) }}</del>
                                                                    <span>${{ number_format($compare->product->discount_price, 2) }}</span>
                                                                @else
                                                                    <span>${{ number_format($compare->product->sale_price, 2) }}</span>
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                    <tr>
                                                        <td class="product-title">Availability</td>
                                                        @foreach($compares as $compare)
                                                            <td>{{ $compare->product->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                    <tr>
                                                        <td class="product-title">Rating</td>
                                                        @foreach($compares as $compare)
                                                            <td>
                                                                <div class="product-ratings d-flex justify-content-center mb-2">
                                                                    <ul class="rating d-flex">
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            @if($i <= $compare->product->average_rating)
                                                                                <li><i class="fa fa-star"></i></li>
                                                                            @else
                                                                                <li><i class="fa fa-star disabled"></i></li>
                                                                            @endif
                                                                        @endfor
                                                                    </ul>
                                                                </div>
                                                                <span>Based on {{ $compare->product->reviews_count ?? 0 }}
                                                                    reviews.</span>
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                    <tr>
                                                        <td class="product-title">Summary</td>
                                                        @foreach($compares as $compare)
                                                            <td class="description">
                                                                {{ Str::limit($compare->product->description, 150) }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                    @foreach($allAttributes as $attribute)
                                                        <tr>
                                                            <td class="product-title">{{ ucfirst($attribute['name']) }}</td>
                                                            @foreach($compares as $compare)
                                                                <td>
                                                                    @php
                                                                        $productAttribute = $compare->product->attributes
                                                                            ->first(function ($pa) use ($attribute) {
                                                                                return $pa->attributeValue->attribute->name === $attribute['name'];
                                                                            });
                                                                    @endphp
                                                                    
                                                                    @if($productAttribute)
                                                                        {{ $productAttribute->attributeValue->name }}
                                                                    @else
                                                                        <span class="text-muted">N/A</span>
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td class="product-title">Actions</td>
                                                        @foreach($compares as $compare)
                                                            <td>
                                                                <a href="#"
                                                                    class="btn btn-secondary mb-2 mb-lg-0 mr-xl-2 add-to-cart-btn"
                                                                    data-product-id="{{ $compare->product->id }}"
                                                                    data-quantity="1">Add to Cart
                                                                </a>
                                                                {{-- <a href="#"
                                                                    class="btn btn-danger mb-2 mb-lg-0 mr-xl-2 delete-compare"
                                                                    data-id="{{ $compare->id }}"
                                                                    >Delete
                                                                </a> --}}
                                                                <form action="{{ route('ecommerce.deleteCompare', $compare->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-secondary">Remove</button>
                                                                </form>
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end of comparison -->
                    </main> <!-- end of #primary -->
                </div>
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div>
@endsection
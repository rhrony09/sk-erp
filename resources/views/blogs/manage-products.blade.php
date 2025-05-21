@extends('layouts.admin')
@section('page-title')
    {{__('Manage Blog Products')}} - {{ $blog->title }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('ecommerce.blog.index')}}">{{__('Blog Posts')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('ecommerce.blog.edit', $blog->id)}}">{{__('Edit')}}</a></li>
    <li class="breadcrumb-item">{{__('Manage Products')}}</li>
@endsection

@section('content')
    <div class="row">
        <!-- Blog Info Card -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Blog Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3>{{ $blog->title }}</h3>
                            <p>{{ Str::limit($blog->description, 200) }}</p>
                            <div class="mt-3">
                                <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> {{ __('Edit Blog') }}
                                </a>
                                <a href="{{ route('ecommerce.blog.show', $blog->slug) }}" class="btn btn-sm btn-info" target="_blank">
                                    <i class="fas fa-eye"></i> {{ __('View Blog') }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if($blog->thumb)
                                <div class="blog-image-preview mb-2">
                                    <img src="{{ asset('storage/'.$blog->thumb) }}" alt="{{ $blog->title }}" class="img-fluid rounded">
                                </div>
                            @elseif($blog->featured_image)
                                <div class="blog-image-preview mb-2">
                                    <img src="{{ asset('storage/'.$blog->featured_image) }}" alt="{{ $blog->title }}" class="img-fluid rounded">
                                </div>
                            @endif
                            <div class="blog-meta mt-2">
                                <p><strong>{{ __('Status') }}:</strong> 
                                    @if($blog->is_published)
                                        <span class="badge bg-success">{{ __('Published') }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ __('Draft') }}</span>
                                    @endif
                                </p>
                                <p><strong>{{ __('Published Date') }}:</strong> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : __('Not Set') }}</p>
                                <p><strong>{{ __('Views') }}:</strong> {{ $blog->view_count }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Related Products -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>{{ __('Related Products') }} ({{ count($relatedProducts) }})</h5>
                        @if(count($relatedProducts) > 1)
                            <button type="button" id="update-order" class="btn btn-sm btn-success">
                                <i class="fas fa-sort"></i> {{ __('Update Order') }}
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(count($relatedProducts) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 70px">{{ __('Image') }}</th>
                                        <th>{{ __('Product') }}</th>
                                        <th style="width: 100px">{{ __('Price') }}</th>
                                        <th style="width: 90px">{{ __('Order') }}</th>
                                        <th style="width: 80px">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="related-products-list">
                                    @foreach($relatedProducts as $product)
                                    <tr data-product-id="{{ $product->id }}" data-relation-id="{{ $product->pivot->id }}">
                                        <td>
                                            @if(!empty($product->image) && \Storage::exists($product->image))
                                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 60px; max-height: 60px;">
                                            @else
                                                <div class="no-image" style="width: 60px; height: 60px; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('ecommerce.singleProduct', $product->slug ?? 'product') }}" target="_blank">
                                                {{ $product->name }}
                                            </a>
                                            <div class="text-muted small">{{ Str::limit($product->sku, 20) }}</div>
                                        </td>
                                        <td>
                                            @if($product->sale_price && $product->sale_price < $product->purchase_price)
                                                <span class="text-success">৳ {{ number_format($product->sale_price, 2) }}</span>
                                                <div class="text-muted small"><del>৳ {{ number_format($product->purchase_price, 2) }}</del></div>
                                            @else
                                                <span>৳ {{ number_format($product->purchase_price, 2) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" min="0" value="{{ $product->pivot->order }}" class="form-control form-control-sm product-order">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-related-product">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            {{ __('No related products added yet. Use the form on the right to add products.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Add Products Form -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Add Products') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Single Product Add -->
                    <form action="#" method="POST" id="add-single-product-form">
                        @csrf
                        <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                        
                        <div class="form-group mb-3">
                            <label for="product-select" class="form-label">{{ __('Select Product') }}</label>
                            <select id="product-select" name="product_id" class="form-control select2">
                                <option value="">{{ __('Select a product') }}</option>
                                @foreach($productServices as $product)
                                    @if(!$relatedProducts->contains('id', $product->id))
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="product-order" class="form-label">{{ __('Display Order') }}</label>
                            <input type="number" id="product-order" name="order" class="form-control" min="0" value="{{ count($relatedProducts) }}">
                            <small class="form-text text-muted">{{ __('Products with lower order values will be displayed first.') }}</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="button" id="add-single-product" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('Add Product') }}
                            </button>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <!-- Bulk Products Add -->
                    <form action="{{ route('ecommerce.blog.related.add-multiple') }}" method="POST">
                        @csrf
                        <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                        
                        <div class="form-group mb-3">
                            <label for="multiple-products-select" class="form-label">{{ __('Select Multiple Products') }}</label>
                            <select id="multiple-products-select" name="product_ids[]" class="form-control select2" multiple>
                                @foreach($productServices as $product)
                                    @if(!$relatedProducts->contains('id', $product->id))
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="form-text text-muted">{{ __('Hold Ctrl/Cmd key to select multiple products.') }}</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus-circle"></i> {{ __('Add Selected Products') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2();
        
        // Add single product
        $('#add-single-product').on('click', function() {
            var productId = $('#product-select').val();
            var order = $('#product-order').val();
            
            if (!productId) {
                toastr.error('{{ __("Please select a product") }}');
                return;
            }
            
            $.ajax({
                url: '{{ route("ecommerce.blog.related.add") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    blog_id: {{ $blog->id }},
                    product_id: productId,
                    order: order
                },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        // Reload the page to show updated products
                        window.location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('{{ __("Something went wrong") }}');
                    }
                }
            });
        });
        
        // Remove related product
        $(document).on('click', '.remove-related-product', function() {
            var row = $(this).closest('tr');
            var productId = row.data('product-id');
            
            if (confirm('{{ __("Are you sure you want to remove this product?") }}')) {
                $.ajax({
                    url: '{{ route("ecommerce.blog.related.remove") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        blog_id: {{ $blog->id }},
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            row.fadeOut(300, function() {
                                $(this).remove();
                                
                                // If no more products, show the info message
                                if ($('#related-products-list tr').length === 0) {
                                    $('.table-responsive').replaceWith(
                                        '<div class="alert alert-info">' +
                                        '{{ __("No related products added yet. Use the form on the right to add products.") }}' +
                                        '</div>'
                                    );
                                    $('#update-order').hide();
                                }
                            });
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('{{ __("Something went wrong") }}');
                    }
                });
            }
        });
        
        // Update product order
        $('#update-order').on('click', function() {
            var items = [];
            
            $('#related-products-list tr').each(function() {
                var row = $(this);
                var relationId = row.data('relation-id');
                var order = row.find('.product-order').val();
                
                items.push({
                    id: relationId,
                    order: order
                });
            });
            
            $.ajax({
                url: '{{ route("ecommerce.blog.related.order") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    items: items
                },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('{{ __("Something went wrong") }}');
                }
            });
        });
    });
</script>
@endpush 
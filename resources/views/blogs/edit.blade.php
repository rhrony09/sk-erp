@extends('layouts.admin')
@section('page-title')
    {{__('Edit Blog Post')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('blog.blogList')}}">{{__('Blog Posts')}}</a></li>
    <li class="breadcrumb-item">{{__('Edit')}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($blog, array('route' => array('blogs.update', $blog->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}
                                {{ Form::text('title', null, array('class' => 'form-control', 'required'=>'required')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                                {{ Form::textarea('description', null, array('class' => 'form-control', 'rows' => 3, 'required'=>'required')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('content', __('Content'), ['class' => 'form-label']) }}
                                {{ Form::textarea('content', null, array('class' => 'form-control summernote-simple', 'required'=>'required')) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('thumb', __('Thumbnail'), ['class' => 'form-label']) }}
                                <div class="choose-file form-group">
                                    <label for="thumb" class="form-label">
                                        <div>{{__('Choose file here')}}</div>
                                        <input type="file" class="form-control" name="thumb" id="thumb" data-filename="thumb_filename">
                                    </label>
                                    <p class="thumb_filename"></p>
                                </div>
                                @if($blog->thumb)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/'.$blog->thumb) }}" class="img-fluid img-thumbnail" style="max-height: 150px;" alt="{{ $blog->title }}">
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                {{ Form::label('featured_image', __('Featured Image'), ['class' => 'form-label']) }}
                                <div class="choose-file form-group">
                                    <label for="featured_image" class="form-label">
                                        <div>{{__('Choose file here')}}</div>
                                        <input type="file" class="form-control" name="featured_image" id="featured_image" data-filename="featured_image_filename">
                                    </label>
                                    <p class="featured_image_filename"></p>
                                </div>
                                @if($blog->featured_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/'.$blog->featured_image) }}" class="img-fluid img-thumbnail" style="max-height: 150px;" alt="{{ $blog->title }}">
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                {{ Form::label('meta_title', __('Meta Title'), ['class' => 'form-label']) }}
                                {{ Form::text('meta_title', null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('meta_description', __('Meta Description'), ['class' => 'form-label']) }}
                                {{ Form::textarea('meta_description', null, array('class' => 'form-control', 'rows' => 2)) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('meta_keywords', __('Meta Keywords'), ['class' => 'form-label']) }}
                                {{ Form::text('meta_keywords', null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('published_at', __('Publish Date'), ['class' => 'form-label']) }}
                                {{ Form::date('published_at', null, array('class' => 'form-control', 'required' => 'required')) }}
                            </div>

                            <div class="form-group form-check">
                                <input type="hidden" name="is_published" value="0">
                                {{ Form::checkbox('is_published', '1', null, ['class' => 'form-check-input', 'id' => 'is_published']) }}
                                {{ Form::label('is_published', __('Published'), ['class' => 'form-check-label']) }}
                            </div>

                            <div class="form-group">
                                <p><strong>{{ __('Created By') }}:</strong> {{ $blog->author ? $blog->author->name : __('Unknown') }}</p>
                                <p><strong>{{ __('Created At') }}:</strong> {{ $blog->created_at->format('M d, Y H:i') }}</p>
                                <p><strong>{{ __('Last Updated') }}:</strong> {{ $blog->updated_at->format('M d, Y H:i') }}</p>
                                <p><strong>{{ __('View Count') }}:</strong> {{ $blog->view_count }}</p>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group text-right">
                                <input type="submit" value="{{__('Update')}}" class="btn-create badge-blue">
                                <a href="{{ route('blogs.index') }}" class="btn-create bg-gray">{{__('Cancel')}}</a>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Related Products') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Current Related Products -->
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5>{{ __('Product List') }} ({{ count($relatedProducts) }})</h5>
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
<script>
    $(document).ready(function() {
        $('.summernote-simple').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
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
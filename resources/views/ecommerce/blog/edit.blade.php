@extends('layouts.admin')
@section('page-title')
    {{__('Edit Blog Post')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('ecommerce.blog.index')}}">{{__('Blog Posts')}}</a></li>
    <li class="breadcrumb-item">{{__('Edit')}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($blog, array('route' => array('ecommerce.blog.update', $blog->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}
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
                                <a href="{{ route('ecommerce.blog.index') }}" class="btn-create bg-gray">{{__('Cancel')}}</a>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>{{ __('Related Products') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Product Selector -->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="product-select" class="form-label">{{ __('Add Related Product') }}</label>
                                <select id="product-select" class="form-control select2">
                                    <option value="">{{ __('Select a product') }}</option>
                                    @foreach($productServices as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" id="add-related-product" class="btn btn-sm btn-primary mt-2">
                                    {{ __('Add Product') }}
                                </button>
                            </div>
                        </div>

                        <!-- Related Products List -->
                        <div class="col-md-7">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Product') }}</th>
                                            <th>{{ __('Order') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="related-products-list">
                                        @foreach($relatedProducts as $product)
                                            <tr data-product-id="{{ $product->id }}">
                                                <td>{{ $product->name }}</td>
                                                <td>
                                                    <input type="number" min="0" value="{{ $product->pivot->order }}" class="form-control form-control-sm product-order" style="width: 80px;">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger remove-related-product">{{ __('Remove') }}</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(count($relatedProducts) > 0)
                                <button type="button" id="update-order" class="btn btn-sm btn-success mt-2">
                                    {{ __('Update Order') }}
                                </button>
                            @else
                                <p class="text-muted">{{ __('No related products yet.') }}</p>
                            @endif
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
        
        // Add related product
        $('#add-related-product').on('click', function() {
            var productId = $('#product-select').val();
            if (!productId) {
                toastr.error('{{ __("Please select a product") }}');
                return;
            }
            
            $.ajax({
                url: '{{ route('ecommerce.blog.related.add') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    blog_id: {{ $blog->id }},
                    product_id: productId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        
                        // Add product to the list
                        var newRow = '<tr data-product-id="' + response.product.id + '">' +
                            '<td>' + response.product.name + '</td>' +
                            '<td><input type="number" min="0" value="' + response.related_product.order + '" class="form-control form-control-sm product-order" style="width: 80px;"></td>' +
                            '<td><button type="button" class="btn btn-sm btn-danger remove-related-product">{{ __('Remove') }}</button></td>' +
                            '</tr>';
                        
                        $('#related-products-list').append(newRow);
                        
                        // Reset the select
                        $('#product-select').val('').trigger('change');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('{{ __("Something went wrong") }}');
                }
            });
        });
        
        // Remove related product
        $(document).on('click', '.remove-related-product', function() {
            var row = $(this).closest('tr');
            var productId = row.data('product-id');
            
            $.ajax({
                url: '{{ route('ecommerce.blog.related.remove') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    blog_id: {{ $blog->id }},
                    product_id: productId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        row.remove();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('{{ __("Something went wrong") }}');
                }
            });
        });
        
        // Update order
        $('#update-order').on('click', function() {
            var items = [];
            
            $('#related-products-list tr').each(function() {
                var row = $(this);
                var productId = row.data('product-id');
                var order = row.find('.product-order').val();
                
                items.push({
                    id: productId,
                    order: order
                });
            });
            
            $.ajax({
                url: '{{ route('ecommerce.blog.related.order') }}',
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
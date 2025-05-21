@extends('layouts.admin')
@section('page-title')
    {{__('Create Blog Post')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('blog.blogList')}}">{{__('Blog Posts')}}</a></li>
    <li class="breadcrumb-item">{{__('Create')}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(array('route' => 'ecommerce.blog.store', 'method'=>'POST', 'enctype' => 'multipart/form-data')) }}
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
                                {{ Form::date('published_at', date('Y-m-d'), array('class' => 'form-control', 'required' => 'required')) }}
                            </div>

                            <div class="form-group form-check">
                                <input type="hidden" name="is_published" value="0">
                                <input type="checkbox" class="form-check-input" name="is_published" id="is_published" value="1" checked>
                                <label class="form-check-label" for="is_published">{{__('Publish Immediately')}}</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group text-right">
                                <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
                                <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
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
    });
</script>
@endpush 
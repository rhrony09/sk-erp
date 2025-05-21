@extends('layouts.admin')
@section('page-title')
    {{__('Blog Posts')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Blog Posts')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="{{ route('blogs.create') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Thumbnail')}}</th>
                                    <th>{{__('Published Date')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Views')}}</th>
                                    <th width="10%">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($blogs as $blog)
                                    <tr>
                                        <td>
                                            <a href="{{ route('ecommerce.blog.show', $blog->slug) }}" class="text-primary">
                                                {{ $blog->title }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($blog->thumb)
                                                <img src="{{ asset('storage/'.$blog->thumb) }}" class="img-fluid img-thumbnail" style="max-width: 80px;" alt="{{ $blog->title }}">
                                            @else
                                                <span class="badge bg-secondary">{{ __('No Image') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : __('Not Published') }}</td>
                                        <td>
                                            @if($blog->is_published)
                                                <span class="badge bg-success">{{ __('Published') }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ __('Draft') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $blog->view_count }}</td>
                                        <td class="Action">
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="{{ route('blogs.edit', $blog->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-info ms-2">
                                                <a href="{{ route('ecommerce.blog.show', $blog->slug) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('View')}}" data-original-title="{{__('View')}}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-success ms-2">
                                                <a href="{{ route('ecommerce.blog.products.manage', $blog->id) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Manage Products')}}" data-original-title="{{__('Manage Products')}}">
                                                    <i class="ti ti-shopping-cart text-white"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['blogs.destroy', $blog->id], 'id' => 'delete-form-'.$blog->id]) !!}
                                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?')}}" data-confirm-yes="document.getElementById('delete-form-{{$blog->id}}').submit();">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                                {!! Form::close() !!}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.copy_link', function (e) {
                e.preventDefault();
                var copyText = $(this).attr('href');

                document.addEventListener('copy', function(e) {
                    e.clipboardData.setData('text/plain', copyText);
                    e.preventDefault();
                }, true);

                document.execCommand('copy');
                show_toastr('Success', 'Url copied to clipboard', 'success');
            });
        });
    </script>
@endpush
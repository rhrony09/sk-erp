@extends('layouts.admin')
@section('page-title')
    {{__('Manage Invoice Category')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Invoice Category')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createCategory">
            <i class="ti ti-plus"></i> {{__('Create Invoice Category')}}
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{__('Category Name')}}</th>
                                    <th>{{__('Footer Note')}}</th>
                                    <th>{{__('Created At')}}</th>
                                    <th width="200px">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoiceCategories as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>{!! $category->footer_note !!}</td>
                                        <td>{{ $category->created_at }}</td>
                                        <td>
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#editCategory{{ $category->id }}" title="{{__('Edit')}}">
                                                    <i class="ti ti-edit text-primary"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-light-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.category.destroy', $category->id], 'id' => 'delete-category-' . $category->id]) !!}
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}">
                                                    <i class="ti ti-trash text-danger"></i>
                                                </a>
                                                {!! Form::close() !!}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">{{__('No Data Found')}}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div class="modal fade" id="createCategory" tabindex="-1" role="dialog" aria-labelledby="createCategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryLabel">{{__('Create Invoice Category')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('invoice.category.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">{{__('Category Name')}}</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">{{__('Footer Note')}}</label>
                            <textarea id="create_footer_note" class="form-control" name="footer_note"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modals -->
    @foreach($invoiceCategories as $category)
        <div class="modal fade" id="editCategory{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editCategoryLabel{{ $category->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryLabel{{ $category->id }}">{{__('Edit Invoice Category')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="{{ route('invoice.category.update', $category->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">{{__('Category Name')}}</label>
                                <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                            </div>
                            <div class="form-group mt-3">
                                <label class="form-label">{{__('Footer Note')}}</label>
                                <textarea id="edit_footer_note{{ $category->id }}" class="form-control" name="footer_note">{{ $category->footer_note }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.2/jodit.min.css" rel="stylesheet">
@endpush

@push('script-page')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.2/jodit.min.js"></script>
    <script>
        // Initialize Jodit for create modal
        const createEditor = new Jodit('#create_footer_note', {
            height: 300,
            toolbar: true,
            buttons: [
                'source', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'font', 'fontsize', 'brush', 'paragraph', '|',
                'align', '|',
                'ul', 'ol', '|',
                'table', 'link', '|',
                'undo', 'redo', '|',
                'hr', 'eraser', 'copyformat', '|',
                'symbol', 'fullsize', 'print', 'about'
            ],
            uploader: {
                insertImageAsBase64URI: true
            },
            removeButtons: ['image'],
            showCharsCounter: true,
            showWordsCounter: true,
            showXPathInStatusbar: false
        });

        // Initialize Jodit for edit modals
        @foreach($invoiceCategories as $category)
            const editEditor{{ $category->id }} = new Jodit('#edit_footer_note{{ $category->id }}', {
                height: 300,
                toolbar: true,
                buttons: [
                    'source', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'font', 'fontsize', 'brush', 'paragraph', '|',
                    'align', '|',
                    'ul', 'ol', '|',
                    'table', 'link', '|',
                    'undo', 'redo', '|',
                    'hr', 'eraser', 'copyformat', '|',
                    'symbol', 'fullsize', 'print', 'about'
                ],
                uploader: {
                    insertImageAsBase64URI: true
                },
                removeButtons: ['image'],
                showCharsCounter: true,
                showWordsCounter: true,
                showXPathInStatusbar: false
            });
        @endforeach

        // Delete confirmation
        $(document).on('click', '.show_confirm', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: '{{__("Are you sure?")}}',
                text: '{{__("This action can not be undone. Do you want to continue?")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{__("Yes, delete it!")}}',
                cancelButtonText: '{{__("Cancel")}}',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush

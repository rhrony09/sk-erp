@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Product Attribute Value') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ $attribute->name }}</li>
@endsection

@section('action-btn')
    <div class="float-end">

        <a href="#" data-url="{{ route('attributevalue.create', $attribute->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create') }}" title="{{ __('Create') }}" data-title="{{ __('Create New Attribute Value') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
            Add Attribute Value
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-3">
            @include('layouts.account_setup')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th> {{ __('Attribute Value') }}</th>
                                    <th width="10%"> {{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attribute->values as $value)
                                    <tr>
                                        <td class="font-style">{{ $value->name }}</td>
                                        <td class="Action">
                                            <span>
                                                @can('edit constant category')
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('attributevalue.edit', $value->id) }}" data-ajax-popup="true" data-title="{{ __('Edit Attribute Value') }}" data-bs-toggle="tooltip" title="{{ __('Create') }}" data-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('delete constant category')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['attributevalue.delete', $value->id], 'id' => 'delete-form-' . $value->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{ __('Delete') }}" data-original-title="{{ __('Delete') }}" data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form-{{ $value->id }}').submit();">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </span>
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
@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Raw Materials') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Product & Services') }}</li>
    <li class="breadcrumb-item">{{ __('Raw Materials') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        {{-- <a href="#" data-size="md" data-bs-toggle="tooltip" title="{{ __('Import') }}" data-url="{{ route('productservice.file.import') }}" data-ajax-popup="true" data-title="{{ __('Import product CSV file') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-import"></i>
        </a>
        <a href="{{ route('productservice.export') }}" data-bs-toggle="tooltip" title="{{ __('Export') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-export"></i>
        </a> --}}

        <a href="#" data-size="lg" data-url="{{ route('productservice.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create New Product') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class=" mt-2 {{ isset($_GET['category']) ? 'show' : '' }}" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['productservice.raw_material.index'], 'method' => 'GET', 'id' => 'raw_materials']) }}
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6">
                                <div class="btn-box">
                                    {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}
                                    {{ Form::select('category', $category, null, ['class' => 'form-control select', 'id' => 'choices-multiple', 'required' => 'required']) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('raw_materials').submit(); return false;" data-bs-toggle="tooltip" title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('productservice.raw_material.index') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off "></i></span>
                                </a>
                            </div>

                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Sku') }}</th>
                                    <th>{{ __('Sale Price') }}</th>
                                    <th>{{ __('Purchase Price') }}</th>
                                    <th>{{ __('Tax') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($raw_materials as $raw_material)
                                    <tr class="font-style">
                                        <td>{{ $raw_material->name }}</td>
                                        <td>{{ $raw_material->sku }}</td>
                                        <td>{{ \Auth::user()->priceFormat($raw_material->sale_price) }}</td>
                                        <td>{{ \Auth::user()->priceFormat($raw_material->purchase_price) }}</td>
                                        <td>
                                            @if (!empty($raw_material->tax_id))
                                                @php
                                                    $itemTaxes = [];
                                                    $getTaxData = Utility::getTaxData();

                                                    foreach (explode(',', $raw_material->tax_id) as $tax) {
                                                        $itemTax['name'] = $getTaxData[$tax]['name'];
                                                        $itemTax['rate'] = $getTaxData[$tax]['rate'] . '%';

                                                        $itemTaxes[] = $itemTax;
                                                    }
                                                    $raw_material->itemTax = $itemTaxes;
                                                @endphp
                                                @foreach ($raw_material->itemTax as $tax)
                                                    <span>{{ $tax['name'] . ' (' . $tax['rate'] . ')' }}</span><br>
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ !empty($raw_material->category) ? $raw_material->category->name : '' }}</td>
                                        <td>{{ !empty($raw_material->unit) ? $raw_material->unit->name : '' }}</td>
                                        {{-- @if ($raw_material->type == 'product') --}}
                                        <td>{{ $raw_material->quantity }}</td>
                                        {{-- @else
                                        <td>-</td>
                                    @endif --}}
                                        <td>{{ ucwords($raw_material->type) }}</td>

                                        @if (Gate::check('edit product & service') || Gate::check('delete product & service'))
                                            <td class="Action">

                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('productservice.raw_material.detail', $raw_material->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Warehouse Details') }}" data-title="{{ __('Warehouse Details') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
                                                </div>

                                                @can('edit product & service')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('productservice.raw_material.edit', $raw_material->id) }}" data-ajax-popup="true" data-size="lg " data-bs-toggle="tooltip" title="{{ __('Edit') }}" data-title="{{ __('Edit Product') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('delete product & service')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['productservice.raw_material.destroy', $raw_material->id], 'id' => 'delete-form-' . $raw_material->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i class="ti ti-trash text-white"></i></a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </td>
                                        @endif
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

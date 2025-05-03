@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Product & Services') }}
@endsection
@push('script-page')
    <script>
        var rowIdx = 2;

        $('#commonModal').on('click', '.addNewRow', function() {
            // alert();
            let html = `
                        <div class="row">
                            <div class="form-group col-md-4">
                                {{ Form::label('raw_material_id', __('Raw Material'), ['class' => 'form-label']) }}<span
                                    class="text-danger">*</span>
                                {{ Form::select('raw_material_id[]', $raw_materials, null, ['class' => 'form-control select', 'id' => 'raw_material_id` + rowIdx + `', 'required' => 'required']) }}
                            </div>
                            <div class="col-md-4">
                                {{ Form::label('quantity', __('Quantity'), ['class' => 'form-label']) }}<span
                                    class="text-danger">*</span>
                                {{ Form::number('quantity[]', null, ['class' => 'form-control', 'id' => 'quantity` + rowIdx + `', 'required' => 'required', 'step' => '0.01']) }}
                            </div>
                            <div class="col-md-4 pt-4">
                                <div class="form-group mt-2">
                                    <button type="button" class="btn btn-primary btn-sm f-14 addNewRow"><i
                                            class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm f-14 remove" name="button"><i
                                            class="fa fa-minus"></i></button>
                                </div>
                            </div>
                        </div>
                        `;

            $(this).closest('div[class=row]').after(html);

            $('.select2').select2();

            rowIdx++;
        });

        $('#commonModal').on('click', '.remove', function() {
            $(this).closest('div[class=row]').remove();
        });
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Product & Services') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" data-size="md" data-bs-toggle="tooltip" title="{{ __('Import') }}"
            data-url="{{ route('productservice.file.import') }}" data-ajax-popup="true"
            data-title="{{ __('Import product CSV file') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-import"></i>
        </a>
        <a href="{{ route('productservice.export') }}" data-bs-toggle="tooltip" title="{{ __('Export') }}"
            class="btn btn-sm btn-primary">
            <i class="ti ti-file-export"></i>
        </a>

        <a href="#" data-size="lg" data-url="{{ route('productservice.create') }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" title="{{ __('Create New Product') }}" class="btn btn-sm btn-primary">
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
                        {{ Form::open(['route' => ['productservice.index'], 'method' => 'GET', 'id' => 'product_service']) }}
                        <div class="d-flex align-items-center justify-content-end">
                            @if($role->role_id == 10)
                            <div class="col-xl-3 col-lg-3 col-md-6 me-1">
                                <div class="btn-box">
                                    {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                                    {{ Form::select('branch', $branch, null, ['class' => 'form-control select', 'id' => 'choices-multiple', 'required' => 'required']) }}
                                </div>
                            </div>
                            @endif
                            <div class="col-xl-3 col-lg-3 col-md-6">
                                <div class="btn-box">
                                    {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}
                                    {{ Form::select('category', $category, null, ['class' => 'form-control select', 'id' => 'choices-multiple', 'required' => 'required']) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('product_service').submit(); return false;"
                                    data-bs-toggle="tooltip" title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('productservice.index') }}" class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip" title="{{ __('Reset') }}">
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
                                    @if($role->role_id != 10)
                                    <th>{{ __('Branch Quantity') }}</th>
                                    @endif
                                    <th>{{ __('Total Quantity') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productServices as $productService)
                                    <tr class="font-style">
                                        <td>{{ $productService->name }}</td>
                                        <td>{{ $productService->sku }}</td>
                                        <td>{{ \Auth::user()->priceFormat($productService->sale_price) }}</td>
                                        <td>{{ \Auth::user()->priceFormat($productService->purchase_price) }}</td>
                                        <td>
                                            @if (!empty($productService->tax_id))
                                                @php
                                                    $itemTaxes = [];
                                                    $getTaxData = Utility::getTaxData();
                        
                                                    foreach (explode(',', $productService->tax_id) as $tax) {
                                                        $itemTax['name'] = $getTaxData[$tax]['name'];
                                                        $itemTax['rate'] = $getTaxData[$tax]['rate'] . '%';
                        
                                                        $itemTaxes[] = $itemTax;
                                                    }
                                                    $productService->itemTax = $itemTaxes;
                                                @endphp
                                                @foreach ($productService->itemTax as $tax)
                                                    <span>{{ $tax['name'] . ' (' . $tax['rate'] . ')' }}</span><br>
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ !empty($productService->category) ? $productService->category->name : '' }}</td>
                                        <td>{{ !empty($productService->unit) ? $productService->unit->name : '' }}</td>
                                        @if($role->role_id != 10)
                                        <td>
                                            @if ($productService->type == 'product')
                                                <span class="{{ $productService->branch_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $productService->branch_quantity }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @endif
                                        <td>
                                            @if ($productService->type == 'product')
                                                {{ $productService->quantity }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ ucwords($productService->type) }}</td>
                        
                                        @if (Gate::check('edit product & service') || Gate::check('delete product & service'))
                                            <td class="Action">

                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center"
                                                        data-url="{{ route('productservice.detail', $productService->id) }}"
                                                        data-ajax-popup="true" data-bs-toggle="tooltip"
                                                        title="{{ __('Warehouse Details') }}"
                                                        data-title="{{ __('Warehouse Details') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
                                                </div>

                                                @can('edit product & service')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-url="{{ route('productservice.edit', $productService->id) }}"
                                                            data-ajax-popup="true" data-size="lg " data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}" data-title="{{ __('Edit Product') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-url="{{ route('productservice.add_raw_materials', $productService->id) }}"
                                                            data-ajax-popup="true" data-size="lg " data-bs-toggle="tooltip"
                                                            title="{{ __('Raw Materials') }}"
                                                            data-title="{{ __('Raw Materials List') }}">
                                                            <i class="ti ti-list text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('delete product & service')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['productservice.destroy', $productService->id],
                                                            'id' => 'delete-form-' . $productService->id,
                                                        ]) !!}
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                                class="ti ti-trash text-white"></i></a>
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

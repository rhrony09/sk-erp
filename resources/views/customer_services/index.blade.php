@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Customer Services') }}
@endsection
@push('script-page')
    <script>
        var rowIdx = 2;

        $('#commonModal').on('click', '.addNewRow', function() {
            // alert();
            let html = `
                        <div class="row">
                            <div class="form-group col-md-4">
                                {{ Form::label('product_id', __('Product'), ['class' => 'form-label']) }}<span
                                    class="text-danger">*</span>
                                {{ Form::select('product_id[]', $products, null, ['class' => 'form-control select', 'id' => 'product_id` + rowIdx + `', 'required' => 'required']) }}
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

    <script>
        $(function() {
            $(".select2").select2();
        });
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Customer Services') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        {{-- <a href="#" data-size="md" data-bs-toggle="tooltip" title="{{ __('Import') }}" data-url="{{ route('productservice.file.import') }}" data-ajax-popup="true" data-title="{{ __('Import product CSV file') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-import"></i>
        </a>
        <a href="{{ route('productservice.export') }}" data-bs-toggle="tooltip" title="{{ __('Export') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-export"></i>
        </a> --}}

        @can('create customer services')
            <a href="#" data-size="lg" data-url="{{ route('customer_services.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create New Service') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endcan

    </div>
@endsection

@section('content')
    {{-- <div class="row">
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
    </div> --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Phone Number') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Employee Number') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Payment') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Employee Location') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $key => $service)
                                    <tr class="font-style">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $service->customer->name }}</td>
                                        <td>{{ $service->phone_number ?? $service->customer->contact }}</td>
                                        <td>{{ $service->address ?? $service->customer->billing_address }}</td>
                                        <td>{{ $service->employee ? $service->employee->name : 'Pending' }}</td>
                                        <td>{{ $service->employee ? $service->employee->phone : 'Pending' }}</td>
                                        <td>
                                            {{-- @if ($service->type == 0)
                                                {{ __('Service') }}
                                            @else
                                                {{ __('Raw Material') }}
                                            @endif --}}
                                            --
                                        </td>
                                        <td>{{ $service->description ?? '--' }}</td>
                                        <td>
                                            @switch($service->status)
                                                @case(0)
                                                    <span class="badge rounded-pill bg-warning">{{ __('Pending') }}</span>
                                                @break

                                                @case(1)
                                                    <span class="badge rounded-pill bg-primary">{{ __('Received') }}</span>
                                                @break

                                                @case(2)
                                                    <span class="badge rounded-pill bg-info">{{ __('In Progress') }}</span>
                                                @break

                                                @case(3)
                                                    <span class="badge rounded-pill bg-secondary">{{ __('On Hold') }}</span>
                                                @break

                                                @case(4)
                                                    <span class="badge rounded-pill bg-success">{{ __('Completed') }}</span>
                                                @break

                                                @case(5)
                                                    <span class="badge rounded-pill bg-danger">{{ __('Cancelled') }}</span>
                                                @break
                                            @endswitch

                                        </td>
                                        <td>
                                            @switch($service->is_paid)
                                                @case(1)
                                                    <span class="badge rounded-pill bg-success">{{ __('Paid') }}</span>
                                                @break

                                                @case(0)
                                                    <span class="badge rounded-pill bg-warning">{{ __('Unpaid') }}</span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td>{{ $service->due_date ?? 'Pending' }}</td>
                                        <td>{{ $service->employee_location ?? '--' }}</td>

                                        <td class="Action">
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="#" class="mx-3 btn btn-sm align-items-center"
                                                    data-url="{{ route('customer_services.show', $service->id) }}"
                                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                                    title="{{ __('Service Details') }}"
                                                    data-title="{{ __('Service Details') }}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>

                                            @can('edit customer services')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ route('customer_services.edit', $service->id) }}"
                                                        data-ajax-popup="true" data-size="lg " data-bs-toggle="tooltip"
                                                        title="{{ __('Edit') }}" data-title="{{ __('Edit Service') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center"
                                                        data-url="{{ route('customer_services.products', $service->id) }}"
                                                        data-ajax-popup="true" data-size="lg " data-bs-toggle="tooltip"
                                                        title="{{ __('Service Products') }}"
                                                        data-title="{{ __('Service Products List') }}">
                                                        <i class="ti ti-list text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('delete customer services')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['customer_services.destroy', $service->id],
                                                        'id' => 'delete-form-' . $service->id,
                                                    ]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                            class="ti ti-trash text-white"></i></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan
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

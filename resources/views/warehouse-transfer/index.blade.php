@extends('layouts.admin')
@section('page-title')
    {{ __('Warehouse Transfer') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Warehouse Transfer') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="#" data-size="lg" data-url="{{ route('warehouse-transfer.create') }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" title="{{ __('Create') }}" data-title="{{ __('Create Warehouse Transfer') }}"
            class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
    <style>
        .select2-container--open .select2-dropdown {
            z-index: 9999 !important;
        }

        .select2-container {
            z-index: 9995 !important;
            width: 100%;
            background: #22242c;
            padding: 0.350rem 1rem;
            border: 2px solid #3E3F4A;
            border-radius: 6px;

        }

        .modal-dialog {
            z-index: 1050;
            /* Default Bootstrap modal z-index */
        }

        .select2-selection {
            display: flex !important;
            align-items: center;
        }
    </style>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('From Warehouse') }}</th>
                                    <th>{{ __('To Warehouse') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($warehouse_transfers as $warehouse_transfer)
                                                    <tr class="font-style">
                                                        <td>{{ !empty($warehouse_transfer->fromWarehouse) ? $warehouse_transfer->fromWarehouse->name : '' }}
                                                        </td>
                                                        <td>{{ !empty($warehouse_transfer->toWarehouse) ? $warehouse_transfer->toWarehouse->name : '' }}
                                                        </td>
                                                        @if (!empty($warehouse_transfer->product))
                                                            <td>{{ !empty($warehouse_transfer->product) ? $warehouse_transfer->product->name : '' }}
                                                            </td>
                                                        @endif
                                                        <td>{{ $warehouse_transfer->quantity }}</td>
                                                        <td>{{ Auth::user()->dateFormat($warehouse_transfer->date) }}</td>

                                                        @if (Gate::check('edit warehouse') || Gate::check('delete warehouse'))
                                                                                <td class="Action">
                                                                                    {{-- @can('edit warehouse') --}}
                                                                                    {{-- <div class="action-btn bg-info ms-2"> --}}
                                                                                        {{-- <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                                            data-url="{{ route('warehouse-transfer.edit',$warehouse_transfer->id) }}"
                                                                                            data-ajax-popup="true" data-size="lg " data-bs-toggle="tooltip"
                                                                                            title="{{__('Edit')}}" data-title="{{__('Edit Warehouse')}}"> --}}
                                                                                            {{-- <i class="ti ti-pencil text-white"></i> --}}
                                                                                            {{-- </a> --}}
                                                                                        {{-- </div> --}}
                                                                                    {{-- @endcan --}}
                                                                                    @can('delete warehouse')
                                                                                                                <div class="action-btn bg-danger ms-2">
                                                                                                                    {!! Form::open([
                                                                                            'method' => 'DELETE',
                                                                                            'route' => ['warehouse-transfer.destroy', $warehouse_transfer->id],
                                                                                            'id' => 'delete-form-' . $warehouse_transfer->id,
                                                                                        ]) !!}
                                                                                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
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

@push('script-page')
    <script>
         $(document).ready(function () {
            // Listen for the modal being shown
            $(document).on('shown.bs.modal', '#commonModal', function () {
                // Check if this is the warehouse transfer modal
                if ($('#commonModal .modal-title').text().includes('Warehouse Transfer')) {
                    var warehouse_id = '0';
                    getProduct(warehouse_id);
                }
            });
        });

        $(document).on('change', 'select[name=from_warehouse]', function () {
            var warehouse_id = $(this).val();
            getProduct(warehouse_id);
        });

        function getProduct(wid) {
            $.ajax({
                url: '{{ route('warehouse-transfer.getproduct') }}',
                type: 'POST',
                data: {
                    "warehouse_id": wid,
                    "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    // Store any existing attributes from the original select
                    var originalSelect = $('#product_id');
                    var originalAttributes = {};

                    // Capture all attributes from the original select element
                    $.each(originalSelect[0].attributes, function (i, attr) {
                        originalAttributes[attr.name] = attr.value;
                    });

                    // Rebuild the product dropdown
                    $("#product_div").html('');
                    $('#product_div').append(
                        '<label for="product" class="form-label">{{ __('Product') }}</label>');

                    // Create new select with preserved attributes
                    var selectHtml = '<select id="product_id" name="product_id"';

                    // Add all original attributes back
                    for (var attr in originalAttributes) {
                        // Skip id and name as we've already added them
                        if (attr !== 'id' && attr !== 'name') {
                            selectHtml += ' ' + attr + '="' + originalAttributes[attr] + '"';
                        }
                    }

                    selectHtml += '></select>';
                    $('#product_div').append(selectHtml);

                    // Add the default empty option
                    $('#product_id').append('<option value="">{{ __('Select Product') }}</option>');

                    // Add product options
                    $.each(data.ware_products, function (key, value) {
                        $('#product_id').append('<option value="' + key + '">' + value + '</option>');
                    });

                    // Initialize Select2 with search capabilities
                    $('#product_id').select2({
                        dropdownParent: $('#commonModal'),
                        theme: 'bootstrap4', // Apply Bootstrap 4 theme
                        placeholder: "Select Product",
                        allowClear: true,
                        width: '100%'
                    });

                    // $('#myModal').on('shown.bs.modal', function () {
                    //     $('#product_id').select2({
                    //         dropdownParent: $('#myModal'), // Important for modal support
                    //         placeholder: 'Select Product',
                    //         allowClear: true,
                    //         width: '100%'
                    //     });
                    // });

                    // $('#myModal').on('shown.bs.modal', function () {
                    //     $('#product_id').select2({
                    //         theme: 'bootstrap4', // Apply Bootstrap 4 theme
                    //         dropdownParent: $('#commonModal'),
                    //         placeholder: 'Select Product',
                    //         allowClear: true,
                    //         width: '100%'
                    //     });
                    // });




                    // Update to_warehouse dropdown
                    $('select[name=to_warehouse]').empty();
                    $.each(data.to_warehouses, function (key, value) {
                        var option = '<option value="' + key + '">' + value + '</option>';
                        $('select[name=to_warehouse]').append(option);
                    });

                    // Re-initialize Select2 for to_warehouse
                    // if ($('select[name=to_warehouse]').hasClass('select')) {
                    //     $('select[name=to_warehouse]').select2({
                    //         width: '100%'
                    //     });
                    // }
                }
            });
        }

        $(document).on('change', '#product_id', function () {
            var product_id = $(this).val();
            var warehouse_id = $('#warehouse_id').val();
            getQuantity(product_id, warehouse_id);
        });

        function getQuantity(pid, wid) {
            $.ajax({
                url: '{{ route('warehouse-transfer.getquantity') }}',
                type: 'POST',
                data: {
                    "product_id": pid,
                    "warehouse_id": wid,
                    "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    console.log(data);
                    $('#quantity').val(data);
                }
            });
        }
    </script>
@endpush
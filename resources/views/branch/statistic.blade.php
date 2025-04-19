@extends('layouts.admin')
@section('page-title')
    {{ $branch->name }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('All Branches') }}</a></li>
    <li class="breadcrumb-item">{{ $branch->name }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="rounded-2 overflow-hidden p-3" style="background-color: #292a33;">
                <p>Employees</p>
                <h3>{{ @$branch->employees->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rounded-2 overflow-hidden p-3" style="background-color: #292a33;">
                <p>Warehouses</p>
                <h3>{{ @$branch->warehouses->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rounded-2 overflow-hidden p-3" style="background-color: #292a33;">
                <p>Products</p>
                <h3>
                    @if ($branch->warehouses->count() > 0)
                        @foreach ($branch->warehouses as $warehouse)
                            {{ $warehouse->warehouseProducts->sum('quantity') }}
                        @endforeach
                    @else
                        0
                    @endif
                </h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rounded-2 overflow-hidden p-3" style="background-color: #292a33;">
                <p>Revenue</p>
                <h3>{{ $totalDiscount }} ৳</h3>
            </div>
        </div>

        <div class="col-md-6 my-4">
            <div class="rounded-2 overflow-hidden p-3" style="background-color: #292a33;">
                <h3>Branch Warehouses</h3>

                @foreach ($branch->warehouses as $warehouse)
                    <div class="p-3 my-3 rounded-2 position-relative" style="background-color: #374151;">
                        <h5>{{ $warehouse->name }}</h5>

                        <div class="badge bg-primary position-absolute" style="top: 15px; right: 15px;">
                            <h5 class="mb-0">{{ @$warehouse->warehouseProducts->count() }} products</h5>
                        </div>

                        <p class="mb-0">Total Quantity: {{ @$warehouse->warehouseProducts->sum('quantity') }}</p>
                        <p>Employees: {{ @$branch->employees->count() }}</p>

                        <a href="{{ route('warehouse.show', $warehouse->id) }}">View Inventory →</a>
                    </div>
                @endforeach
            </div>

            <div class="rounded-2 overflow-hidden p-3 my-4" style="background-color: #292a33;">
                <h3>Recent Sale</h3>

                <div class="table-responsive">
                    @foreach ($branch->warehouses as $warehouse)
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>{{ __('Pos Id') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Warehouse') }}</th>
                                    <th>{{ __('Subtotal') }}</th>
                                    <th>{{ __('Discount') }}</th>
                                    <th>{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($warehouse->poses as $posPayment)
                                    <tr>
                                        <td class="Id">
                                            <a href="{{ route('pos.show', \Crypt::encrypt($posPayment->id)) }}"
                                                class="btn btn-outline-primary">
                                                {{ AUth::user()->posNumberFormat($posPayment->id) }}
                                            </a>
                                        </td>
                                        <td>{{ Auth::user()->dateFormat($posPayment->created_at) }}</td>
                                        @if ($posPayment->customer_id == 0)
                                            <td class="">{{ __('Walk-in Customer') }}</td>
                                        @else
                                            <td>{{ !empty($posPayment->customer) ? $posPayment->customer->name : '' }}
                                            </td>
                                        @endif
                                        <td>{{ !empty($posPayment->warehouse) ? $posPayment->warehouse->name : '' }} </td>
                                        <td>{{ !empty($posPayment->posPayment) ? \Auth::user()->priceFormat($posPayment->posPayment->amount) : 0 }}
                                        </td>
                                        <td>{{ !empty($posPayment->posPayment) ? \Auth::user()->priceFormat($posPayment->posPayment->discount) : 0 }}
                                        </td>
                                        <td>{{ !empty($posPayment->posPayment) ? \Auth::user()->priceFormat($posPayment->posPayment->discount_amount) : 0 }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="rounded-2 overflow-hidden p-3 my-4" style="background-color: #292a33;">
                <h3>Branch Products</h3>

                <div class="table-responsive">
                    @foreach ($branch->warehouses as $warehouse)
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Sku') }}</th>
                                    <th>{{ __('Sale Price') }}</th>
                                    <th>{{ __('Purchase Price') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Type') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($warehouse->warehouseProducts as $warehouseProduct)
                                    @php $productService = $warehouseProduct->stockProduct; @endphp
                                    <tr class="font-style">
                                        <td>{{ $productService->name }}</td>
                                        <td>{{ $productService->sku }}</td>
                                        <td>{{ \Auth::user()->priceFormat($productService->sale_price) }}</td>
                                        <td>{{ \Auth::user()->priceFormat($productService->purchase_price) }}</td>
                                        <td>{{ !empty($productService->category) ? $productService->category->name : '' }}
                                        </td>
                                        <td>{{ $warehouseProduct->quantity }}</td>
                                        <td>{{ ucwords($productService->type) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2 overflow-hidden p-3 my-4" style="background-color: #292a33;">
                <h3>Branch Employees</h3>

                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Address') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($branch->employees as $employee)
                                <tr class="font-style">
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->phone }}</td>
                                    <td>{{ $employee->address }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

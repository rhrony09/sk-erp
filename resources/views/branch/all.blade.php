@extends('layouts.admin')
@section('page-title')
    {{ __('Manage All Branches') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('All Branches') }}</li>
@endsection

@section('content')
    <div class="row">
        @foreach ($branches as $branch)
            <div class="col-lg-4 mb-4">
                <div class="rounded-2 overflow-hidden" style="background-color: #1F2937;">
                    <div class="p-3"
                        style="background: linear-gradient(141.55deg, #008ECC 3.46%, #008ECC 99.86%), #008ECC; !important; color: white">
                        {{ $branch->name }}
                    </div>
                    <div class="p-3 row">
                        <div class="col-md-6 mb-4">
                            <div class="p-3 rounded-2" style="background-color: #374151;">
                                <p>Employees</p>
                                <h3>{{ @$branch->employees->count() }}</h3>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="p-3 rounded-2" style="background-color: #374151;">
                                <p>Warehouses</p>
                                <h3>{{ @$branch->warehouses->count() }}</h3>
                            </div>
                        </div>

                        <div class="col-md-6 mb-lg-0 mb-4">
                            <div class="p-3 rounded-2" style="background-color: #374151;">
                                <p>Products</p>
                                <h3>
                                    @if($branch->warehouses->count() > 0)
                                    @foreach($branch->warehouses as $warehouse)
                                        {{ $warehouse->warehouseProducts->sum('quantity') }}
                                    @endforeach

                                    @else
                                        0
                                    @endif
                                </h3>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-2" style="background-color: #374151;">
                                <p>Revenue</p>
                                <h3>
                                    {{$branch->totalDiscount}}
                                    ৳
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-3 py-1 d-flex justify-content-between align-items-center" style="background-color: #374151;">
                        <span>created at: {{ $branch->created_at->format('d, M Y
                                ') }}</span>
                        <a href="{{ route('branch.singleBranch', $branch->id) }}">View Details →</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
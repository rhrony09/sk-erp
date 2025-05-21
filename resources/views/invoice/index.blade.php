@extends('layouts.admin')
@section('page-title')
    {{__('Manage Invoices')}}
@endsection
@push('script-page')
    <script>
        function copyToClipboard(element) {
            var copyText = element.id;
            navigator.clipboard.writeText(copyText);
            show_toastr('success', 'Url copied to clipboard', 'success');
        }
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Invoice')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        <a href="{{ route('invoice.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Export')}}">
            <i class="ti ti-file-export"></i>
        </a>

        @can('create invoice')
            <a href="{{ route('invoice.create', 0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

<style>
/* Dark Pagination Styling */
.invoice-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding: 0;
}

.pagination-info {
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Style the navigation buttons (Previous/Next) */
.pagination-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 8px 16px;
    background-color: #2d3748;
    border: 1px solid #4a5568;
    border-radius: 8px;
    color: #9ca3af;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.pagination-nav-btn:hover:not(.disabled) {
    background-color: #4a5568;
    color: #e2e8f0;
    text-decoration: none;
}

.pagination-nav-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    color: #6b7280;
}

/* Style the Laravel pagination wrapper */
.pagination-controls nav {
    display: inline-block;
}

.pagination-controls .pagination {
    background-color: #2d3748 !important;
    padding: 8px 12px !important;
    border-radius: 8px !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    gap: 4px !important;
    border: 1px solid #4a5568 !important;
}

.pagination-controls .page-item {
    margin: 0 !important;
}

.pagination-controls .page-link {
    background-color: transparent !important;
    border: none !important;
    color: #9ca3af !important;
    padding: 8px 12px !important;
    border-radius: 6px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    min-width: 40px !important;
    height: 40px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    line-height: 1 !important;
}

.pagination-controls .page-link:hover {
    background-color: #4a5568 !important;
    color: #e2e8f0 !important;
    text-decoration: none !important;
}

.pagination-controls .page-item.active .page-link {
    background-color: #06b6d4 !important;
    color: #ffffff !important;
}

.pagination-controls .page-item.active .page-link:hover {
    background-color: #0891b2 !important;
    color: #ffffff !important;
}

.pagination-controls .page-item.disabled .page-link {
    background-color: transparent !important;
    color: #6b7280 !important;
    cursor: not-allowed !important;
    opacity: 0.5 !important;
}

.pagination-controls .page-item.disabled .page-link:hover {
    background-color: transparent !important;
    color: #6b7280 !important;
}

/* Remove default Bootstrap styling */
.pagination-controls .pagination .page-item .page-link {
    margin-left: 0 !important;
    border-left: none !important;
}

.pagination-controls .pagination .page-item:first-child .page-link,
.pagination-controls .pagination .page-item:last-child .page-link {
    border-radius: 6px !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .invoice-pagination {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .pagination-controls {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .pagination-nav-btn {
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .pagination-controls .page-link {
        padding: 6px 10px !important;
        font-size: 13px !important;
        min-width: 36px !important;
        height: 36px !important;
    }
    
    .pagination-info {
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .pagination-controls {
        gap: 0.25rem;
    }
    
    .pagination-nav-btn {
        padding: 4px 8px;
        font-size: 12px;
    }
    
    .pagination-controls .pagination {
        padding: 6px 8px !important;
        gap: 2px !important;
    }
    
    .pagination-controls .page-link {
        padding: 4px 8px !important;
        font-size: 12px !important;
        min-width: 32px !important;
        height: 32px !important;
    }
}
</style>

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                <div class="card-body">
    {{ Form::open(['route' => ['invoice.index'], 'method' => 'GET', 'id' => 'customer_submit']) }}
    <div class="row d-flex align-items-center justify-content-end">
        <!-- Search Input Field -->
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
            <div class="btn-box">
                {{ Form::label('search', __('Search'),['class'=>'form-label'])}}
                {{ Form::text('search', isset($_GET['search'])?$_GET['search']:'', array('class' => 'form-control', 'placeholder' => __('Search invoice, customer, salesman...'))) }}
            </div>
        </div>
        <!-- Issue Date -->
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
            <div class="btn-box">
                {{ Form::label('issue_date', __('Issue Date'),['class'=>'form-label'])}}
                {{ Form::date('issue_date', isset($_GET['issue_date'])?$_GET['issue_date']:'', array('class' => 'form-control month-btn','id'=>'pc-daterangepicker-1')) }}
            </div>
        </div>
        <!-- Customer -->
        <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 mr-2">
            <div class="btn-box">
                {{ Form::label('customer', __('Customer'),['class'=>'form-label'])}}
                {{ Form::select('customer', $customer, isset($_GET['customer']) ? $_GET['customer'] : '', ['class' => 'form-control select2']) }}
            </div>
        </div>
        <!-- Status -->
        <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12">
            <div class="btn-box">
                {{ Form::label('status', __('Status'),['class'=>'form-label'])}}
                {{ Form::select('status', [''=>'Select Status'] + $status,isset($_GET['status'])?$_GET['status']:'', array('class' => 'form-control select')) }}
            </div>
        </div>
        <!-- Action Buttons -->
        <div class="col-auto float-end ms-2 mt-4">
            <a href="#" class="btn btn-sm btn-primary"
               onclick="document.getElementById('customer_submit').submit(); return false;"
               data-toggle="tooltip" data-original-title="{{ __('Apply Filters') }}">
                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
            </a>
            <a href="{{ route('invoice.index') }}" class="btn btn-sm btn-danger" data-toggle="tooltip"
               data-original-title="{{ __('Reset Filters') }}">
                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <!-- Removed 'datatable' class from table -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th> {{ __('Invoice') }}</th>
                                    <th>{{ __('Issue Date') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Due Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Note') }}</th>
                                    <th>{{ __('Salesman') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    @if (Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                        <th>{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($invoices as $invoice)
                                <tr>
                                    <td class="Id">
                                        <a href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}" class="btn btn-outline-primary">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</a>
                                    </td>
                                    <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                    <td>
                                        @if ($invoice->due_date < date('Y-m-d'))
                                            <p class="text-danger mt-3">
                                                {{ \Auth::user()->dateFormat($invoice->due_date) }}</p>
                                        @else
                                            {{ \Auth::user()->dateFormat($invoice->due_date) }}
                                        @endif
                                    </td>
                                    <td>{{ \Auth::user()->priceFormat($invoice->getDue()) }}</td>
                                    <td>
                                        @if ($invoice->status == 0)
                                            <span
                                                class="status_badge badge bg-secondary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span
                                                class="status_badge badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span
                                                class="status_badge badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span
                                                class="status_badge badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span
                                                class="status_badge badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($invoice->note, 30) ?? __('--') }}</td>
                                    <td>
                                        {{ $invoice->salesman ? $invoice->salesman->name : __('--') }} <br>
                                        {{ $invoice->salesman ? $invoice->salesman->contact : __('--') }}
                                    </td>
                                    <td>
                                        {{ $invoice->customer ? $invoice->customer->name : __('--') }} <br>
                                        {{ $invoice->customer ? $invoice->customer->contact : __('--') }}
                                    </td>
                                    @if (Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                        <td class="Action">
                                            <span class="d-flex">
                                                @php $invoiceID= Crypt::encrypt($invoice->id); @endphp

                                                    @can('copy invoice')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="#" id="{{ route('invoice.link.copy',[$invoiceID]) }}"
                                                               class="mx-3 btn btn-sm d-flex justify-content-center align-items-center"   onclick="copyToClipboard(this)"
                                                               data-bs-toggle="tooltip" title="{{__('Copy Invoice')}}" data-original-title="{{__('Copy Invoice')}}"><i class="ti ti-link text-white"></i></a>
                                                        </div>
                                                    @endcan
                                                    @can('duplicate invoice')
                                                        <div class="action-btn bg-success ms-2">
                                                           {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id], 'id' => 'duplicate-form-' . $invoice->id, 'class' => 'mb-0']) !!}

                                                            <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-toggle="tooltip"
                                                               data-original-title="{{ __('Duplicate') }}" data-bs-toggle="tooltip" title="Duplicate Invoice"
                                                               data-original-title="{{ __('Delete') }}"
                                                               data-confirm="You want to confirm this action. Press Yes to continue or Cancel to go back"
                                                               data-confirm-yes="document.getElementById('duplicate-form-{{ $invoice->id }}').submit();">
                                                                <i class="ti ti-copy text-white"></i>
                                                                {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id], 'id' => 'duplicate-form-' . $invoice->id]) !!}
                                                                {!! Form::close() !!}
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('show invoice')
                                                            <div class="action-btn bg-info ms-2">
                                                                    <a href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}"
                                                                       class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Show "
                                                                       data-original-title="{{ __('Detail') }}">
                                                                        <i class="ti ti-eye text-white"></i>
                                                                    </a>
                                                                </div>
                                                    @endcan
                                                    @can('edit invoice')
                                                        <div class="action-btn bg-primary ms-2">
                                                                <a href="{{ route('invoice.edit', \Crypt::encrypt($invoice->id)) }}"
                                                                   class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Edit "
                                                                   data-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                    @endcan
                                                    @can('delete invoice')
                                                        <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id], 'id' => 'delete-form-' . $invoice->id, 'class' => 'mb-0']) !!}
                                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para " data-bs-toggle="tooltip" title="{{__('Delete')}}"
                                                                       data-original-title="{{ __('Delete') }}"
                                                                       data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                                       data-confirm-yes="document.getElementById('delete-form-{{ $invoice->id }}').submit();">
                                                                        <i class="ti ti-trash text-white"></i>
                                                                    </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                    @endcan
                                                </span>
                                        </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice') ? '8' : '7' }}" class="text-center">
                                        {{ __('No invoices found') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Custom Pagination Links -->
                    @if($invoices->hasPages())
                        <div class="invoice-pagination">
                            <div class="pagination-info">
                                Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results
                            </div>
                            
                            <div class="pagination-controls">
                                {{-- Previous Button --}}
                                @if ($invoices->onFirstPage())
                                    <span class="pagination-nav-btn disabled">
                                        <i class="ti ti-chevron-left"></i> Previous
                                    </span>
                                @else
                                    <a href="{{ $invoices->previousPageUrl() }}" class="pagination-nav-btn">
                                        <i class="ti ti-chevron-left"></i> Previous
                                    </a>
                                @endif
                                
                                {{-- Page Numbers --}}
                                <!-- <nav aria-label="Page navigation">
                                    {{ $invoices->onEachSide(1)->links() }}
                                </nav> -->
                                
                                {{-- Next Button --}}
                                @if ($invoices->hasMorePages())
                                    <a href="{{ $invoices->nextPageUrl() }}" class="pagination-nav-btn">
                                        Next <i class="ti ti-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="pagination-nav-btn disabled">
                                        Next <i class="ti ti-chevron-right"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
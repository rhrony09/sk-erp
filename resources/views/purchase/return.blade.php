@extends('layouts.admin')
@section('page-title')
    {{ __('Purchase Return') }}
@endsection
@section('content')
    <style>
        .select2-selection{
            height: 100% !important;
            border: 2px solid #3E3F4A !important;
        }
        .select2-selection__rendered {
            padding: 0.350rem 1rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #808191;
            background-color: #22242c;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">
                        <h4>{{ __('Create Purchase Return') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('purchase.return.store') }}" class="needs-validation"
                        novalidate="">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Purchase') }}</label>
                                    <select class="form-control select2" name="purchase_id" required>
                                        <option value="">{{ __('Select Purchase') }}</option>
                                        @foreach($purchases as $purchase)
                                            <option value="{{ $purchase->id }}">
                                                {{ Auth::user()->purchaseNumberFormat($purchase->purchase_id) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Return Date') }}</label>
                                    <input type="date" class="form-control" name="return_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Product') }}</label>
                                    <select class="form-control select2" name="product_id" required>
                                        <option value="">{{ __('Select Product') }}</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Warehouse') }}</label>
                                    <select class="form-control select2" name="warehouse_id" required>
                                        <option value="">{{ __('Select Warehouse') }}</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Quantity') }}</label>
                                    <input type="number" class="form-control" name="quantity" required min="1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Reason') }}</label>
                                    <textarea class="form-control" name="reason" rows="3"
                                        placeholder="{{ __('Enter reason for return') }}"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-end">
                                <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">
                        <h4>{{ __('Purchase Returns List') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Purchase Number') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Warehouse') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Return Date') }}</th>
                                    <th>{{ __('Reason') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchaseReturns ?? [] as $return)
                                    <tr>
                                        <td>
                                            <a href="{{ route('purchase.show', \Crypt::encrypt($return->purchase_id)) }}"
                                                class="btn btn-outline-primary">
                                                {{ Auth::user()->purchaseNumberFormat($return->purchase->purchase_id) }}
                                            </a>
                                        </td>
                                        <td>{{ $return->product->name ?? '' }}</td>
                                        <td>{{ $return->warehouse->name ?? '' }}</td>
                                        <td>{{ $return->quantity }}</td>
                                        <td>{{ Auth::user()->dateFormat($return->return_date) }}</td>
                                        <td>{{ $return->reason }}</td>
                                        <td>
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
            $('.select2').select2({
                width: '100%'
            });
        });
    </script>
@endpush
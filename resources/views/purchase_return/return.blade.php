@extends('layouts.admin')
@section('page-title')
    {{ __('Purchase Return') }}
@endsection
@section('content')
    <!-- <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">
                        <h4>{{ __('Create Purchase Return') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('purchase.return.store') }}" class="needs-validation" novalidate="">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Purchase') }}</label>
                                    <select class="form-control select2" name="purchase_id" required>
                                        <option value="">{{ __('Select Purchase') }}</option>
                                        @foreach($purchases as $purchase)
                                            <option value="{{ $purchase->id }}">{{ $purchase->purchase_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('Return Date') }}</label>
                                    <input type="date" class="form-control" name="return_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>{{ __('Items') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="items">
                                                <thead>
                                                <tr>
                                                    <th>{{ __('Product') }}</th>
                                                    <th>{{ __('Quantity') }}</th>
                                                    <th>{{ __('Price') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <select class="form-control select2" name="items[0][product_id]" required>
                                                            <option value="">{{ __('Select Product') }}</option>
                                                            @foreach($products as $product)
                                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="items[0][quantity]" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" name="items[0][price]" required>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary add-item">+</button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
@endsection

<!-- @push('script-page')
    <script>
        $(document).ready(function () {
            var row = 0;
            $('.add-item').click(function () {
                row++;
                var html = '<tr>' +
                    '<td>' +
                    '<select class="form-control select2" name="items[' + row + '][product_id]" required>' +
                    '<option value="">{{ __('Select Product') }}</option>' +
                    '@foreach($products as $product)' +
                    '<option value="{{ $product->id }}">{{ $product->name }}</option>' +
                    '@endforeach' +
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" class="form-control" name="items[' + row + '][quantity]" required>' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" class="form-control" name="items[' + row + '][price]" required>' +
                    '</td>' +
                    '<td>' +
                    '<button type="button" class="btn btn-danger remove-item">-</button>' +
                    '</td>' +
                    '</tr>';
                $('#items tbody').append(html);
                $('.select2').select2();
            });

            $(document).on('click', '.remove-item', function () {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endpush  -->
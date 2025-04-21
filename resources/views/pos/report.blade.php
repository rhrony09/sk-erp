@extends('layouts.admin')
@section('page-title')
    {{__('POS Summary')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('POS Summary')}}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/datatable/buttons.dataTables.min.css') }}">
@endpush

@section('content')
    <div id="printableArea">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                <tr>
                                    <th>{{__('POS ID')}}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Warehouse') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Sub Total') }}</th>
                                    <th>{{ __('Discount') }}</th>
                                    <th>{{ __('Total') }}</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Sold By</th>
                                </tr>
                                </thead>

                                <tbody>

                                @forelse ($posPayments as $posPayment)
                                    <tr>
{{--                                        <td>{{ AUth::user()->posNumberFormat($posPayment->pos_id) }}</td>--}}

                                        <td class="Id">
                                            <a href="{{ route('pos.show',\Crypt::encrypt($posPayment->id)) }}" class="btn btn-outline-primary">
                                                {{ AUth::user()->posNumberFormat($posPayment->id) }}
                                            </a>
                                        </td>
                                        <td>{{ Auth::user()->dateFormat($posPayment->created_at)}}</td>
                                        <td class="">
                                            @if($posPayment->customer_id == 0)
                                                {{__('Walk-in Customer')}}
                                            @else
                                                {{ !empty($posPayment->customer) ? $posPayment->customer->name : '' }} <br/>
                                                
                                            @endif
                                        </td>
                                        <td>{{ !empty($posPayment->customer) ? $posPayment->customer->contact : '' }}</td>
                                        <td>{{ !empty($posPayment->warehouse) ? $posPayment->warehouse->name : '' }} </td>
                                        <td class="text-capitalize">{{$posPayment->delivery_status}}</td>
                                        <td>{{!empty($posPayment->posPayment)? \Auth::user()->priceFormat ($posPayment->posPayment->amount) :0}}</td>
                                        <td>{{!empty($posPayment->posPayment)? \Auth::user()->priceFormat($posPayment->posPayment->discount) :0}}</td>
                                        <td>{{!empty($posPayment->posPayment)? \Auth::user()->priceFormat($posPayment->posPayment->discount_amount) :0}}</td>
                                        <td>{{!empty($posPayment->posPayment)? \Auth::user()->priceFormat($posPayment->posPayment->paid) :0}}</td>
                                        <td>{{!empty($posPayment->posPayment)? \Auth::user()->priceFormat($posPayment->posPayment->due) :0}}</td>
                                        <td>{{ !empty($posPayment->createdBy) ? $posPayment->createdBy->name : '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')
@section('page-title')
    {{__('Sale Return')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Sale Return')}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                            <h4>{{__('Sale Return')}}</h4>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row text-white">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-white">Sale id</th>
                                            <th class="text-white">{{__('Item')}}</th>
                                            <th class="text-white">{{__('Quantity')}}</th>
                                            <th>Customer</th>
                                            <th>Condition</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    @foreach($saleReturns as $return)
                                        <tr>
                                            <td>
                                                <a href="{{ route('pos.show',\Crypt::encrypt(@$return->pos->pos_id)) }}" class="btn btn-outline-primary">
                                                    {{ AUth::user()->posNumberFormat(@$return->pos->pos_id) }}
                                                </a>
                                            </td>
                                            <td>{{!empty($return->product) ? $return->product->name : ''}}</td>
                                            <td>{{$return->quantity}}</td>
                                            <td>
                                                @if($return->customer_id != 0)
                                                    {{!empty($return->customer) ? $return->customer->name : ''}}
                                                @else
                                                    Walk in Customer
                                                @endif
                                            </td>
                                            <td class="text-capitalize">{{$return->product_condition}}</td>
                                            <td>{{$return->reason}}</td>
                                            <td>{{$return->is_approved == 1 ? 'Approved' : 'Unapproved'}}</td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <form action="{{ route('sale.approve.update',$return->id) }}" method="post">
                                                        @csrf
                                                        <button type="submit" class="btn @if($return->is_approved == 1) btn-danger @else btn-outline-primary @endif">@if($return->is_approved == 1) Unapprove @else Approve @endif</button>
                                                    </form>
                                                    
                                                    <form action="{{ route('sale.return.delete',$return->id) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
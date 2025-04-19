@extends('layouts.admin')

@section('page-title')
    {{__('Orders')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Order')}}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>{{__('Order')}}</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Total')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr class="font-style">
                                    <td>{{ $order->order_id}}</td>
                                    <td>{{ $order->created_at->format('d M, Y') }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ $order->total }}৳</td>

                                    @if(Gate::check('show warehouse') || Gate::check('edit warehouse') || Gate::check('delete warehouse'))
                                        <td class="Action">
                                            @can('show warehouse')
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{ route('erp_order.show',$order->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-bs-toggle="tooltip" title="{{__('View')}}"><i class="ti ti-eye text-white"></i></a>
                                                </div>
                                            @endcan
                                            @can('delete warehouse')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['erp_order.delete', $order->id],'id'=>'delete-form-'.$order->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti ti-trash text-white"></i></a>
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
@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Provident Funds') }}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Provident Funds') }}</li>
@endsection
@section('action-btn')
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
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Employee Name') }}</th>
                                    <th>{{ __('Department') }}</th>
                                    <th>{{ __('Designation') }}</th>
                                    <th>{{ __('Salary') }}</th>
                                    <th>{{ __('PF Status') }}</th>
                                    <th>{{ __('PF Contribution') }}</th>
                                    <th>{{ __('PF Total') }}</th>
                                    <th>{{ __('Withdrawn') }}</th>
                                    <th>{{ __('Note') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $key => $employee)
                                    <tr class="font-style">
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            <a
                                                href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}">{{ $employee->name }}</a>
                                        </td>
                                        <td>{{ $employee->department ? $employee->department->name : '--' }}</td>
                                        <td>{{ $employee->designation ? $employee->designation->name : '--' }}</td>
                                        <td>{{ \Auth::user()->priceFormat($employee->salary) }}</td>
                                        <td>
                                            @if ($employee->provident_fund)
                                                @switch($employee->provident_fund->status)
                                                    @case(1)
                                                        <span class="badge rounded-pill bg-success">{{ __('Active') }}</span>
                                                    @break

                                                    @case(2)
                                                        <span class="badge rounded-pill bg-secondary">{{ __('Inactive') }}</span>
                                                    @break

                                                    @case(3)
                                                        <span class="badge rounded-pill bg-primary">{{ __('Closed') }}</span>
                                                    @break

                                                    @case(4)
                                                        <span class="badge rounded-pill bg-danger">{{ __('Suspended') }}</span>
                                                    @break
                                                @endswitch
                                            @else
                                                N/A
                                            @endif

                                        </td>
                                        <td>{{ $employee->provident_fund ? $employee->provident_fund->contribution_rate . '%' : '--' }}
                                        </td>
                                        <td>{{ $employee->provident_fund ? \Auth::user()->priceFormat($employee->provident_fund->total_amount) : '--' }}
                                        </td>
                                        <td>{{ $employee->provident_fund ? \Auth::user()->priceFormat($employee->provident_fund->withdrawn_amount) : '--' }}
                                        </td>
                                        <td>{{ $employee->provident_fund ? $employee->provident_fund->note : '--' }}</td>

                                        @if (Gate::check('manage provident fund'))
                                            <td class="Action">
                                                @if (!$employee->provident_fund)
                                                    @can('initiate provident fund')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm align-items-center"
                                                                data-url="{{ route('provident_fund.initiate', $employee->id) }}"
                                                                data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                title="{{ __('Initiate Fund') }}"
                                                                data-title="{{ __('Initiate Fund') }}">
                                                                <i class="ti ti-arrow-up text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                @endif

                                                @if ($employee->provident_fund)
                                                    @can('create provident fund')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm align-items-center"
                                                                data-url="{{ route('provident_fund.add_transaction', $employee->id) }}"
                                                                data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                title="{{ __('Add Transaction') }}"
                                                                data-title="{{ __('Add Transaction') }}">
                                                                <i class="ti ti-plus text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('edit provident fund')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ route('provident_fund.edit', $employee->id) }}"
                                                                data-ajax-popup="true" data-size="lg " data-bs-toggle="tooltip"
                                                                title="{{ __('Edit Fund') }}"
                                                                data-title="{{ __('Edit Fund') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center"
                                                            data-url="{{ route('provident_fund.transactions', $employee->id) }}"
                                                            data-ajax-popup="true" data-size="lg " data-bs-toggle="tooltip"
                                                            title="{{ __('Fund Transactions') }}"
                                                            data-title="{{ __('Fund Transactions') }}">
                                                            <i class="ti ti-list text-white"></i>
                                                        </a>
                                                    </div>
                                                @endif
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

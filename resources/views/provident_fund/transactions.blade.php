<div class="modal-body">
    <div class="card ">
        <h4>{{ __('PF Transactions') }}</h4>
        <div class="card-body table-border-style full-card">
            <div class="table-responsive">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>{{ __('SL') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Transaction Type') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Note') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $key => $transaction)
                            <tr class="font-style">
                                <td>{{ ++$key }}</td>
                                <td>{{ Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y') }}</td>
                                <td>
                                    @switch($transaction->transaction_type)
                                        @case(1)
                                            <span class="badge rounded-pill bg-success">{{ __('Employee Contribution') }}</span>
                                        @break

                                        @case(2)
                                            <span class="badge rounded-pill bg-secondary">{{ __('Withdrawal') }}</span>
                                        @break

                                        @case(3)
                                            <span class="badge rounded-pill bg-warning">{{ __('Loan') }}</span>
                                        @break

                                        @case(4)
                                            <span class="badge rounded-pill bg-primary">{{ __('Company Contribution') }}</span>
                                        @break
                                    @endswitch
                                </td>
                                <td>{{ \Auth::user()->priceFormat($transaction->amount) }}</td>
                                <td>
                                    @switch($transaction->status)
                                        @case(0)
                                            <span class="badge rounded-pill bg-warning">{{ __('Pending') }}</span>
                                        @break

                                        @case(1)
                                            <span class="badge rounded-pill bg-success">{{ __('Approved') }}</span>
                                        @break
                                    @endswitch
                                </td>
                                <td>{{ $transaction->note ?? '--' }}</td>

                                <td class="Action">
                                    @can('edit provident fund')
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                data-url="{{ route('provident_fund.edit_transaction', $transaction->id) }}"
                                                data-ajax-popup="true" data-size="lg " data-bs-toggle="tooltip"
                                                title="{{ __('Edit Transaction') }}"
                                                data-title="{{ __('Edit Transaction') }}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                        {{-- <div class="action-btn bg-danger ms-2">
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['provident_fund.destroy_transaction', $transaction->id],
                                                    'id' => 'delete-form-' . $transaction->id,
                                                ]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" title="{{ __('Delete Transaction') }}"><i
                                                        class="ti ti-trash text-white"></i></a>
                                                {!! Form::close() !!}
                                            </div> --}}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

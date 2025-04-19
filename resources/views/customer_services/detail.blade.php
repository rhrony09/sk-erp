<div class="modal-body">
    <div class="card ">
        <div class="card-body table-border-style full-card">
            <div class="table-responsive">
                {{--                    @if (!$products->isEmpty()) --}}
                <table class="table">
                    <tr>
                        <th>{{ __('Service ID') }}</th>
                        <td>#00{{ $customer_service->id + 1 }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Employee Name') }}</th>
                        <td>{{ $customer_service->employee->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Customer Name') }}</th>
                        <td>{{ $customer_service->customer->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Phone') }}</th>
                        <td>{{ $customer_service->phone_number }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Address') }}</th>
                        <td>{{ $customer_service->address }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Due Date') }}</th>
                        <td>{{ $customer_service->due_date }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Description') }}</th>
                        <td>{{ $customer_service->description }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Status') }}</th>
                        <td>
                            @switch($customer_service->status)
                                @case(0)
                                    <span class="badge rounded-pill bg-warning">{{ __('Pending') }}</span>
                                @break

                                @case(1)
                                    <span class="badge rounded-pill bg-primary">{{ __('Received') }}</span>
                                @break

                                @case(2)
                                    <span class="badge rounded-pill bg-info">{{ __('In Progress') }}</span>
                                @break

                                @case(3)
                                    <span class="badge rounded-pill bg-secondary">{{ __('On Hold') }}</span>
                                @break

                                @case(4)
                                    <span class="badge rounded-pill bg-success">{{ __('Completed') }}</span>
                                @break

                                @case(5)
                                    <span class="badge rounded-pill bg-danger">{{ __('Cancelled') }}</span>
                                @break
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Service Charge') }}</th>
                        <td> {{ \Auth::user()->priceFormat($customer_service->service_charge) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Product Price') }}</th>
                        <td> {{ \Auth::user()->priceFormat($customer_service->product_price) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Payment') }}</th>
                        <td>
                            @switch($customer_service->is_paid)
                                @case(1)
                                    <span class="badge rounded-pill bg-success">{{ __('Paid') }}</span>
                                @break

                                @case(0)
                                    <span class="badge rounded-pill bg-warning">{{ __('Unpaid') }}</span>
                                @break
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Employee Location') }}</th>
                        <td> {{ $customer_service->employee_location }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Created At') }}</th>
                        <td>{{ Carbon\Carbon::parse($customer_service->created_at)->format('d-m-Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

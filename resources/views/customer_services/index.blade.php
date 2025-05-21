@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Customer Services') }}
@endsection
@push('script-page')
<style>
    .modal .dropdown-menu,
.modal select option,
.modal .select2-dropdown {
    z-index: 9999 !important;
}

/* If using Select2 */
.select2-container {
    z-index: 9999 !important;
}
</style>
    <script>
        var rowIdx = 2;

        $('#commonModal').on('click', '.addNewRow', function() {
            // alert();
            let html = `
                        <div class="row">
                            <div class="form-group col-md-4">
                                {{ Form::label('product_id', __('Product'), ['class' => 'form-label']) }}<span
                                    class="text-danger">*</span>
                                {{ Form::select('product_id[]', $products, null, ['class' => 'form-control select2', 'id' => 'product_id` + rowIdx + `', 'required' => 'required']) }}
                            </div>
                            <div class="col-md-4">
                                {{ Form::label('quantity', __('Quantity'), ['class' => 'form-label']) }}<span
                                    class="text-danger">*</span>
                                {{ Form::number('quantity[]', null, ['class' => 'form-control', 'id' => 'quantity` + rowIdx + `', 'required' => 'required']) }}
                            </div>
                            <div class="col-md-4 pt-4">
                                <div class="form-group mt-2">
                                    <button type="button" class="btn btn-danger btn-sm f-14 remove" name="button"><i
                                            class="fa fa-minus"></i></button>
                                </div>
                            </div>
                        </div>
                        `;

            $(this).closest('div[class=row]').after(html);

            $('.select2').select2();

            rowIdx++;
        });

        $('#commonModal').on('click', '.remove', function() {
            $(this).closest('div[class=row]').remove();
        });
    </script>

    <script>
        $(function() {
            $(".select2").select2();
        });
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Customer Services') }}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        {{-- <a href="#" data-size="md" data-bs-toggle="tooltip" title="{{ __('Import') }}" data-url="{{ route('productservice.file.import') }}" data-ajax-popup="true" data-title="{{ __('Import product CSV file') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-import"></i>
        </a>
        <a href="{{ route('productservice.export') }}" data-bs-toggle="tooltip" title="{{ __('Export') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-export"></i>
        </a> --}}

        @can('create customer services')
            <a href="#" data-size="lg" data-url="{{ route('customer_services.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create New Service') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endcan

    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="theme-avtar bg-warning">
                                <i class="ti ti-clock"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0" data-stat="pending">{{ $stats['pending'] }}</h5>
                                <p class="text-muted text-sm mb-0">{{ __('Pending Services') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="theme-avtar bg-success">
                                <i class="ti ti-check"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0" data-stat="completed">{{ $stats['completed'] }}</h5>
                                <p class="text-muted text-sm mb-0">{{ __('Completed Services') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="theme-avtar bg-primary">
                                <i class="ti ti-currency-dollar"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0" data-stat="paid">{{ $stats['paid'] }}</h5>
                                <p class="text-muted text-sm mb-0">{{ __('Paid Services') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="theme-avtar bg-danger">
                                <i class="ti ti-alert-triangle"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0" data-stat="unpaid">{{ $stats['unpaid'] }}</h5>
                                <p class="text-muted text-sm mb-0">{{ __('Unpaid Services') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(Auth::user()->type != 'client')
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="theme-avtar bg-info">
                                <i class="ti ti-activity"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0" data-stat="in_progress">{{ $stats['in_progress'] }}</h5>
                                <p class="text-muted text-sm mb-0">{{ __('In Progress') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="theme-avtar bg-secondary">
                                <i class="ti ti-player-pause"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0" data-stat="on_hold">{{ $stats['on_hold'] }}</h5>
                                <p class="text-muted text-sm mb-0">{{ __('On Hold') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <!-- End Stats Cards -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" id="service-search" class="form-control" placeholder="Search by customer or description">
                        </div>
                        <div class="col-md-4">
                            <input type="date" id="service-due-date" class="form-control" placeholder="Due Date">
                        </div>
                        <div class="col-md-4">
                            <button id="service-search-btn" class="btn btn-primary">Search</button>
                            <button id="service-reset-btn" class="btn btn-danger">Reset</button>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Phone Number') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Employee Number') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Payment') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Employee Location') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="service-list-body">
                                <!-- AJAX-loaded rows go here -->
                            </tbody>
                        </table>
                        <div id="pagination-container" class="d-flex justify-content-center mt-3">
                            <!-- Pagination will be rendered here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
// Define permissions for actions
const canEdit = @json(Auth::user()->can('edit customer services'));
const canDelete = @json(Auth::user()->can('delete customer services'));

function renderServiceRow(service, index) {
    return `
        <tr class="font-style">
            <td>${index}</td>
            <td>${service.customer ? service.customer.name : '--'}</td>
            <td>${service.phone_number || (service.customer ? service.customer.contact : '--')}</td>
            <td>${service.address || (service.customer ? service.customer.billing_address : '--')}</td>
            <td>${service.employee ? service.employee.name : 'Pending'}</td>
            <td>${service.employee ? service.employee.phone : 'Pending'}</td>
            <td>--</td>
            <td>${service.description || '--'}</td>
            <td>
                ${(() => {
                    switch (parseInt(service.status)) {
                        case 0: return '<span class="badge rounded-pill bg-warning">{{ __("Pending") }}</span>';
                        case 1: return '<span class="badge rounded-pill bg-primary">{{ __("Received") }}</span>';
                        case 2: return '<span class="badge rounded-pill bg-info">{{ __("In Progress") }}</span>';
                        case 3: return '<span class="badge rounded-pill bg-secondary">{{ __("On Hold") }}</span>';
                        case 4: return '<span class="badge rounded-pill bg-success">{{ __("Completed") }}</span>';
                        case 5: return '<span class="badge rounded-pill bg-danger">{{ __("Cancelled") }}</span>';
                        default: return '--';
                    }
                })()}
            </td>
            <td>
                ${parseInt(service.is_paid) === 1
                    ? '<span class="badge rounded-pill bg-success">{{ __("Paid") }}</span>'
                    : '<span class="badge rounded-pill bg-warning">{{ __("Unpaid") }}</span>'}
            </td>
            <td>${service.due_date || 'Pending'}</td>
            <td>${service.employee_location || '--'}</td>
            <td class="Action">
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm align-items-center"
                        data-url="{{ route('customer_services.show', '') }}/${service.id}"
                        data-ajax-popup="true" data-bs-toggle="tooltip"
                        title="{{ __('Service Details') }}"
                        data-title="{{ __('Service Details') }}">
                        <i class="ti ti-eye text-white"></i>
                    </a>
                </div>

                ${canEdit ? `
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm align-items-center"
                        data-url="{{ url('erp/customer_services') }}/${service.id}/edit"
                        data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
                        title="{{ __('Edit') }}" data-title="{{ __('Edit Service') }}">
                        <i class="ti ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-primary ms-2">
                    <a href="#" class="mx-3 btn btn-sm align-items-center"
                        data-url="{{ url('erp/customer_services') }}/${service.id}/products"
                        data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
                        title="{{ __('Service Products') }}"
                        data-title="{{ __('Service Products List') }}">
                        <i class="ti ti-list text-white"></i>
                    </a>
                </div>
                ` : ''}

                ${canDelete ? `
                <div class="action-btn bg-danger ms-2">
                    <form class="mb-0" action="{{ route('customer_services.destroy', '') }}/${service.id}" 
                          method="POST" 
                          id="delete-form-${service.id}">
                        @csrf
                        @method('DELETE')
                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para"
                            data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                            data-confirm="{{ __('Are You Sure?') }}"
                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                            data-confirm-yes="document.getElementById('delete-form-${service.id}').submit();">
                            <i class="ti ti-trash text-white"></i>
                        </a>
                    </form>
                </div>
                ` : ''}
            </td>
        </tr>
    `;
}

function renderPagination(pagination) {
    if (!pagination || pagination.last_page <= 1) return '';
    
    let links = '';
    const currentPage = pagination.current_page;
    
    // Previous button
    links += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
        </a>
    </li>`;
    
    // Page numbers
    for (let i = 1; i <= pagination.last_page; i++) {
        if (
            i === 1 || // First page
            i === pagination.last_page || // Last page
            (i >= currentPage - 2 && i <= currentPage + 2) // Pages around current
        ) {
            links += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        } else if (
            i === currentPage - 3 || 
            i === currentPage + 3
        ) {
            links += `<li class="page-item disabled"><a class="page-link" href="#">...</a></li>`;
        }
    }
    
    // Next button
    links += `<li class="page-item ${currentPage === pagination.last_page ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
        </a>
    </li>`;
    
    return `<nav aria-label="Page navigation"><ul class="pagination">${links}</ul></nav>`;
}

function loadServices(page = 1) {
    let search = $('#service-search').val();
    let due_date = $('#service-due-date').val();
    
    $('#service-list-body').html('<tr><td colspan="13" class="text-center"><div class="spinner-border text-primary" role="status"></div></td></tr>');
    
    $.ajax({
        url: '{{ route('customer_services.serviceList') }}',
        data: {
            search: search,
            due_date: due_date,
            page: page,
            per_page: 10
        },
        success: function(response) {
            let rows = '';
            if (response.data && response.data.length) {
                response.data.forEach((service, idx) => {
                    rows += renderServiceRow(service, ((response.current_page - 1) * response.per_page) + idx + 1);
                });
                
                // Render pagination
                $('#pagination-container').html(renderPagination(response));
                
                // Add pagination event handlers
                $('#pagination-container').off('click', '.page-link').on('click', '.page-link', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    if (page && !$(this).parent().hasClass('disabled')) {
                        loadServices(page);
                    }
                });
            } else {
                rows = '<tr><td colspan="13" class="text-center">{{ __("No Data Found.!") }}</td></tr>';
                $('#pagination-container').html('');
            }
            $('#service-list-body').html(rows);
            
            // Update stats cards
            if (response.stats) {
                updateStatsCards(response.stats);
            }
            
            // Re-initialize tooltips and other BS components
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        error: function(xhr) {
            $('#service-list-body').html('<tr><td colspan="13" class="text-center text-danger">{{ __("Error loading data.") }}</td></tr>');
            $('#pagination-container').html('');
        }
    });
}

// Function to update stats cards
function updateStatsCards(stats) {
    // Update the main stats cards
    $('.card h5[data-stat="pending"]').text(stats.pending);
    $('.card h5[data-stat="completed"]').text(stats.completed);
    $('.card h5[data-stat="paid"]').text(stats.paid);
    $('.card h5[data-stat="unpaid"]').text(stats.unpaid);
    
    // Update admin-only stats if they exist
    if (stats.in_progress !== undefined) {
        $('.card h5[data-stat="in_progress"]').text(stats.in_progress);
        $('.card h5[data-stat="on_hold"]').text(stats.on_hold);
    }
}

$(document).ready(function() {
    // Load services on page load
    loadServices();
    
    // Search button click
    $('#service-search-btn').on('click', function() {
        loadServices(1);
    });
    
    // Reset button click
    $('#service-reset-btn').on('click', function() {
        $('#service-search').val('');
        $('#service-due-date').val('');
        loadServices(1);
    });
    
    // Search on enter key
    $('#service-search, #service-due-date').on('keypress', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            loadServices(1);
        }
    });
    
    // Re-initialize any popup or BS components after AJAX load
    $(document).on('ajaxComplete', function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
});

// Function to initialize BS delete confirmation
function initializeDeleteConfirmation() {
    $('body').on('click', '.bs-pass-para', function() {
        const confirm_text = $(this).data('confirm');
        const text = $(this).data('text');
        const confirm_yes = $(this).data('confirm-yes');
        
        if (confirm(confirm_text + "\n" + text)) {
            eval(confirm_yes);
        }
        return false;
    });
}

// Initialize the delete confirmation on page load
initializeDeleteConfirmation();
</script>

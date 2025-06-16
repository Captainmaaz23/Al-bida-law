@extends('layouts.app')

@section('content')
<div class="content">
    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
        <div class="flex-grow-1 mb-1 mb-md-0">
            <h1 class="h3 fw-bold mb-2">Dashboard</h1>
        </div>
        <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
            <div class="dropdown d-inline-block">
                <a href="#" class="btn btn-sm btn-alt-secondary space-x-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-calendar-alt opacity-50"></i>
                    <span>Summary</span>
                </a>
                <div class="dropdown-menu" x-placement="bottom-start">
                    @foreach (['today', 'yesterday', 'last_week', 'last_month', 'last_year'] as $option)
                        <a class="dropdown-item fw-medium state_option" data-option="{{ $option }}" href="javascript:void(0)">{{ ucfirst(str_replace('_', ' ', $option)) }}</a>
                        <div class="dropdown-divider"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    @include('dashboard.today')
    @include('dashboard.yesterday')
    @include('dashboard.last_week')
    @include('dashboard.last_month')
    @include('dashboard.last_year')

    <div class="row items-push">
        @foreach ([
            ['count' => $active_waiters_count, 'label' => 'Active App Users', 'url' => '/app-users/active-listing'],
            ['count' => $tables_count, 'label' => 'Active Tables', 'url' => '/serve-tables/active-listing'],
            ['count' => $pending_orders_count, 'label' => 'Open Orders', 'url' => '/orders/open-listing'],
            ['count' => $declined_orders_count, 'label' => 'Declined Orders', 'url' => '/orders/declined-listing'],
            ['count' => $cancelled_orders_count, 'label' => 'Cancelled Orders', 'url' => '/orders/cancelled-listing'],
            ['count' => $completed_orders_count, 'label' => 'Completed Orders', 'url' => '/orders/completed-listing'],
        ] as $item)
            <div class="col-lg-2 col-md-4 col-sm-4 col-xxl-2">
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{ $item['count'] }}</dt>
                            <dd class="fs-sm fw-medium text-muted mb-0">{{ $item['label'] }}</dd>
                        </dl>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ url($item['url']) }}">
                            <span>View All</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Today's Orders</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <form method="post" id="data-search-form">
                    <table class="table table-striped table-hover table-vcenter" id="myDataTable">
                        <thead class="fs-sm">
                            <tr class="heading">
                                <td><input type="text" class="form-control" id="s_order_no" placeholder="Order No"></td>
                                <td>
                                    <select class="form-control" id="s_user_id">
                                        <option value="-1">Select</option>
                                        @foreach ($users_array as $value)
                                            <option value="{{ $value->user_id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" id="s_table_id">
                                        <option value="-1">Select</option>
                                        @foreach ($tables_array as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" class="form-control" id="s_order_value" placeholder="Order Value"></td>
                                <td>
                                    <select class="form-control" id="s_status">
                                        <option value="-1">Select</option>
                                        @foreach (['4' => 'Declined', '5' => 'Accepted', '6' => 'Preparing', '7' => 'Ready', '8' => 'Picked', '9' => 'Collected'] as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr class="heading">
                                <th>Order No</th>
                                <th>User</th>
                                <th>Table</th>
                                <th>Order Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="fs-sm"></tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('headerInclude')
<link rel="stylesheet" href="{{ asset_url('css/oneui.min-5.1.css') }}">
@include('datatables.css')
@endsection

@section('footerInclude')
@include('datatables.js')
@endsection

@push('scripts')
<script src="{{ asset_url('bundles/chartjs/chart.min.js') }}"></script>
<script src="{{ asset_url('bundles/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset_url('bundles/jquery.sparkline.min.js') }}"></script>
<script>
$(document).ready(function () {
    $('.state_option').click(function () {
        $('.state_option_div').addClass('hide');
        var option = $(this).data('option');
        $('#state_div_' + option).removeClass('hide');
    });

    todaySalesChart();
    yesterdaySalesChart();
    last_weekSalesChart();
    last_monthSalesChart();
    last_yearSalesChart();
});

jQuery(document).ready(function () {
    var oTable = $('#myDataTable').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        stateSave: true,
        searching: false,
        dom: 'Blfrtip',
        autoWidth: false,
        buttons: [
            { extend: 'excel', exportOptions: { columns: ':visible' } },
            { extend: 'pdf', exportOptions: { columns: ':visible' } },
            { extend: 'print', exportOptions: { columns: ':visible' } },
            'colvis'
        ],
        columnDefs: [{ targets: -1, visible: true }],
        ajax: {
            url: "{!! route('orders_dashboard_datatable') !!}",
            data: function (d) {
                d.order_no = $('#s_order_no').val();
                d.user_id = $('#s_user_id').val();
                d.table_id = $('#s_table_id').val();
                d.order_value = $('#s_order_value').val();
                d.status = $('#s_status').val();
            }
        },
        columns: [
            { data: 'order_no', name: 'order_no' },
            { data: 'user_id', name: 'user_id' },
            { data: 'table_id', name: 'table_id' },
            { data: 'order_value', name: 'order_value' },
            { data: 'status', name: 'status' },
        ]
    });

    $('#data-search-form').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });

    $('#s_order_no, #s_user_id, #s_table_id, #s_order_value, #s_status').on('change keyup', function (e) {
        oTable.draw();
        e.preventDefault();
    });
});
</script>
@endpush
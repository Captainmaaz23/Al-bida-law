@extends('layouts.app')

@section('content')
<?php $AUTH_USER = Auth::user(); ?>

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">{{ $list_title}}</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                </ol>
            </nav>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">
    <div class="block block-rounded block-themed">
        <div class="block-content">
            @if($recordsExists == 1)
            <form method="post" role="form" id="data-search-form">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="myDataTable">
                        <thead>
                            <tr role="row" class="heading">
                                @if($AUTH_USER->rest_id == 0)
                                    <td>
                                        <select class="form-control js-select2 form-select rest_select2" id="s_rest_id">
                                            <option value="-1">Select</option>
                                            @foreach($restaurants_array as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endif
                                <td><input type="text" class="form-control" id="s_order_no" autocomplete="off" placeholder="Order No"></td>
                                <td>
                                    <select class="form-control" id="s_user_id">
                                        <option value="-1">Select</option>
                                        @foreach($users_array as $value)
                                            <option value="{{ $value->user_id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" id="s_table_id">
                                        <option value="-1">Select</option>
                                        @foreach($tables_array as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" class="form-control" id="s_order_value" autocomplete="off" placeholder="Order Value"></td>
                                <td>
                                    <select class="form-control" id="s_status">
                                        @foreach($filter_array as $value => $label)
                                            <option value="{{ $value }}" {{ isset($filter_status) && $value == $filter_status ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr role="row" class="heading">
                                @if($AUTH_USER->rest_id == 0)
                                <th>Restaurant</th>
                                @endif
                                <th>Order No</th>
                                <th>User</th>
                                <th>Table</th>
                                <th>Order Value</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </form>
            @else
            <p class="text-center font-weight-bold py-5">No Records Available</p>
            @endif
        </div>
    </div>
</div>

@endsection

@if($recordsExists == 1)
@section('headerInclude')
@include('datatables.css')
@endsection

@section('footerInclude')
@include('datatables.js')
@endsection
@endif

@push('scripts')
<script>
    jQuery(document).ready(function () {
        @if($recordsExists == 1)
            var oTable = $('#myDataTable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                searching: false,
                dom: 'Blfrtip',
                autoWidth: false,
                buttons: [
                    { extend: 'excel', exportOptions: { columns: ':visible' }},
                    { extend: 'pdf', exportOptions: { columns: ':visible' }},
                    { extend: 'print', exportOptions: { columns: ':visible' }},
                    'colvis'
                ],
                columnDefs: [{ targets: -1, visible: true }],
                ajax: {
                    url: "{!! route('orders_datatable') !!}",
                    data: function (d) {
                        @if($AUTH_USER->rest_id == 0)
                            d.rest_id = $('#s_rest_id').val();
                        @endif                        
                        d.order_no = $('#s_order_no').val();
                        d.table_id = $('#s_table_id').val();
                        d.order_value = $('#s_order_value').val();
                        d.status = $('#s_status').val();
                    }
                },
                columns: [
                    @if($AUTH_USER->rest_id == 0)
                        { data: 'rest_id', name: 'rest_id' },
                    @endif
                    {data: 'order_no', name: 'order_no'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'table_id', name: 'table_id'},
                    {data: 'order_value', name: 'order_value'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'}
                ]
            });

            $('#data-search-form').on('submit', function (e) {
                oTable.draw();
                e.preventDefault();
            });

            $('#s_rest_id, #s_order_no, #s_user_id, #s_table_id, #s_order_value, #s_status').on('change keyup', function (e) {
                oTable.draw();
                e.preventDefault();
            });
        @endif
    });
</script>
@endpush
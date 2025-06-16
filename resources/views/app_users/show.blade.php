@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                App User Details: {{ $Model_Data->name }} 
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('app-users.index') }}">App Users</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        View Details
                    </li>
                </ol>
            </nav>
            <a href="{{ route('app-users.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">App User Details</h3>
        </div>
        <div class="block-content">
            @include('app_users.show_fields')
        </div>
    </div>

    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">App User Orders</h3>
        </div>
        <div class="block-content">                     
            @include('app_users.user_orders')
        </div>
    </div>

    <input type="hidden" class="form-control" value="{{ $Model_Data->id }}">
</div>

@endsection

@if($UserOrders_exists == 1)
    @section('headerInclude')
        @include('datatables.css')
    @endsection
    
    @section('footerInclude')
        @include('datatables.js')
    @endsection
@endif

@push('scripts') 

<script>
    jQuery(document).ready(function(e) {
        @if($UserOrders_exists == 1)
            var oTable2 = $('#myDataTable2').DataTable({
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
                    url: "{!! route('order_datatable', $Model_Data->id) !!}",
                    data: function (d) {  
                        //d.rest_id = $('#s_rest_id').val();
                        d.order_no = $('#s_order_no_2').val();
                        d.order_value = $('#s_order_value').val();
                        d.order_status = $('#s_order_status').val();
                    }
                },
                columns: [
                    //{ data: 'rest_name', name: 'rest_name' },
                    { data: 'order_no', name: 'order_no' },
                    { data: 'order_value', name: 'order_value' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' }
                ]
            });

            $('#s_rest_id, #s_order_no_2, #s_order_value, #s_order_status').on('change keyup', function (e) {
                oTable2.draw();
                e.preventDefault();
            });
        @endif
    });
</script>

@endpush

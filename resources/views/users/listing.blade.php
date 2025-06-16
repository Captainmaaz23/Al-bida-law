@extends('layouts.app')

@section('headerInclude')
    @include('datatables.css')
@endsection

@section('content')
<?php
$AUTH_USER = Auth::user();
?>
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
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </nav>
                @if(Auth::user()->can('users-add') || Auth::user()->can('all'))
                    <a href="{{ url('/users/create') }}" class="btn btn-primary btn-add-new pull-right">
                        <i class="fa fa-plus-square fa-lg"></i> Add New
                    </a>
                @endif
            </div>
            @include('flash::message')
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded block-themed">
            <div class="block-content">
                @if($recordsExists == 1)
<!--                    <form method="post" role="form" id="data-search-form">-->
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="myDataTable">
                                <thead>
                                    <tr role="row" class="heading">
                                        @if($AUTH_USER->rest_id == 0)
                                            <td>
                                                <select class="form-control js-select2 form-select" id="s_rest_id">
                                                    <option value="-1">Select</option>
                                                    <option value="0">Sufra App</option>
                                                    @foreach($restaurants_array as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @endif
                                        <td><input type="text" class="form-control" id="s_name" autocomplete="off" placeholder="Name"></td>
                                        <td><input type="text" class="form-control" id="s_email" autocomplete="off" placeholder="Email"></td>
                                        <td><input type="text" class="form-control" id="s_phone" autocomplete="off" placeholder="Phone"></td>
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
                                            <th>Company</th>
                                        @endif
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
<!--                    </form>-->
                @else
                    <p style="text-align:center; font-weight:bold; padding:50px;">No Records Available</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@if($recordsExists == 1)
@section('footerInclude')
    @include('datatables.js')
@endsection
@endif

@push('scripts')
<script>
    jQuery(document).ready(function(e) {
        var oTable = $('#myDataTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            dom: 'Blfrtip',
            autoWidth: false,
            buttons: [
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ],
            columnDefs: [{ targets: -1, visible: true }],
            ajax: {
                url: "{!! route('users_datatable') !!}",
                data: function(d) {
                    @if($AUTH_USER->rest_id == 0)
                        d.rest_id = $('#s_rest_id').val();
                    @endif
                    d.name = $('#s_name').val();
                    d.email = $('#s_email').val();
                    d.phone = $('#s_phone').val();
                    d.status = $('#s_status').val();
                }
            },
            columns: [
                @if($AUTH_USER->rest_id == 0)
                    {data: 'company_name', name: 'company_name'},
                @endif
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'}
            ]
        });

        $('#data-search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });

        $('#s_name, #s_email, #s_phone, #s_status').on('keyup change', function(e) {
            oTable.draw();
            e.preventDefault();
        });
    });
</script>
@endpush
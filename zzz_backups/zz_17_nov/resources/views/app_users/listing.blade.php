@extends('layouts.app')

@section('content')

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
                    <li class="breadcrumb-item active" aria-current="page">App Users</li>
                </ol>
            </nav>
            @if(Auth::user()->can('app-users-add') || Auth::user()->can('all'))
                <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-add-new pull-right">
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
                <form method="post" role="form" id="data-search-form">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="myDataTable">
                            <thead>
                                <tr role="row" class="heading">                                                 
                                    <td><input type="text" class="form-control" id="s_name" autocomplete="off" placeholder="Name"></td>
                                    <td><input type="text" class="form-control" id="s_username" autocomplete="off" placeholder="Username"></td>
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
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Action</th>                                                    
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </form>
            @else
                <p class="text-center font-weight-bold" style="padding:50px;">No Records Available</p>
            @endif
        </div>
    </div>
</div>

@if(Auth::user()->can('app-users-add') || Auth::user()->can('all'))
<div class="modal" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Add New</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option btn_close_modal" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <form action="{{ route('app-users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="block-content fs-sm">
                        @include('app_users.fields')
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

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
        @if ($recordsExists == 1)
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
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'print',
                        exportOptions: { columns: ':visible' }
                    },
                    'colvis'
                ],
                columnDefs: [{ targets: -1, visible: true }],
                ajax: {
                    url: "{!! route('app_users_datatable') !!}",
                    data: function (d) {
                        d.name = $('#s_name').val();
                        d.username = $('#s_username').val();
                        d.phone = $('#s_phone').val();
                        d.status = $('#s_status').val();
                    }
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'username', name: 'username' },
                    { data: 'phone', name: 'phone' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' }
                ]
            });

            $('#data-search-form').on('submit', function (e) {
                oTable.draw();
                e.preventDefault();
            });

            $('#s_name, #s_username, #s_phone').on('keyup', function () {
                oTable.draw();
            });

            $('#s_status').on('change', function () {
                oTable.draw();
            });
        @endif
    });
</script>

<script>
    jQuery(document).ready(function () {
        $('.btn_close_modal').click(function () {
            $('#createModal').modal('hide');
        });
    });
</script>
@endpush
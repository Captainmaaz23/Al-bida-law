@extends('layouts.app')

@section('content')
<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Modules</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Modules</li>
                </ol>
            </nav>
            @can('modules-add')
                <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-add-new pull-right">
                    <i class="fa fa-plus-square fa-lg"></i> Add New
                </a>
            @endcan
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-content">
            @if($recordsExists)
                <form method="post" role="form" id="data-search-form">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="myDataTable">
                            <thead>
                                <tr role="row" class="heading">
                                    <td colspan="7">
                                        <input type="text" class="form-control" id="s_title" autocomplete="off" placeholder="Title">
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr role="row" class="heading">
                                    <th>Title</th>
                                    <th>Listing</th>
                                    <th>Add</th>
                                    <th>Update</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Delete</th>
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

@if(Auth::user()->can('modules-add'))
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
                <form action="{{ route('modules.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="block-content fs-sm">
                        @include('modules.fields')
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@if($recordsExists)
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
        @if($recordsExists)
        var oTable = $('#myDataTable').DataTable({
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
                url: "{!! route('modules_datatable') !!}",
                data: function (d) {
                    d.title = $('#s_title').val();
                }
            },
            columns: [
                { data: 'title', name: 'title' },
                { data: 'listing', name: 'listing' },
                { data: 'add', name: 'add' },
                { data: 'edit', name: 'edit' },
                { data: 'view', name: 'view' },
                { data: 'status', name: 'status' },
                { data: 'delete', name: 'delete' },
                { data: 'action', name: 'action' }
            ]
        });

        $('#data-search-form').on('submit', function (e) {
            e.preventDefault();
            oTable.draw();
        });

        $('#s_title').on('keyup', function () {
            oTable.draw();
        });
        @endif
    });
</script>
<script>
    $(document).ready(function () {
        $('.radioBtn a').on('click', function () {
            var sel = $(this).data('title');
            var tog = $(this).data('toggle');
            $('#' + tog).val(sel);

            $('a[data-toggle="' + tog + '"]').not('[data-title="' + sel + '"]').removeClass('active').addClass('notActive');
            $('a[data-toggle="' + tog + '"][data-title="' + sel + '"]').removeClass('notActive').addClass('active');
        });

        $('.btn_close_modal').click(function () {
            $('#createModal').modal('hide');
        });
    });
</script>
@endpush

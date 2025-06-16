@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Roles</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Roles</li>
                </ol>
            </nav> 
            @canany(['roles-add', 'all'])
            <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-add-new pull-right">
                <i class="fa fa-plus-square fa-lg"></i> Add New
            </a>
            @endcanany
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
                                <td>
                                    <input type="text" class="form-control" id="s_title" autocomplete="off" placeholder="Title">
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr role="row" class="heading">
                                <th>Title</th>
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

@if(auth()->user()->can('roles-add') || auth()->user()->can('all'))
<div class="modal" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Add New</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="block-content fs-sm">
                        @include('roles.fields')
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
    @if ($recordsExists)
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
            ajax: {
            url: "{!! route('roles_datatable') !!}",
                    data: function (d) {
                    d.title = $('#s_title').val();
                    }
            },
            columns: [
            { data: 'title', name: 'title' },
            { data: 'action', name: 'action' }
            ]
    });
    $('#s_title').on('keyup', function (e) {
    oTable.draw();
    e.preventDefault();
    });
    @endif
    });
</script>
@endpush
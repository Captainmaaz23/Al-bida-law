@extends('layouts.app')

@section('content')
@php
$AUTH_USER = Auth::user();
@endphp
<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Addon Types</h1>
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Addon Types</li>
                </ol>
            </nav>
            @can('addon-types-add')
                <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-add-new">
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
                <form method="post" id="data-search-form">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="myDataTable">
                            <thead>
                                <tr role="row" class="heading">
                                    @if($AUTH_USER->rest_id == 0)
                                        <td>
                                            <select class="form-control js-select2" id="s_rest_id">
                                                <option value="-1">Select</option>
                                                @foreach ($restaurants_array as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @endif
                                    <td>
                                        <select class="form-control js-select2" id="s_item_id">
                                            <option value="-1">Select</option>
                                            @foreach ($Items as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="s_name" autocomplete="off" placeholder="Name">
                                    </td>
                                    <td>
                                        <select class="form-control" id="s_mandatory">
                                            <option value="-1">Select</option>
                                            <option value="1">Enabled</option>
                                            <option value="0">Disabled</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" id="s_multi_select">
                                            <option value="-1">Select</option>
                                            <option value="1">Enabled</option>
                                            <option value="0">Disabled</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" id="s_maximum" placeholder="Maximum Selection" min="0" max="10">
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr role="row" class="heading">
                                    <th>Restaurant</th>
                                    <th>Item</th>
                                    <th>Name</th>
                                    <th>Mandatory</th>
                                    <th>MultiSelect</th>
                                    <th>Maximum Selection</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </form>
            @else
                <p style="text-align:center; font-weight:bold; padding:50px;">No Records Available</p>
            @endif
        </div>
    </div>
</div>

@if(Auth::user()->can('addon-types-add'))
<div class="modal" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
                <form action="{{ route('addon-types.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="block-content fs-sm">
                        @include('addon_types.fields')
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('headerInclude')
    @if($recordsExists)
        @include('datatables.css')
    @endif
@endsection

@section('footerInclude')
    @if($recordsExists)
        @include('datatables.js')
    @endif
@endsection

@push('scripts')
<script>
    jQuery(document).ready(function () {
        var oTable = $('#myDataTable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            dom: 'Blfrtip',
            autoWidth: false,
            buttons: ['excel', 'pdf', 'print', 'colvis'],
            ajax: {
                url: "{!! route('addon_types_datatable') !!}",
                data: function (d) {
                    d.rest_id = $('#s_rest_id').val();
                    d.name = $('#s_name').val();
                    d.item_id = $('#s_item_id').val();
                    d.is_mandatory = $('#s_mandatory').val();
                    d.is_multi_select = $('#s_multi_select').val();
                    d.max_selection = $('#s_maximum').val();
                }
            },
            columns: [
                {data: 'rest_id', name: 'rest_id'},
                {data: 'item_name', name: 'item_name'},
                {data: 'name', name: 'name'},
                {data: 'is_mandatory', name: 'is_mandatory'},
                {data: 'is_multi_select', name: 'is_multi_select'},
                {data: 'max_selection', name: 'max_selection'},
                {data: 'action', name: 'action'}
            ]
        });

        $('#data-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    });
</script>
@endpush
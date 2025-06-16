@extends('layouts.app')

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
                    <li class="breadcrumb-item active" aria-current="page">Tables</li>
                </ol>
            </nav>
            @canany(['serve-tables-add', 'all'])
                <a href="{{ route('serve-tables.create') }}" class="btn btn-primary btn-add-new pull-right">
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
<!--                <form method="post" role="form" id="data-search-form">-->
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

                                    <td>
                                        <input type="text" class="form-control" id="s_title" autocomplete="off" placeholder="Title">
                                    </td>
                                    <td>
                                        <select class="form-control" id="s_availability">
                                            <option value="-1">Select</option>
                                            <option value="1">Available</option>
                                            <option value="0">Not Available</option>
                                        </select>
                                    </td>
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
                                    <th>Restaurants</th>
                                    @endif
                                    <th>Title</th>
                                    <th>Availability</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
<!--                </form>-->
            @else
                <p class="text-center font-weight-bold" style="padding:50px;">No Records Available</p>
            @endif
        </div>
    </div>
</div>
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
        const tableOptions = {
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
                url: "{!! route('serve_tables_datatable') !!}",
                data: function (d) {
                    d.title = $('#s_title').val();
                    d.availability = $('#s_availability').val();
                    d.status = $('#s_status').val();
                    @if($AUTH_USER->rest_id == 0)
                    d.rest_id = $('#s_rest_id').val();
                    @endif
                }
            },
            columns: [
                @if($AUTH_USER->rest_id == 0)
                    { data: 'rest_id', name: 'rest_id' },
                @endif
                { data: 'title', name: 'title' },
                { data: 'availability', name: 'availability' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' }
            ]
        };

        var oTable = $('#myDataTable').DataTable(tableOptions);

        /*$('#data-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });*/

        $('#s_title, #s_rest_id, #s_availability, #s_status').on('change keyup', function (e) {
            oTable.draw();
        });
    });
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function () {
        $("tbody").sortable({
            update: function () {
                slide_count();
            }
        });
    });

    function slide_count() {
        $('.spncount').each(function (index) {
            $(this).html(index + 1);
        });
    }
</script>
@endpush
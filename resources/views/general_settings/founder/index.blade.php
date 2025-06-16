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
                        <li class="breadcrumb-item active" aria-current="page">Company</li>
                    </ol>
                </nav>  
                @can('case-study.create')
                    <a href="{{ route('founder.create') }}" class="btn btn-primary btn-add-new pull-right">
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
<!--                    <form method="post" role="form" id="data-search-form">-->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="myDataTable">
                                <thead>
                                    <tr role="row" class="heading">
                                        @if($AUTH_USER->rest_id == 0)
                                            <td>
                                                <select class="form-control js-select2 form-select rest_select2" id="s_rest_id">
                                                    <option value="-1">Select Restaurant</option>
                                                    @foreach($recordsExists as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @endif
                                        {{-- <td>
                                            <select class="form-control js-select2 form-select menu_select2" id="s_menu_id">
                                                <option value="-1">Select Menu</option>
                                                @foreach($menus_array as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td> --}}
                                        <td>
                                            <input type="text" class="form-control" id="s_text" autocomplete="off" placeholder="Title">
                                        </td>
                                        {{-- <td>
                                            <input type="number" class="form-control" id="s_price" autocomplete="off" placeholder="Description">
                                        </td> --}}
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
                                            <th>Company Detail</th>
                                        @endif
                                        <th>Founder Name</th>
                                        <th>Image</th>                                                    
                                        <th>Address</th>                                                    
                                        <th>Action</th>                                                    
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
<!--                    </form>-->
                @else
                    <p class="text-center font-weight-bold py-5">No Records Available</p>
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
        let tableOptions = {
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
                url: "{!! route('founder-record') !!}",
                data: function (d) {
                    d.name = $('#s_text').val();
                }
            },
            columns: [
                { 
                    data: 'founder_name', 
                    name: 'title', 
                    orderable: false, 
                    searchable: false 
                },
                { 
                    data: 'image', 
                    name: 'image', 
                    orderable: false, 
                    searchable: false 
                },
                { 
                    data: 'designation', 
                    name: 'designation', 
                    orderable: false, 
                    searchable: false 
                },
                { 
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false 
                },
                
            ]
        };

        var oTable = $('#myDataTable').DataTable(tableOptions);

        // Filters
        ['s_text'].forEach(function (id) {
            $('#' + id).on('keyup', function (e) {
                oTable.draw();
                e.preventDefault();
            });
        });
    });
</script>

 
@endpush
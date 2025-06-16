@extends('layouts.app')

@section('content')
    <div class="bg-body-light">
        <div class="content">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">Restaurants</h1>
            </div>
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Restaurants</li>
                    </ol>
                </nav>
                @can('restaurants-add')
                    <a href="{{ route('restaurants.create') }}" class="btn btn-primary btn-add-new pull-right">
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
                                        <td><input type="text" class="form-control" id="s_name" autocomplete="off" placeholder="Name"></td>
                                        <td>
                                            <select class="form-control" id="s_open">
                                                <option value="-1">Select</option>
                                                <option value="1">Open</option>
                                                <option value="2">Busy</option>
                                                <option value="0">Closed</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" id="s_is_featured">
                                                <option value="-1">Select</option>
                                                <option value="1">Is Featured</option>
                                                <option value="0">Not Featured</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" id="s_status">
                                                <option value="-1">Select</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr role="row" class="heading">
                                        <th>Name</th>
                                        <th>Open</th>
                                        <th>Featured</th>
                                        <th>Status</th>
                                        <th>Actions</th>
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
        jQuery(document).ready(function() {
            @if($recordsExists)
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
                    columnDefs: [{
                        targets: -1,
                        visible: true
                    }],
                    ajax: {
                        url: "{!! route('restaurants_datatable') !!}",
                        data: function (d) {
                            d.name = $('#s_name').val();
                            d.is_open = $('#s_open').val();
                            d.is_featured = $('#s_is_featured').val();
                            d.status = $('#s_status').val();
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'is_open', name: 'is_open'},
                        {data: 'is_featured', name: 'is_featured'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', searchable: false, orderable: false}
                    ]
                });

                $('#data-search-form').on('submit', function (e) {
                    oTable.draw();
                    e.preventDefault();
                });

                $('#s_name, #s_open, #s_is_featured, #s_status').on('change keyup', function (e) {
                    oTable.draw();
                    e.preventDefault();
                });
            @endif
        });

        $(document).on('click', '.btnActive, .btnInActive', function() {
            var id = $(this).attr("id");
            var url = $(this).hasClass('btnActive') ? '{{url("restaurant/inactive")}}/' + id : '{{url("restaurant/active")}}/' + id;

            $.ajax({
                url: url,
                type: 'GET',
                success: function(result) {
                    if(result === true) {
                        location.href = "{{url('restaurants/index')}}";
                    }
                }
            });
        });
    </script>
@endpush
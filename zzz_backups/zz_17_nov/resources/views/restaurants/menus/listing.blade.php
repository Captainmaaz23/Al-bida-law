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
                    <li class="breadcrumb-item active" aria-current="page">Menus</li>
                </ol>
            </nav>
            @can('menus-add')
                <a href="{{ route('menus.create') }}" class="btn btn-primary btn-add-new pull-right">
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
                @if($AUTH_USER->rest_id > 0 && $menus->isNotEmpty())
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#menuOrderModal">
                                Change Order
                            </a>

                            <div class="modal" id="menuOrderModal" tabindex="-1" role="dialog" aria-labelledby="menuOrderModal" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="block block-rounded block-themed mb-0">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">Edit Menus Order</h3>
                                                <div class="block-options">
                                                    <button type="button" class="btn-block-option btn_close_modal" data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="fa fa-fw fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-lg-1">&nbsp;</div>
                                                <div class="col-lg-10">
                                                    <form action="{{ route('restaurants_menus.order', $AUTH_USER->rest_id) }}" method="POST">
                                                        @csrf
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover">
                                                                <thead>
                                                                    <tr class="heading">
                                                                        <th>#</th>
                                                                        <th>Menus</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($menus as $index => $menu)
                                                                        <tr>
                                                                            <td>
                                                                                <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                                                <span class="spncount">{{ $index + 1 }}</span>
                                                                                <input type="hidden" name="menu_order[]" value="{{ $menu->id }}" />
                                                                            </td>
                                                                            <td>{{ $menu->title }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <div class="mt-4 mb-3 row">
                                                            <div class="col-sm-12"><hr /></div>
                                                            <div class="form-group col-sm-12 text-right">
                                                                <button type="submit" class="btn btn-primary">Save Order</button>
                                                                <a href="{{ route('restaurants.index') }}" class="btn btn-outline-dark">Cancel</a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-lg-1">&nbsp;</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="post" role="form" id="data-search-form">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="myDataTable">
                            <thead>
                                <tr class="heading">
                                    @if($AUTH_USER->rest_id == 0)
                                        <td>
                                            <select class="form-control js-select2 form-select" id="s_rest_id">
                                                <option value="-1">Select</option>
                                                @foreach($restaurants_array as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
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
                                    @else
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
                                                <option value="-1">Select</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </td>
                                    @endif
                                    <td>&nbsp;</td>
                                </tr>
                                <tr class="heading">
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
                </form>
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
    $(document).ready(function() {
        const oTable = $('#myDataTable').DataTable({
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
            ajax: {
                url: "{!! route('menus_datatable') !!}",
                data: function(d) {
                    //d.rest_id = $('#s_rest_id').val();
                    d.title = $('#s_title').val();
                    d.availability = $('#s_availability').val();
                    d.status = $('#s_status').val();
                }
            },
            columns: [
                //{data: 'rest_id', name: 'rest_id'},
                {data: 'title', name: 'title'},
                {data: 'availability', name: 'availability'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'}
            ]
        });

        $('#data-search-form').on('submit', function(e) {
            e.preventDefault();
            oTable.draw();
        });

        $('#s_title, #s_rest_id, #s_availability, #s_status').on('change keyup', function(e) {
            oTable.draw();
        });
    });
</script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function(e) {
        $( "tbody" ).sortable(
            {
                update: function() {
                    slide_count();
                }
            });
    });

    function slide_count()
    {
        var btncount = 0;
        jQuery('.spncount').each(function(index, element) {
            var value = index;
            value++;
            $(this).html(value);
        });
    }
</script> 
@endpush
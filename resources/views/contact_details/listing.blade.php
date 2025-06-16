@extends('layouts.app')

@section('content')
<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">General Settings</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">General Settings</li>
                </ol>
            </nav>  
            @canany(['contact-details-edit', 'all'])
            <a href="{{ route('contact_details_edit_Settings') }}" class="btn btn-primary btn-add-new pull-right">Edit Settings</a>
            @endcanany
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-content">
            @if($recordsExists == 1)
<!--            <form method="post" role="form" id="data-search-form">-->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="myDataTable">
                        <thead>
                            <tr role="row" class="heading">                                                  
                                <td>
                                    <input type="text" class="form-control" id="s_title" autocomplete="off" placeholder="Title">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="s_value" autocomplete="off" placeholder="Value">
                                </td>
                            </tr>
                            <tr role="row" class="heading">
                                <th>Title</th>
                                <th>Value</th>                                                    
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
<!--            </form>-->
            @else
            <p style="text-align:center; font-weight:bold; padding:50px;">No Records Available</p>
            @endif
        </div>
    </div>
</div>

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
    jQuery(document).ready(function() {
        @if($recordsExists == 1)
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
                url: "{!! route('contact_details_datatable') !!}",
                data: function (d) {  
                    d.title = $('#s_title').val(); 
                    d.s_value = $('#s_value').val();
                }
            },
            columns: [
                { data: 'title', name: 'title' },
                { data: 'value', name: 'value' }
            ]
        });

        /*$('#data-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });*/

        $('#s_title, #s_value').on('keyup', function () {
            oTable.draw();
        });
        @endif
    });
</script> 
@endpush
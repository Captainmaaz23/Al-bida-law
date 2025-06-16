@extends('layouts.app')

@section('content')
<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Order No : {{ $Model_Data->order_no }}
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('orders.index') }}">Orders</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">View Details</li>
                </ol>
            </nav>
            <a href="{{ route('orders.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-sm-6">
            
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Order Details</h3>
                </div>
                <div class="block-content">
                    @include('restaurants.orders.order_fields')
                </div>
            </div>
            
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">User Information</h3>
                </div>
                <div class="block-content">
                    @include('restaurants.orders.show_user')
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Ordered Items & Addons</h3>
                </div>
                <div class="block-content">
                    <a class="btn btn-dark text-white" target="_blank" href="{{ route('make-order-pdf',['id'=>$Model_Data->id]) }}">
                        Print Invoice
                    </a>
                    <div class="col-sm-12 order_no incoming_order" id="{{ $orders_common_details['id'] }}" data-lastid="{{ $orders_common_details['id'] }}" data-lat="{{ $orders_common_details['lat'] }}" data-lng="{{ $orders_common_details['lng'] }}">            
                        {!! get_common_details_html($orders_common_details) !!}            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="full_screen_modal" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="full_screen_animate" style="max-width: 900px;">
        <div class="modal-content ordr_col">
            <div class="row m0 p0">
                <div class="col-sm-10 full_screen_order" id="full_screen_order"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="{{ asset_url('tooltip.min.css') }}">
@endpush


@push('scripts')
<script>

    jQuery(document).ready(function(e)
    {
        call_events();
    });

    function call_events()
    {
        $('[data-toggle="tooltip"]').tooltip();

        if($('.order_fullscreen'))
        {
            $('.order_fullscreen').off();
            $('.order_fullscreen').click(function(e) {
                var order_id = $(this).parent().parent().parent().data('lastid');
                full_screen_order(order_id);
            });
        }
    }

    function full_screen_order(order_id)
    {
        var div = $("#"+order_id);
        div_html = div.html();
        $("#full_screen_order").html(div_html);
        $("#full_screen_order").find( ".nomodal" ).remove();
        $("#full_screen_order").find( ".inmodal" ).removeClass('hide');
    }

</script>
@endpush
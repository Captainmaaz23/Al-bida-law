@extends('layouts.k_app')

@section('content')
<?php $AUTH_USER = Auth::user(); ?>
<div class="kitchen-dashboard">
    <a href="{{ url('/dashboard') }}" class="menu-toggle-btn" title="Dashboard">
        <i class="fas fa-home"></i>
    </a>

    <div class="dashboard-content">
        <div class="dashboard-section-header">
            <div class="section-title">Incoming <span id="incoming_count">[0]</span></div>
            <div class="section-title">Preparing <span id="outgoing_count">[0]</span></div>
            <div class="section-title hide">Scheduled <span id="scheduled_count">[0]</span></div>
            <div class="section-title">Ready <span id="ready_count">[0]</span></div>
        </div>
        <div class="order-sections">
            <div class="order-section" id="incoming_orders_section">
                <div class="action-section" id="incoming_section_title">
                    <span class="select_all_container">
                        <input type="checkbox" id="select_all_incoming" class="select_all_orders" />
                        <label for="select_all_incoming">Select All</label>
                    </span>
                    <span class="action_buttons">
                        <a class="btn btn-outline-danger decline_btn_batch_in">Decline</a>
                        <a class="btn btn-primary accept_btn_batch_in">Accept</a>
                    </span>
                </div>
                <div class="section-content" id="incoming_orders"></div>
            </div>

            <div class="order-section" id="preparing_orders_section">
                <div class="action-section" id="outgoing_section_title">
                    <span class="select_all_container">
                        <input type="checkbox" id="select_all_outgoing" class="select_all_orders" />
                        <label for="select_all_outgoing">Select All</label>
                    </span>
                    <span class="action_buttons">
                        <a class="btn btn-outline-danger decline_btn_batch_out">Decline</a>
                        <a class="btn btn-primary ready_btn_batch_out">Ready</a>
                    </span>
                </div>
                <div class="section-content" id="outgoing_orders"></div>
                <div class="section-content hide" id="scheduled_orders"></div>
            </div>

            <div class="order-section" id="ready_orders_section">
                <div class="action-section" id="ready_section_title">
                    <span class="select_all_container">
                        <input type="checkbox" id="select_all_ready" class="select_all_orders" />
                        <label for="select_all_ready">Select All</label>
                    </span>
                    <span class="action_buttons">
                        <a class="btn btn-primary pickup_btn_batch_ready">Serve</a>
                    </span>
                </div>
                <div class="section-content" id="ready_orders"></div>
            </div>
        </div>
    </div>
</div>

@include('restaurants.kitchen._modals')

<button class="vhide" id="play_audio" onclick="playSound('{{ get_new_order_notification_audio() }}');">Play</button>

@endsection

@push('scripts')
<link rel="stylesheet" href="{{ asset_url('tooltip.min.css') }}">
<link rel="stylesheet" href="{{ asset_url('kitchen/kitchen.css') }}">
<link rel="stylesheet" href="{{ asset_url('kitchen/new.css') }}">
<link rel="stylesheet" href="{{ asset_url('timer/timer.css') }}">

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="{{ asset_url('timer/timer.js') }}"></script>
<script>

    window.routes = {
        get_combined_orders: "{{ route('get_combined_orders') }}",
        get_incoming_orders: "{{ route('get_incoming_orders', ':id') }}",
        outgoing_orders: "{{ route('outgoing_orders', ':id') }}",
        decline_order: "{{ route('decline_order') }}",
        order_ready: "{{ route('order_ready') }}",
        order_scheduled: "{{ route('order_scheduled') }}",
        scheduled_orders: "{{ route('scheduled_orders', ':id') }}",
        order_preparing: "{{ route('order_preparing') }}",
        move_to_outgoing_orders: "{{ route('move_to_outgoing_orders') }}",
        ready_orders: "{{ route('ready_orders', ':id') }}",
        ready_order_details: "{{ route('ready_order_details', ':id') }}",
        check_pin: "{{ route('check_pin', ['id'=>':id', 'pin'=>':pin']) }}",
        pickup_orders: "{{ route('pickup_orders', ':id') }}",
        decline_order_batch: "{{ route('decline_order_batch') }}",
        order_scheduled_batch: "{{ route('order_scheduled_batch') }}",
        order_preparing_batch: "{{ route('order_preparing_batch') }}",
        move_to_outgoing_orders_batch: "{{ route('move_to_outgoing_orders_batch') }}",
        order_ready_batch: "{{ route('order_ready_batch') }}",
        ready_orders_batch: "{{ route('ready_orders_batch', ':id') }}",
        ready_order_details_batch: "{{ route('ready_order_details_batch', ':id') }}",
        pickup_orders_batch: "{{ route('pickup_orders_batch', ':id') }}",
        order_reschedule_batch: "{{ route('order_reschedule_batch', ':id') }}"
    };</script>
<script src="{{ asset_url('kitchen/kitchen.js') }}"></script>
<script src="{{ asset_url('kitchen/orders.js') }}"></script>
<script src="{{ asset_url('kitchen/order_templates.js') }}"></script>
@endpush
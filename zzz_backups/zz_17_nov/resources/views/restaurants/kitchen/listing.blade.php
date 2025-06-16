@extends('layouts.k_app')

@section('content')
<?php $AUTH_USER = Auth::user(); ?>
<div class="kitchen-dashboard">
    <header class="dashboard-header">
        <a href="{{ url('/dashboard') }}">
            <h3 class="dashboard-title">Dashboard</h3>
        </a>
    </header>

    <div class="dashboard-content">
        <!-- Section Headers -->
        <div class="dashboard-section-header">
            <div class="section-title">Incoming <span id="incoming_count">[0]</span></div>
            <div class="section-title">Preparing <span id="outgoing_count">[0]</span></div>
            <div class="section-title hide">Scheduled <span id="scheduled_count">[0]</span></div>
            <div class="section-title">Ready <span id="ready_count">[0]</span></div>
        </div>

        <!-- Order Sections -->
        <div class="order-sections">
            <!-- Incoming Orders -->
            <div class="order-section" id="incoming_orders_section">
                <div class="section-content" id="incoming_orders">
                    <button id="get_orders_btn" class="btn-primary" onclick="get_orders()">Get Orders</button>
                    <!--                    <button id="get_orders_btn" class="btn btn-primary w-100" onclick="get_orders()">Get Orders</button>-->
                </div>
            </div>

            <!-- Preparing Orders -->
            <div class="order-section" id="preparing_orders_section">
                <div class="section-content" id="outgoing_orders"></div>
                <div class="section-content hide" id="scheduled_orders"></div>
            </div>

            <!-- Ready Orders -->
            <div class="order-section" id="ready_orders_section">
                <div class="section-content" id="ready_orders"></div>
            </div>
        </div>
    </div>
</div>

{{-- Accept Modal --}}
<div id="accept_modal" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="accept_animate" style="max-width: 1000px;">
        <div class="modal-content ordr_col">
            <div class="row align-items-center">
                <div class="col-sm-6 m0 p0">
                    <div id="accept_order" class="w100 accept_order"></div>
                </div>

                <div class="col-sm-1 separator">&nbsp;</div>

                <div class="col-sm-5 text-center" id="confirmation_div">
                    <div class="pos_fixed text-center">
                        <div class="col-sm-12 mb-3">
                            <h2><b>Ready in <span id="span_estimate">5</span> min.</b></h2>
                            <h2><b>Can you make it?</b></h2>
                            <div class="fs-sm fw-medium text-muted">We'll send this update to the customer</div>
                        </div>

                        <div class="col-sm-12 mb-2" data-duration="10">
                            <a class="btn btn-primary w-100" onclick="insert_outgoing();">Yes, let's prepare it!</a>
                        </div>

                        <div class="col-sm-12 mb-2">
                            <a class="btn btn-outline-primary change_estimation w-100">No, change estimate.</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5 text-center hide" id="estimation_div">
                    <div class="pos_fixed text-center">
                        <div class="col-sm-12 row m-0">
                            @foreach([0, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60] as $mins)
                            <div class="col-sm-4">
                                <a class="btn btn-info estbox" data-mins="{{ $mins }}">
                                    {{ $mins == 0 ? 'Just Now' : $mins }}
                                    <br>
                                    <small>Mins</small>
                                </a>
                            </div>
                            @endforeach

                            <div class="col-sm-12">
                                <a class="btn btn-danger custom_estimation">Custom</a>
                            </div>
                            <div class="col-sm-12">
                                <a class="btn btn-dark back_to_confirm">OK</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5 text-center hide" id="custom_div">
                    <div class="pos_fixed text-center">
                        <div class="col-sm-12 mb-3">
                            <h2><b>Custom Estimated Time</b></h2>
                            <div class="fs-sm fw-medium text-muted">Order can be ready in</div>
                        </div>

                        <div class="col-sm-12 mb-3">
                            <select id="custom_time" class="form-control">
                                <option value="">Select Time</option>
                                @for ($i = 1; $i <= 60; $i++)
                                <option value="{{ $i }}">{{ sprintf('%02d', $i) }} Mins</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-sm-12">
                            <a class="btn btn-dark back_to_estimation">OK</a>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="accept_order_id" value="0" />
                <input type="hidden" id="estimation_time" value="0" />
            </div>
        </div>
    </div>
</div>

{{-- Schedule Modal --}}
<div id="schedule_modal" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="schedule_animate" style="max-width: 1000px;">
        <div class="modal-content ordr_col">
            <div class="row align-items-center">
                <div class="col-sm-6 m0 p0">
                    <div id="schedule_order" class="w100 accept_order"></div>
                </div>

                <div class="col-sm-1 separator">&nbsp;</div>

                <div class="col-sm-5 text-center" id="schedule_confirmation_div">
                    <div class="pos_fixed text-center">
                        <div class="col-sm-12 mb-3">
                            <h2><b>Schedule in <span id="span_schedule_estimate">5</span> min.</b></h2>
                            <h2><b>Can you make it?</b></h2>
                            <div class="fs-sm fw-medium text-muted">We'll send this update to the customer</div>
                        </div>

                        <div class="col-sm-12 mb-2" data-duration="10">
                            <a class="btn btn-primary w-100" onclick="insert_schedule();">Yes, let's schedule it!</a>
                        </div>

                        <div class="col-sm-12 mb-2">
                            <a class="btn btn-outline-primary change_schedule_estimation w-100">No, change estimate.</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5 text-center hide" id="schedule_estimation_div">
                    <div class="pos_fixed text-center">
                        <div class="col-sm-12 row m-0">
                            @foreach([5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60] as $mins)
                            <div class="col-sm-4">
                                <a class="btn btn-info schedule_estbox" data-mins="{{ $mins }}">
                                    {{ $mins }}
                                    <br>
                                    <small>Mins</small>
                                </a>
                            </div>
                            @endforeach

                            <div class="col-sm-12">
                                <a class="btn btn-danger custom_schedule_estimation">Custom</a>
                            </div>
                            <div class="col-sm-12">
                                <a class="btn btn-dark back_to_schedule_confirm">OK</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5 text-center hide" id="schedule_custom_div">
                    <div class="pos_fixed text-center">
                        <div class="col-sm-12 mb-3">
                            <h2><b>Custom Estimated Time</b></h2>
                            <div class="fs-sm fw-medium text-muted">Order can be ready in</div>
                        </div>

                        <div class="col-sm-12 mb-3">
                            <select id="custom_schedule_time" class="form-control">
                                <option value="">Select Time</option>
                                @for ($i = 1; $i <= 60; $i++)
                                <option value="{{ $i }}">{{ sprintf('%02d', $i) }} Mins</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-sm-12">
                            <a class="btn btn-dark back_to_schedule_estimation">OK</a>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="schedule_order_id" value="0" />
                <input type="hidden" id="schedule_estimation_time" value="0" />
            </div>
        </div>
    </div>
</div>

{{-- Decline Modal --}}
<div id="decline_modal" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="decline_animate" style="max-width: 1000px;">
        <div class="modal-content ordr_col">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div id="decline_order" class="w-100"></div>
                </div>

                <div class="col-sm-1 separator">&nbsp;</div>

                <div class="col-sm-5 text-center">
                    <h2><b>Decline this Order</b></h2>
                    <h2><b>Are you sure?</b></h2>
                    <textarea class="form-control" id="decline_reason" rows="3" placeholder="Reason to Decline"></textarea>
                    <input type="hidden" id="decline_order_id" value="0" />
                    <div class="mt-3">
                        <a class="btn btn-primary decline_submit_btn w-100">Yes, let's do it!</a>
                    </div>
                    <div class="mt-2">
                        <a class="btn btn-outline-primary cancel_decline_order w-100">No</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Full Screen Modal --}}
<div id="full_screen_modal" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="full_screen_animate" style="max-width: 900px;">
        <div class="modal-content ordr_col">
            <div class="row m-0">
                <div id="full_screen_order" class="col-sm-10"></div>
            </div>
        </div>
    </div>
</div>

{{-- Ready Modal --}}
<div id="ready_modal" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="ready_animate" style="max-width: 1000px;">
        <div class="modal-content ordr_col">
            <div class="row align-items-center">
                <div class="col-sm-12 text-center">
                    <input type="hidden" id="ready_order_id" value="0" />
                    <div id="ready_order_details" class="ready_order_details mt-3"></div>
                    <div class="mt-3">
                        <a class="btn btn-primary" id="pin_submit" class="w-100">Serve this Order</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
        order_ready: "{{ route('order_ready') }}",
        order_scheduled: "{{ route('order_scheduled') }}",
        scheduled_orders: "{{ route('scheduled_orders', ':id') }}",
        order_preparing: "{{ route('order_preparing') }}",
        move_to_outgoing_orders: "{{ route('move_to_outgoing_orders') }}",
        ready_orders: "{{ route('ready_orders', ':id') }}",
        ready_order_details: "{{ route('ready_order_details', ':id') }}",
        check_pin: "{{ route('check_pin', ['id'=>':id', 'pin'=>':pin']) }}",
        pickup_orders: "{{ route('pickup_orders', ':id') }}"
    };</script>
<script src="{{ asset_url('kitchen/kitchen.js') }}"></script>
<script src="{{ asset_url('kitchen/orders.js') }}"></script>
<script src="{{ asset_url('kitchen/order_templates.js') }}"></script>
@endpush
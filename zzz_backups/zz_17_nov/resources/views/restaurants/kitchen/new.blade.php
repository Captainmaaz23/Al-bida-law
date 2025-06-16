@extends('layouts.k_app')

@section('content')
<?php $AUTH_USER = Auth::user(); ?>

<div class="content">
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <a href="{{ url('/dashboard') }}">
                <h3 class="block-title">Dashboard</h3>
            </a>
        </div>

        <div class="block-content">
            <div class="row text-center">
                <div class="col-sm-5">
                    <p>
                        <strong>Incoming</strong>
                        <span id="incoming_count">[0]</span>
                    </p>
                </div>
                <div class="col-sm-5">
                    <div class="row">
                        <div class="col-sm-6 text-right">
                            <p>
                                <strong>Preparing</strong>
                                <span id="outgoing_count">[0]</span>
                            </p>
                        </div>
                        <div class="col-sm-6 text-left hide">
                            <p>
                                <strong>Scheduled</strong>
                                <span id="scheduled_count">[0]</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <p>
                        <strong>Ready</strong>
                        <span id="ready_count">[0]</span>
                    </p>
                </div>
            </div>

            <div class="row ktchn_row">
                <div class="col-sm-5">
                    <div class="block block-rounded block-themed">
                        <div class="block-content ktchn_col" id="incoming_orders">
                            <div class="text-center">
                                <button id="get_orders_btn" class="btn btn-primary w-100" onclick="get_orders()">Get Orders</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="block block-rounded block-themed">
                        <div class="block-content ktchn_col ktchn_col_sub">
                            <div id="outgoing_orders"></div>
                        </div>
                        <div class="block-content ktchn_col ktchn_col_sub hide">
                            <div id="scheduled_orders"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="block block-rounded block-themed">
                        <div class="block-content ktchn_col" id="ready_orders"></div>
                    </div>
                </div>
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
                    <div class="mt-3">
                        <a class="btn btn-primary" id="pin_submit" class="w-100">Serve this Order</a>
                    </div>
                    <input type="hidden" id="ready_order_id" value="0" />
                    <div id="ready_order_details" class="ready_order_details mt-3"></div>
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
<link rel="stylesheet" href="{{ asset_url('timer/timer.css') }}">

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="{{ asset_url('timer/timer.js') }}"></script>
<script>

    window.routes = {
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
            pickupOrder: "{{ route('pickup_orders', ':id') }}"
    };
</script>
<script src="{{ asset_url('kitchen/kitchen.js') }}"></script>
<script src="{{ asset_url('kitchen/orders.js') }}"></script>
<script src="{{ asset_url('kitchen/order_templates.js') }}"></script>
@endpush
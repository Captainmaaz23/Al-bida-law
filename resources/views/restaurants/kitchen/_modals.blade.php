
<a class="hide accept_batch_in_modal" data-toggle="modal" data-target="#accept_modal">Accept</a>
<a class="hide decline_batch_in_modal" data-toggle="modal" data-target="#decline_modal">Decline</a>
<a class="hide decline_batch_out_modal" data-toggle="modal" data-target="#decline_modal">Decline</a>
<a class="hide pickup_batch_ready_modal" data-toggle="modal" data-target="#ready_modal">Serve</a>

{{-- Accept Modal --}}
<div id="accept_modal" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="accept_animate" style="max-width: 1000px;">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="row align-items-center m0 p0">
                <div class="col-sm-7 ordr_col">
                    <div id="accept_order" class="w100 m0 accept_order"></div>
                </div>

                <div class="col-sm-5 text-center" id="confirmation_div">
                    <div class="pos_fixed text-center">
                        <div class="col-sm-12 mb-3">
                            <h2><b>Ready in <span id="span_estimate">5</span> min.</b></h2>
                            <h2><b>Can you make it?</b></h2>
                            <div class="fs-sm fw-medium text-muted">We'll send this update to the customer</div>
                        </div>

                        <div class="col-sm-12 mb-2" data-duration="10">
                            <input type="hidden" id="accept_order_id" value="0" />
                            <input type="hidden" id="estimation_time" value="0" />
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
                            @foreach([0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60] as $mins)
                            <div class="col-sm-4">
                                <a class="btn btn-info estbox" data-mins="{{ $mins }}">
                                    {{ $mins == 0 ? 'Just Now' : $mins }}
                                    @if($mins > 0)
                                    <br>
                                    <small>Mins</small>
                                    @endif
                                </a>
                            </div>
                            @endforeach

                            <div class="col-sm-8">
                                <a class="btn btn-danger custom_estimation">
                                    Custom
                                    <br>
                                    <small>Mins</small>
                                </a>
                            </div>
                            <div class="col-sm-12 hide">
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
            </div>
        </div>
    </div>
</div>

{{-- Reschedule Modal --}}
<div id="reschedule_modal" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="accept_animate" style="max-width: 1000px;">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="row align-items-center m0 p0">
                <div class="col-sm-7 ordr_col">
                    <div id="reschedule_order" class="w100 m0 reschedule_order"></div>
                </div>

                <div class="col-sm-5 text-center" id="re_sch_confirmation_div">
                    <div class="pos_fixed text-center">
                        <div class="col-sm-12 mb-3">
                            <h2><b>Ready in <span id="re_sch_span_estimate">5</span> min.</b></h2>
                            <h2><b>Can you make it?</b></h2>
                            <div class="fs-sm fw-medium text-muted">We'll send this update to the customer</div>
                        </div>

                        <div class="col-sm-12 mb-2" data-duration="10">
                            <input type="hidden" id="re_sch_accept_order_id" value="0" />
                            <input type="hidden" id="re_sch_estimation_time" value="0" />
                            <a class="btn btn-primary w-100" onclick="reschedule_outgoing();">Yes, let's prepare it!</a>
                        </div>

                        <div class="col-sm-12 mb-2">
                            <a class="btn btn-outline-primary re_sch_change_estimation w-100">No, change estimate.</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5 text-center hide" id="re_sch_estimation_div">
                    <div class="pos_fixed text-center">
                        <div class="col-sm-12 row m-0">
                            @foreach([0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60] as $mins)
                            <div class="col-sm-4">
                                <a class="btn btn-info re_sch_estbox" data-mins="{{ $mins }}">
                                    {{ $mins == 0 ? 'Just Now' : $mins }}
                                    @if($mins > 0)
                                    <br>
                                    <small>Mins</small>
                                    @endif
                                </a>
                            </div>
                            @endforeach

                            <div class="col-sm-8">
                                <a class="btn btn-danger re_sch_custom_estimation">
                                    Custom
                                    <br>
                                    <small>Mins</small>
                                </a>
                            </div>
                            <div class="col-sm-12 hide">
                                <a class="btn btn-dark re_sch_back_to_confirm">OK</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5 text-center hide" id="re_sch_custom_div">
                    <div class="pos_fixed text-center">
                        <div class="col-sm-12 mb-3">
                            <h2><b>Custom Estimated Time</b></h2>
                            <div class="fs-sm fw-medium text-muted">Order can be ready in</div>
                        </div>

                        <div class="col-sm-12 mb-3">
                            <select id="re_sch_custom_time" class="form-control">
                                <option value="">Select Time</option>
                                @for ($i = 1; $i <= 60; $i++)
                                <option value="{{ $i }}">{{ sprintf('%02d', $i) }} Mins</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-sm-12">
                            <a class="btn btn-dark re_sch_back_to_estimation">OK</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Decline Modal --}}
<div id="decline_modal" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="decline_animate" style="max-width: 1000px;">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="row align-items-center m0 p0">
                <div class="col-sm-7 ordr_col">
                    <div id="decline_order" class="w100 m0"></div>
                </div>

                <div class="col-sm-5 text-center">
                    <div class="pos_fixed text-center">
                        <h2><b>Decline selected order(s)</b></h2>
                        <h2><b>Are you sure?</b></h2>
                        <textarea class="form-control" id="decline_reason" rows="3" placeholder="Reason to Decline"></textarea>
                        <input type="hidden" id="decline_order_id" value="0" />
                        <div class="mt-3">
                            <a class="btn btn-danger decline_submit_btn w-100">Yes, let's do it!</a>
                        </div>
                        <div class="mt-2">
                            <a class="btn btn-primary cancel_decline_order w-100">No</a>
                        </div>
                        <div class="mt-2 decline_msg_success">
                            <p class="text text-danger">Order Declined Successfully</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Schedule Modal --}}
<div id="schedule_modal" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="schedule_animate" style="max-width: 1000px;">
        <div class="modal-content ordr_col">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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
                        <div class="col-sm-12 row m-0 p-0">
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

{{-- Ready Modal --}}
<div id="ready_modal" class="modal fade" data-backdrop="true">
    <div class="modal-dialog" id="ready_animate" style="max-width: 1000px;">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="row align-items-center m0 p0">
                <div class="col-sm-7 ordr_col">
                    <div id="ready_order_details" class="w100 m0"></div>
                </div>

                <div class="col-sm-5 text-center">
                    <div class="pos_fixed text-center">
                        <h2><b>Serve selected order(s)</b></h2>
                        <h2><b>Are you sure?</b></h2>
                        <input type="hidden" id="ready_order_id" value="0" />
                        <div class="mt-3">
                            <a class="btn btn-primary" id="pin_submit" class="w-100">Yes, let's serve it!</a>
                        </div>
                        <div class="mt-2 serve_msg_success">
                            <p class="text text-success">Order Served Successfully</p>
                        </div>
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
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="row m-0">
                <div id="full_screen_order" class="col-sm-12"></div>
            </div>
        </div>
    </div>
</div>
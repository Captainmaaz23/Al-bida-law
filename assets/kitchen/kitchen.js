jQuery(document).ready(function (e) {
    $('#page-container').removeClass('sidebar-mini').addClass('sidebar-mini');
    call_events();
});

function call_events() {
    $('[data-toggle="tooltip"]').tooltip();

    if ($('.order_fullscreen').length > 0) {
        $('.order_fullscreen').off();
        $('.order_fullscreen').click(function (e) {
            var order_id = $(this).parent().parent().parent().data('lastid');
            
            var div_html = $("#" + order_id).html();
            $("#full_screen_order").html(div_html);
            $("#full_screen_order").find(".nomodal").remove();
            $("#full_screen_order").find(".inmodal").removeClass('hide');
        });
    }

    if ($('.move_btn').length > 0) {
        $('.move_btn').off();
        $('.move_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            move_to_outgoing(order_id);
        });
    }
    
    accept_events();
    decline_events();
    schedule_events();
    ready_events();
    reschedule_events();
    
    call_timer();
    recount();
    select_all_events();
}

function accept_events(){
    if ($('.accept_btn').length > 0) {
        $('.accept_btn').off();
        $('.accept_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            var order_time = $(this).parent().parent().parent().parent().find('.pickup_timer').data('duration');
            $("#accept_order_id").val(order_id);
            $("#span_estimate").html(order_time);
            $("#estimation_time").val(order_time);
            $("#confirmation_div").removeClass('hide');
            $("#estimation_div").removeClass('hide').addClass('hide');
            $("#custom_div").removeClass('hide').addClass('hide');
            
            var div_html = $("#" + order_id).html();
            $("#accept_order").html(div_html);
            $("#accept_order").find(".nomodal").remove();
            $("#accept_order").find(".inmodal").removeClass('hide');
        });
    }
    
    if ($('.change_estimation').length > 0) {
        $('.change_estimation').off();
        $('.change_estimation').click(function (e) {
            $("#estimation_div").removeClass('hide');
            $("#confirmation_div").removeClass('hide').addClass('hide');
            $("#custom_div").removeClass('hide').addClass('hide');
        });
    }

    if ($('.estbox').length > 0) {
        $('.estbox').off();
        $('.estbox').each(function (index, element) {
            $(this).click(function (e) {
                var obj = $(this);
                var minutes = $(this).data('mins');
                $("#span_estimate").html(minutes);
                $("#estimation_time").val(minutes);
                $('.estbox').each(function (index, element) {
                    $(this).removeClass('btn-success').addClass('btn-info');
                });
                obj.removeClass('btn-info').addClass('btn-success');
                $('.back_to_confirm').trigger('click');
            });
        });
    }

    if ($('.back_to_confirm').length > 0) {
        $('.back_to_confirm').off();
        $('.back_to_confirm').click(function (e) {
            $("#estimation_div").removeClass('hide').addClass('hide');
            $("#confirmation_div").removeClass('hide');
            $("#custom_div").removeClass('hide').addClass('hide');
        });
    }

    if ($('.custom_estimation').length > 0) {
        $('.custom_estimation').off();
        $('.custom_estimation').click(function (e) {
            $("#estimation_div").removeClass('hide').addClass('hide');
            $("#confirmation_div").removeClass('hide').addClass('hide');
            $("#custom_div").removeClass('hide');
        });
    }

    if ($('#custom_time').length > 0) {
        $('#custom_time').off();
        $('#custom_time').change(function (e) {
            var minutes = $(this).val();
            if (minutes != '') {
                $("#span_estimate").html(minutes);
                $("#estimation_time").val(minutes);
                $('.estbox').each(function (index, element) {
                    $(this).removeClass('btn-success').addClass('btn-info');
                });
            }
        });
    }

    if ($('.back_to_estimation').length > 0) {
        $('.back_to_estimation').off();
        $('.back_to_estimation').click(function (e) {
            $("#estimation_div").removeClass('hide').addClass('hide');
            $("#confirmation_div").removeClass('hide');
            $("#custom_div").removeClass('hide').addClass('hide');
        });
    }
}

function decline_events(){
    if ($('.decline_btn').length > 0) {
        $('.decline_btn').off();
        $('.decline_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            $("#decline_order_id").val(order_id);
            
            var div_html = $("#" + order_id).html();
            $("#decline_order").html(div_html);
            $("#decline_order").find(".nomodal").remove();
            $("#decline_order").find(".inmodal").removeClass('hide');
            $('.decline_msg_success').hide();
            $('#decline_reason').val('');
        });
    }
    
    if ($('.decline_submit_btn').length > 0) {
        $('.decline_submit_btn').off();
        $('.decline_submit_btn').click(function (e) {
            decline_Order();
        });
    }

    if ($('.cancel_decline_order').length > 0) {
        $('.cancel_decline_order').off();
        $('.cancel_decline_order').click(function (e) {
            $('#decline_modal').modal('toggle');
        });
    }
}

function schedule_events(){
    if ($('.schedule_btn').length > 0) {
        $('.schedule_btn').off();
        $('.schedule_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            var order_time = $(this).parent().parent().parent().parent().find('.pickup_timer').data('duration');
            $("#schedule_order_id").val(order_id);
            $("#span_schedule_estimate").html(order_time);
            $("#schedule_estimation_time").val(order_time);
            $("#schedule_confirmation_div").removeClass('hide');
            $("#schedule_estimation_div").removeClass('hide').addClass('hide');
            $("#schedule_custom_div").removeClass('hide').addClass('hide');
            
            var div_html = $("#" + order_id).html();
            $("#schedule_order").html(div_html);
            $("#schedule_order").find(".nomodal").remove();
            $("#schedule_order").find(".inmodal").removeClass('hide');
        });
    }  

    if ($('.change_schedule_estimation').length > 0) {
        $('.change_schedule_estimation').off();
        $('.change_schedule_estimation').click(function (e) {
            $("#schedule_estimation_div").removeClass('hide');
            $("#schedule_confirmation_div").removeClass('hide').addClass('hide');
            $("#schedule_custom_div").removeClass('hide').addClass('hide');
        });
    }

    if ($('.schedule_estbox').length > 0) {
        $('.schedule_estbox').off();
        $('.schedule_estbox').each(function (index, element) {
            $(this).click(function (e) {
                var obj = $(this);
                var minutes = $(this).data('mins');
                $("#span_schedule_estimate").html(minutes);
                $("#schedule_estimation_time").val(minutes);
                $('.schedule_estbox').each(function (index, element) {
                    $(this).removeClass('btn-success').addClass('btn-info');
                });
                obj.removeClass('btn-info').addClass('btn-success');
            });
        });
    }

    if ($('.back_to_schedule_confirm').length > 0) {
        $('.back_to_schedule_confirm').off();
        $('.back_to_schedule_confirm').click(function (e) {
            $("#schedule_estimation_div").removeClass('hide').addClass('hide');
            $("#schedule_confirmation_div").removeClass('hide');
            $("#schedule_custom_div").removeClass('hide').addClass('hide');
        });
    }

    if ($('.custom_schedule_estimation').length > 0) {
        $('.custom_schedule_estimation').off();
        $('.custom_schedule_estimation').click(function (e) {
            $("#schedule_estimation_div").removeClass('hide').addClass('hide');
            $("#schedule_confirmation_div").removeClass('hide').addClass('hide');
            $("#schedule_custom_div").removeClass('hide');
        });
    }


    if ($('#custom_schedule_time').length > 0) {
        $('#custom_schedule_time').off();
        $('#custom_schedule_time').change(function (e) {
            var minutes = $(this).val();
            if (minutes != '') {
                $("#span_schedule_estimate").html(minutes);
                $("#schedule_estimation_time").val(minutes);
                $('.estbox').each(function (index, element) {
                    $(this).removeClass('btn-success').addClass('btn-info');
                });
            }
        });
    }

    if ($('.back_to_schedule_estimation').length > 0) {
        $('.back_to_schedule_estimation').off();
        $('.back_to_schedule_estimation').click(function (e) {
            $("#schedule_estimation_div").removeClass('hide').addClass('hide');
            $("#schedule_confirmation_div").removeClass('hide');
            $("#schedule_custom_div").removeClass('hide').addClass('hide');
        });
    }
}

function reschedule_events(){  
    if ($('.reschedule_btn').length > 0) {
        $('.reschedule_btn').off();
        $('.reschedule_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            var order_time = $(this).parent().parent().parent().parent().find('.pickup_timer').data('duration');
            $("#re_sch_accept_order_id").val(order_id);
            $("#re_sch_span_estimate").html(order_time);
            $("#re_sch_estimation_time").val(order_time);
            $("#re_sch_confirmation_div").removeClass('hide');
            $("#re_sch_estimation_div").removeClass('hide').addClass('hide');
            $("#re_sch_custom_div").removeClass('hide').addClass('hide');
            
            var div_html = $("#" + order_id).html();
            $("#reschedule_order").html(div_html);
            $("#reschedule_order").find(".nomodal").remove();
            $("#reschedule_order").find(".inmodal").removeClass('hide');
        });
    } 

    if ($('.re_sch_change_estimation').length > 0) {
        $('.re_sch_change_estimation').off();
        $('.re_sch_change_estimation').click(function (e) {
            $("#re_sch_estimation_div").removeClass('hide');
            $("#re_sch_confirmation_div").removeClass('hide').addClass('hide');
            $("#re_sch_custom_div").removeClass('hide').addClass('hide');
        });
    }

    if ($('.re_sch_estbox').length > 0) {
        $('.re_sch_estbox').off();
        $('.re_sch_estbox').each(function (index, element) {
            $(this).click(function (e) {
                var obj = $(this);
                var minutes = $(this).data('mins');
                $("#re_sch_span_estimate").html(minutes);
                $("#re_sch_estimation_time").val(minutes);
                $('.re_sch_estbox').each(function (index, element) {
                    $(this).removeClass('btn-success').addClass('btn-info');
                });
                obj.removeClass('btn-info').addClass('btn-success');
                $('.re_sch_back_to_confirm').trigger('click');
            });
        });
    }

    if ($('.re_sch_back_to_confirm').length > 0) {
        $('.re_sch_back_to_confirm').off();
        $('.re_sch_back_to_confirm').click(function (e) {
            $("#re_sch_estimation_div").removeClass('hide').addClass('hide');
            $("#re_sch_confirmation_div").removeClass('hide');
            $("#re_sch_custom_div").removeClass('hide').addClass('hide');
        });
    }

    if ($('.re_sch_custom_estimation').length > 0) {
        $('.re_sch_custom_estimation').off();
        $('.re_sch_custom_estimation').click(function (e) {
            $("#re_sch_estimation_div").removeClass('hide').addClass('hide');
            $("#re_sch_confirmation_div").removeClass('hide').addClass('hide');
            $("#re_sch_custom_div").removeClass('hide');
        });
    }

    if ($('#re_sch_custom_time').length > 0) {
        $('#re_sch_custom_time').off();
        $('#re_sch_custom_time').change(function (e) {
            var minutes = $(this).val();
            if (minutes != '') {
                $("#re_sch_span_estimate").html(minutes);
                $("#re_sch_estimation_time").val(minutes);
                $('.re_sch_estbox').each(function (index, element) {
                    $(this).removeClass('btn-success').addClass('btn-info');
                });
            }
        });
    }

    if ($('.re_sch_back_to_estimation').length > 0) {
        $('.re_sch_back_to_estimation').off();
        $('.re_sch_back_to_estimation').click(function (e) {
            $("#re_sch_estimation_div").removeClass('hide').addClass('hide');
            $("#re_sch_confirmation_div").removeClass('hide');
            $("#re_sch_custom_div").removeClass('hide').addClass('hide');
        });
    }
}

function ready_events(){
    if ($('.ready_btn').length > 0) {
        $('.ready_btn').off();
        $('.ready_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            ready(order_id);
        });
    }

    if ($('.pickup_btn').length > 0) {
        $('.pickup_btn').off();
        $('.pickup_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            $('.serve_msg_success').hide();
            ready_order_details(order_id);
        });
    }

    if ($('#pin_submit').length > 0) {
        $('#pin_submit').off();
        $('#pin_submit').click(function (e) {
            var order_id = $("#ready_order_id").val();
            check_pin(order_id);
        });
    }
}

function call_timer() {
    $('.order_timer').each(function (index, element) {
        var called = parseInt($(this).data('called'));
        if (called == 0) {
            $(this).attr('data-called', 1);
            var order_duration = parseInt($(this).data('duration'));
            var timer_id = parseInt($(this).data('id'));
            //console.log(timer_id + '=>' + order_duration);
            $(this).timer({duration: order_duration, unit: 'm'});
            $(this).removeClass('order_timer');
        }
    });
}

/*function update_timer() {
 $('.order_timer').each(function(index, element) {
 var duration = $(this).data('duration');
 duration--;
 var timer_id = $(this).attr('id');
 $('#timer-'+timer_id).timer({duration: duration, unit: 'm'});
 });
 }*/

function recount() {
    $('#incoming_orders').each(function () {
        $("#incoming_count").html(' [' + $(this).find('.order_no').length + ']');
    });

    $('#outgoing_orders').each(function () {
        $("#outgoing_count").html(' [' + $(this).find('.order_no').length + ']');
    });

    $('#scheduled_orders').each(function () {
        $("#scheduled_count").html(' [' + $(this).find('.order_no').length + ']');
    });

    $('#ready_orders').each(function () {
        //var order_count = $(this).find('.order_no').length;
        $("#ready_count").html(' [' + $(this).find('.order_no').length + ']');
    });
}

function bindCheckboxes(sectionId, selectAllId) {
    const section = `#${sectionId}`;
    const selectAll = `#${selectAllId}`;

    $(selectAll).on('change', function () {
        const isChecked = $(this).prop('checked');
        $(`${section} .order-checkbox`).prop('checked', isChecked).trigger('change');
    });

    $(`${section} .order-checkbox`).on('change', function () {
        const allChecked = $(`${section} .order-checkbox`).length === $(`${section} .order-checkbox:checked`).length;
        $(selectAll).prop('checked', allChecked);
    });
}

function select_all_events(){
    bindCheckboxes('incoming_orders', 'select_all_incoming');
    bindCheckboxes('outgoing_orders', 'select_all_outgoing');
    bindCheckboxes('scheduled_orders', 'select_all_scheduled');
    bindCheckboxes('ready_orders', 'select_all_ready');

    if ($('.accept_btn_batch_in').length > 0) {
        $('.accept_btn_batch_in').off();
        $('.accept_btn_batch_in').click(function (e) {
            accept_btn_batch_in();
        });
    }

    if ($('.decline_btn_batch_in').length > 0) {
        $('.decline_btn_batch_in').off();
        $('.decline_btn_batch_in').click(function (e) {
            decline_btn_batch_in();
        });
    }

    if ($('.ready_btn_batch_out').length > 0) {
        $('.ready_btn_batch_out').off();
        $('.ready_btn_batch_out').click(function (e) {
            ready_btn_batch_out();
        });
    }

    if ($('.decline_btn_batch_out').length > 0) {
        $('.decline_btn_batch_out').off();
        $('.decline_btn_batch_out').click(function (e) {
            decline_btn_batch_out();
        });
    }

    if ($('.pickup_btn_batch_ready').length > 0) {
        $('.pickup_btn_batch_ready').off();
        $('.pickup_btn_batch_ready').click(function (e) {
            pickup_btn_batch_ready();
        });
    }
}

function accept_btn_batch_in(){
    const selected_order_ids = $('#incoming_orders .order-checkbox:checked').map(function () {
            return $(this).val();
    }).get();

    if (selected_order_ids.length === 0) {
        alert('No orders selected!');
        return;
    }
    $('.accept_batch_in_modal').trigger('click');
    const order_ids = selected_order_ids.join(',');
    var order_time = 10;
    $("#accept_order_id").val(order_ids);
    $("#span_estimate").html(order_time);
    $("#estimation_time").val(order_time);
    $("#confirmation_div").removeClass('hide');
    $("#estimation_div").removeClass('hide').addClass('hide');
    $("#custom_div").removeClass('hide').addClass('hide');
    
    var div_html = '';
    selected_order_ids.forEach(function (order_id) {
        div_html+= $("#" + order_id).html();
    });
    $("#accept_order").html(div_html);
    
    $("#accept_order").find(".nomodal").remove();
    $("#accept_order").find(".inmodal").removeClass('hide');
}

function decline_btn_batch_in(){
    const selected_order_ids = $('#incoming_orders .order-checkbox:checked').map(function () {
            return $(this).val();
    }).get();

    if (selected_order_ids.length === 0) {
        alert('No orders selected!');
        return;
    }
    $('.decline_batch_in_modal').trigger('click');
    const order_ids = selected_order_ids.join(',');    
    $("#decline_order_id").val(order_ids);
    
    var div_html = '';
    selected_order_ids.forEach(function (order_id) {
        div_html+= $("#" + order_id).html();
    });
    $("#decline_order").html(div_html);
    
    $("#decline_order").find(".nomodal").remove();
    $("#decline_order").find(".inmodal").removeClass('hide');
    $('.decline_msg_success').hide();
    $('#decline_reason').val('');
}

function ready_btn_batch_out(){
    const selected_order_ids = $('#outgoing_orders .order-checkbox:checked').map(function () {
            return $(this).val();
    }).get();

    if (selected_order_ids.length === 0) {
        alert('No orders selected!');
        return;
    }
    
    const order_ids = selected_order_ids.join(',');
    ready(order_ids);
}

function decline_btn_batch_out(){
    const selected_order_ids = $('#outgoing_orders .order-checkbox:checked').map(function () {
            return $(this).val();
    }).get();

    if (selected_order_ids.length === 0) {
        alert('No orders selected!');
        return;
    }
    $('.decline_batch_out_modal').trigger('click');
    const order_ids = selected_order_ids.join(',');    
    $("#decline_order_id").val(order_ids);
    
    var div_html = '';
    selected_order_ids.forEach(function (order_id) {
        div_html+= $("#" + order_id).html();
    });
    $("#decline_order").html(div_html);
    
    $("#decline_order").find(".nomodal").remove();
    $("#decline_order").find(".inmodal").removeClass('hide');
    $('.decline_msg_success').hide();
    $('#decline_reason').val('');
}

function pickup_btn_batch_ready(){
    const selected_order_ids = $('#ready_orders .order-checkbox:checked').map(function () {
            return $(this).val();
    }).get();

    if (selected_order_ids.length === 0) {
        alert('No orders selected!');
        return;
    }
    $('.pickup_batch_ready_modal').trigger('click');
    $('.serve_msg_success').hide();
    const order_ids = selected_order_ids.join(',');    
    ready_order_details(order_ids);
}
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
            full_screen_order(order_id);
        });
    }

    if ($('.accept_btn').length > 0) {
        $('.accept_btn').off();
        $('.accept_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            var order_time = $(this).parent().parent().parent().parent().find('.pickup_timer').data('duration');
            $("#accept_order_id").val(order_id);
            $("#span_estimate").html(order_time);
            $("#estimation_time").val(order_time);
            $("#confirmation_div").removeClass('hide');
            accept(order_id);
        });
    }

    if ($('.schedule_btn').length > 0) {
        $('.schedule_btn').off();
        $('.schedule_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            var order_time = $(this).parent().parent().parent().parent().find('.pickup_timer').data('duration');
            $("#schedule_order_id").val(order_id);
            $("#span_schedule_estimate").html(order_time);
            $("#schedule_estimation_time").val(order_time);
            $("#schedule_confirmation_div").removeClass('hide');
            schedule(order_id);
        });
    }

    if ($('.ready_btn').length > 0) {
        $('.ready_btn').off();
        $('.ready_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            ready(order_id);
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



    if ($('.decline_btn').length > 0) {
        $('.decline_btn').off();
        $('.decline_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            $("#decline_order_id").val(order_id);
            decline(order_id);
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


    if ($('.move_btn').length > 0) {
        $('.move_btn').off();
        $('.move_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
            move_to_outgoing(order_id);
        });
    }


    if ($('.pickup_btn').length > 0) {
        $('.pickup_btn').off();
        $('.pickup_btn').click(function (e) {
            var order_id = $(this).parent().parent().parent().parent().data('lastid');
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

    call_timer();
    recount();
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
var csrfToken = null;
jQuery(document).ready(function (e) {
    csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    get_orders();
    //outgoing_orders(0);
    schedule_orders(0);
    ready_orders(0);
});

function playSound(url)
{
    const audio = new Audio(url);
    audio.play();
}

var div_html = "";
var id = 0;

function get_orders() {
    $('#get_orders_btn').hide();
    //orders();
    //setInterval(orders,1000);
    combine_Orders();
    setInterval(combine_Orders,1000);
}

function orders() {
    var order_ids = "0";
    if ($('.incoming_order').length > 0) {
        $('.incoming_order').each(function (index) {
            var val = $(this).data("lastid");
            if (order_ids == "") {
                order_ids = val;
            }
            else {
                order_ids = order_ids + "," + val;
            }
        });
    }

    var url = window.routes.get_incoming_orders;
    url = url.replace(':id', order_ids);
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data != "" || data !=null) {
                handle_incoming_orders(data);
                call_events();
            }
        }
    });



    var order_ids = "0";
    if ($('.outgoing_order').length > 0) {
        $('.outgoing_order').each(function (index) {
            var val = $(this).data("lastid");
            if (order_ids == "") {
                order_ids = val;
            }
            else {
                order_ids = order_ids + "," + val;
            }
        });
    }

    var url = window.routes.outgoing_orders;
    url = url.replace(':id', order_ids);
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data != "" || data !=null || data.length == 0) {
                handle_outgoing_orders(data);
                call_remove_orders();
                call_events();
            }
        }
    });
}

function combine_Orders() {
    var incoming_order_ids = "0";
    if ($('.incoming_order').length > 0) {
        $('.incoming_order').each(function (index) {
            var val = $(this).data("lastid");
            incoming_order_ids = incoming_order_ids + "," + val;
        });
    }
    var outgoing_order_ids = "0";
    if ($('.outgoing_order').length > 0) {
        $('.outgoing_order').each(function (index) {
            var val = $(this).data("lastid");
            outgoing_order_ids = outgoing_order_ids + "," + val;
        });
    }
    
    var url = window.routes.get_combined_orders;
    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: {
            "_token": csrfToken,
            incoming_order_ids: incoming_order_ids,
            outgoing_order_ids: outgoing_order_ids,
        },
        success: function (data) {
            if (data != "" || data !=null || data.length == 0) {
                handle_common_orders(data);
                call_events();
            }
        }
    });
}

function call_remove_orders() {
    if ($('.outgoing_order').length > 0) {
        $('.outgoing_order').each(function (index) {
            var outgoing = $(this).data("lastid");
            $('.incoming_order').each(function (index) {
                var incoming = $(this).data("lastid");
                if (outgoing == incoming)
                {
                    $(this).remove();
                }
            });

            $('.scheduled_order').each(function (index) {
                var scheduled = $(this).data("lastid");
                if (outgoing == scheduled)
                {
                    $(this).remove();
                }
            });
        });
    }
}

function decline_Order() {
    var order_ids = $("#decline_order_id").val();
    var decline_reason = $("#decline_reason").val();
    //var url = window.routes.decline_order;
    var url = window.routes.decline_order_batch;
    $.ajax({
        url: url,
        type: 'post',
        data: {
            "_token": csrfToken,
            id: order_ids,
            reason: decline_reason
        },
        success: function (data) {
            $('.decline_msg_success').show();
            setTimeout(function() {
                $('#decline_modal').modal('toggle');
                remove_order_html(order_ids);
            }, 400);
        }
    });
}

function remove_order_html(order_ids){
    if ($.isNumeric(order_ids)) {
        $("#" + order_ids).remove();
    } else {
        const idsArray = order_ids.split(',');
        idsArray.forEach(function(order_id) {
            $("#" + order_id).remove();
        }); 
    }
}

function insert_schedule() {
    var time = $("#schedule_estimation_time").val();
    var order_id = $("#schedule_order_id").val();
    var url = window.routes.order_scheduled;
    $.ajax({
        url: url,
        type: 'post',
        data: {
            "_token": csrfToken,
            id: order_id,
            time: time
        },
        success: function (data) {
            $("#" + order_id).remove();
            $('#schedule_modal').modal('hide');
            schedule_orders(order_id);
        }
    });
}

function schedule_orders(order_id) {
    var url = window.routes.scheduled_orders;
    url = url.replace(':id', order_id);
    $.ajax({
        url: url,
        type: 'get',
        success: function (data) {
            $("#scheduled_orders").append(data);
            call_events();
        }
    });
}

function insert_outgoing() {
    var time = $("#estimation_time").val();
    var order_ids = $("#accept_order_id").val();
    //var url = window.routes.order_preparing;
    var url = window.routes.order_preparing_batch;
    $.ajax({
        url: url,
        type: 'post',
        data: {
            "_token": csrfToken,
            id: order_ids,
            time: time
        },
        success: function (data) {
            $('#accept_modal').modal('hide');
            remove_order_html(order_ids);
            //outgoing_orders(order_id);
        }
    });
}

function move_to_outgoing(order_id) {
    var url = window.routes.move_to_outgoing_orders;
    $.ajax({
        url: url,
        type: 'post',
        data: {
            "_token": csrfToken,
            id: order_id
        },
        success: function (data) {
            $("#" + order_id).remove();
            outgoing_orders(order_id);
        }
    });
}

function outgoing_orders(order_id) {
    var url = window.routes.outgoing_orders;
    url = url.replace(':id', order_id);
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data != "" || data !=null || data.length == 0 || data.length == 0) {
                handle_outgoing_orders(data);
                //call_remove_orders();
                call_events();
            }
        }
    });
}

function reschedule_outgoing() {
    var time = $("#re_sch_estimation_time").val();
    var order_ids = $("#re_sch_accept_order_id").val();
    var url = window.routes.order_reschedule_batch;
    $.ajax({
        url: url,
        type: 'post',
        data: {
            "_token": csrfToken,
            id: order_ids,
            time: time
        },
        success: function (data) {
            $('#reschedule_modal').modal('hide');
            $("#" + order_ids).remove();
            outgoing_orders(order_ids);
        }
    });
}

function ready(order_ids) {
    var url = window.routes.order_ready_batch;
    $.ajax({
        url: url,
        type: 'post',
        data: {
            "_token": csrfToken,
            id: order_ids
        },
        success: function (data) {
            remove_order_html(order_ids);
            ready_orders(order_ids);
            call_events();
        }
    });

}

function ready_orders(order_ids) {
    var url = window.routes.ready_orders_batch;
    url = url.replace(':id', order_ids);
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data != "" || data !=null || data.length == 0) {
                handle_ready_orders(data);
                call_events();
            }
        }
    });
}

function ready_order_details(order_ids) {
    var url = window.routes.ready_order_details_batch;
    url = url.replace(':id', order_ids);
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data != "" || data !=null || data.length == 0) {
                handle_ready_details(data);
                $("#ready_order_id").val(order_ids);
                $("#ready_order_details").find(".nomodal").remove();
                $("#ready_order_details").find(".inmodal").removeClass('hide');
                call_events();
            }            
        }
    });
}

/*function check_pin(order_id) {
    var url = window.routes.check_pin;
    url = url.replace(":id", order_id);
    url = url.replace(":pin", 1234);
    $.ajax({
        url: url,
        type: 'get',
        success: function (data) {
            if (data == 1) {
                pickup_order(order_id);
                $('#ready_modal').modal('hide');
            }
            else {
                $("#messages").html("Incorrect PIN provided");
            }
            call_events();
        }
    });
}*/

function check_pin(order_ids) {
    pickup_order(order_ids);
}

function pickup_order(order_ids) {
    var url = window.routes.pickup_orders_batch;
    url = url.replace(':id', order_ids);
    $.ajax({
        url: url,
        type: 'get',
        success: function (data) {
            $('.serve_msg_success').show();
            setTimeout(function() {
                $('#ready_modal').modal('hide');
                remove_order_html(order_ids);
                call_events();
            }, 400);
        }
    });
}
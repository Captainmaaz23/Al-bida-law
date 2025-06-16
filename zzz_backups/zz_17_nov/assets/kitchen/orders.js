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
            incoming_order_ids = order_ids + "," + val;
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

function full_screen_order(order_id) {
    var div = $("#" + order_id);
    div_html = div.html();
    $("#full_screen_order").html(div_html);
    $("#full_screen_order").find(".nomodal").remove();
    $("#full_screen_order").find(".inmodal").removeClass('hide');
}

function accept(order_id) {
    var div = $("#" + order_id);
    div_html = div.html();
    $("#accept_order").html(div_html);
    $("#accept_order").find(".nomodal").remove();
    $("#accept_order").find(".inmodal").removeClass('hide');

}

function schedule(order_id) {
    var div = $("#" + order_id);
    div_html = div.html();
    $("#schedule_order").html(div_html);
    $("#schedule_order").find(".nomodal").remove();
    $("#schedule_order").find(".inmodal").removeClass('hide');

}

function ready(order_id) {
    var div = $("#" + order_id);
    div_html = div.html();
    var url = window.routes.order_ready;
    $.ajax({
        url: url,
        type: 'post',
        data: {
            "_token": csrfToken,
            id: order_id,
        },
        success: function (data) {
            $("#" + order_id).remove();
            ready_orders(order_id);
            call_events();
        }
    });

}

function decline(order_id) {
    var div = $("#" + order_id);
    div_html = div.html();
    $("#decline_order").html(div_html);
    $("#decline_order").find(".nomodal").remove();
    $("#decline_order").find(".inmodal").removeClass('hide');
}

function decline_Order() {
    var order_id = $("#decline_order_id").val();
    var decline_reason = $("#decline_reason").val();
    var url = window.routes.decline_order;
    $.ajax({
        url: url,
        type: 'post',
        data: {
            "_token": csrfToken,
            id: order_id,
            reason: decline_reason,
        },
        success: function (data) {
            $('#decline_modal').modal('toggle');
            $("#" + order_id).remove();
        }
    });
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
    var order_id = $("#accept_order_id").val();
    var url = window.routes.order_preparing;
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
            $('#accept_modal').modal('hide');
            outgoing_orders(order_id);
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

function ready_orders(order_id) {
    var url = window.routes.ready_orders;
    url = url.replace(':id', order_id);
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data != "" || data !=null || data.length == 0) {
                //$("#ready_orders").append(data);
                handle_ready_orders(data);
                call_events();
            }
        }
    });
}

function ready_order_details(order_id) {
    var url = window.routes.ready_order_details;
    url = url.replace(':id', order_id);
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data != "" || data !=null || data.length == 0) {
                //$("#ready_order_details").html(data);
                handle_ready_details(data);
                $("#ready_order_id").val(order_id);
                $("#ready_order_details").find(".nomodal").remove();
                $("#ready_order_details").find(".inmodal").removeClass('hide');
                call_events();
            }            
        }
    });
}

function check_pin(order_id) {
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
}

function pickup_order(order_id) {
    var url = window.routes.pickup_orders;
    url = url.replace(':id', order_id);
    $.ajax({
        url: url,
        type: 'get',
        success: function (data) {
            $("#" + order_id).remove();
            call_events();
        }
    });
}
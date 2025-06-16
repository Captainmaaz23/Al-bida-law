function handle_common_orders(response){
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0){
            var status = parseInt(order.status);
            switch(status){
                case 3:
                handle_common_order_details(order, 'col-sm-12 order_no incoming_order', 'incoming_orders', 'incoming');
                    break;
                case 6:
                handle_common_order_details(order, 'col-sm-12 order_no outgoing_order', 'outgoing_orders', 'outgoing');
                    break;
                case 5:
                handle_common_order_details(order, 'col-sm-12 order_no scheduled_order', 'scheduled_orders', 'schedule');
                    break;
                case 7:
                handle_ready_order_details(order);
                    break;
                default:
                    //alert(status);
                    break;
            }
        }
    };
}

function handle_common_order_details(order, order_class, target_div, order_type){
    if(parseInt(order.id) > 0 && $('#'+order.id).length == 0){
        let orderHtml = `
        <div class="${order_class}" id="${order.id}" data-lastid="${order.id}" data-lat="${order.lat}" data-lng="${order.lng}">
                <div class="row">`;
                    orderHtml += load_basic_info_user_section(order, 1);
                    //orderHtml += load_basic_info_section(order);
                    orderHtml += load_timer_section(order);
            orderHtml += `
                </div>`;
                //orderHtml += load_user_section(order);
                orderHtml += load_items_section(order);
                orderHtml += load_amounts_section_2(order);
                switch(order_type){
                    case 'incoming':
                    orderHtml += load_incoming_buttons(order);
                        break;
                    case 'outgoing':
                    orderHtml += load_outgoing_buttons(order);
                        break;
                    case 'schedule':
                    orderHtml += load_schedule_buttons(order);
                        break;
                    default:
                        break;
                }
                orderHtml += load_hr_section(order);
            orderHtml += `
        </div>`;

        $("#"+target_div).prepend(orderHtml);
    }
}

function handle_incoming_orders(response){
    var exists = 0;
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0 && $('#'+order.id).length == 0){
            exists = 1;
            handle_common_order_details(order, 'col-sm-12 order_no incoming_order', 'incoming_orders', 'incoming');
        }
    }; 
    if(exists){
        play_notification_sound();
    } 
}

function handle_outgoing_orders(response){
    var exists = 0;
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0 && $('#'+order.id).length == 0){
            exists = 1;
            handle_common_order_details(order, 'col-sm-12 order_no outgoing_order', 'outgoing_orders', 'outgoing');
        }
    };
    if(exists){
        play_notification_sound();
    }    
}

function handle_scheduled_orders(response){
    var exists = 0;
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0 && $('#'+order.id).length == 0){
            exists = 1;
            handle_common_order_details(order, 'col-sm-12 order_no scheduled_order', 'scheduled_orders', 'schedule');
        }
    };
    if(exists){
        play_notification_sound();
    }    
}

function handle_ready_order_details(order){
    if(parseInt(order.id) > 0 && $('#'+order.id).length == 0){
        let orderHtml = `
        <div class="col-sm-12 order_no m0 p0 ready_order" id="${order.id}" data-lastid="${order.id}" data-lat="${order.lat}" data-lng="${order.lng}">
                <div class="row">
                        <small>
                            <input type="checkbox" class="order-checkbox nomodal" value="${order.id}" /> 
                        </small><br>`;
                    orderHtml += load_basic_info_user_section(order, 0);
            orderHtml += `
                </div>`;
                orderHtml += load_amounts_section(order);
                orderHtml += load_ready_buttons(order);
                orderHtml += load_hr_section(order);
            orderHtml += `
        </div>`;

        $("#ready_orders").prepend(orderHtml);
    }
}

function handle_ready_orders(response){
    var exists = 0;
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0 && $('#'+order.id).length == 0){
            exists = 1;
            handle_ready_order_details(order);
        }
    };
    if(exists){
        play_notification_sound();
    }  
}

function handle_ready_details(response){
    var exists = 0;
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0){
            exists = 1;
            let orderHtml = `
            <div class="col-sm-12" data-lastid="${order.id}" data-lat="${order.lat}" data-lng="${order.lng}">
                    <div class="row">`;
                        orderHtml += load_basic_info_section(order);
                        orderHtml += load_timer_section(order);
                orderHtml += `
                    </div>`;
                    orderHtml += load_user_section(order);
                    orderHtml += load_items_section(order);
                    orderHtml += load_amounts_section_2(order);
                    orderHtml += load_hr_section(order);
                orderHtml += `
            </div>`;
            
            $("#ready_order_details").prepend(orderHtml);
        }
    };
    if(exists){
        play_notification_sound();
    }
}

function play_notification_sound(){
    //$('#play_audio').click();  
}

function load_basic_info_user_section(order, is_ready){
    var class_div = (is_ready == 1) ? 'col-sm-6' : 'col-sm-12';
    let html = `
        <div class="${class_div}">
            <div class="usy-dt"> 
                <a href="#"><img src="${order.user_image}" alt="User Image"></a>
                <div class="usy-name">
                    <h3>${order.order_no}</h3>
                    <span><small><i class="fa fa-table"></i><span class="phone_no"> ${order.table_name}</span></small></span><br />
                    <span><small><i class="fa fa-user"></i><span class="phone_no"> ${order.user_name}</span></small></span>
                </div>
            </div>
        </div>`;
    if(is_ready == 1) {
        html+= `
        <div class="col-sm-2 txt_right nomodal">
            <input type="checkbox" class="order-checkbox nomodal" value="${order.id}" />
            <a class="order_fullscreen" data-toggle="modal" data-target="#full_screen_modal" title="View Order Details">
                <i class="fa fa-expand"></i>
            </a>
        </div>`;
    }
    return html;
    //<span class="hide"><small><i class="fa fa-phone"></i><span class="phone_no"> ${order.user_phone}</span></small></span><br />
}

function load_basic_info_section(order){
    let html = `
        <div class="col-sm-6">
            <small>
                <input type="checkbox" class="order-checkbox nomodal" value="${order.id}" /> 
                Order # ${order.order_no}
            </small><br>
            <small>For ${order.table_name}</small>
        </div>
        <div class="col-sm-2 txt_right nomodal">
            <a class="order_fullscreen" data-toggle="modal" data-target="#full_screen_modal" title="View Order Details">
                <i class="fa fa-expand"></i>
            </a>
        </div>`;
    return html;
}

function load_timer_section(order){
    console.log(order);
    if(parseInt(order?.show_timer) > 0){
        let html = `
        <div class="col-sm-${order.timer_col_class} txt_right fl_right" style="${order.timer_col_style}">
            <div data-id="${order.id}" class="order_timer pickup_timer" data-duration="${order.timer_description}" data-called="0"></div>
        </div>`;
        return html;
    } else {
        let html = ``;
        return html;
    }
}

function load_user_section(order){
    let html = `
    <div class="row">
        <div class="col-sm-6">
            <div class="usy-dt"> 
                <a href="#"><img src="${order.user_image}" alt="User Image"></a>
                <div class="usy-name">
                    <h3>${order.user_name}</h3>
                    <span><small><i class="fa fa-phone"></i><span class="phone_no">${order.user_phone}</span></small></span>
                    <br />
                    <span><small><i class="fa fa-clock"></i><span class="phone_no">${order.created_date_time}</span></small></span>
                </div>
            </div>
        </div>
    </div>`;
    return html;
}

function load_items_section(order){
    let html = ``; 
    order.items?.forEach(item => {
        html += `
        <div class="row">
            <div class="col-sm-12">
                <span class="w15 fl_left"><b>${item.quantity}x</b></span>
                <span class="w85 fl_left"><b>${item.details.name}</b></span>`;
        
                item.addons?.forEach(addon => {
                    html += `
                        <span class="w85 fl_right or_addon">
                            <small>${addon.details.name}</small>
                        </span>`;
                });
                html += `
                <span class="w85 fl_right or_notes">
                    <small class="nomodal" title="${item.notes}">
                        ${item.notes.substring(0, 90)}...
                    </small>
                    <small class="inmodal hide">
                        ${item.notes}
                    </small>
                </span>
                <span class="w85 fl_right"><hr class="m0 p0"></span>
            </div>
        </div>`;
    });
    return html;
}

function load_amounts_section(order){
    const formatNumber = (num) => {
        return new Intl.NumberFormat('en-US', { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
    };

    let totalValue = formatNumber(order.total_value);
    let vatValue = formatNumber(order.vat_value);
    let serviceCharges = formatNumber(order.service_charges);
    let finalValue = formatNumber(order.final_value);
    let html = `
    <div class="row mt-2">
        <div class="col-sm-12">
            <table class="w100">
                <tr class="or_value_s">
                    <th class="text-left w50">Order Value:</th>
                    <td class="text-right w50">${totalValue}</td>
                </tr>
                <tr class="or_value_s">
                    <th class="text-left w50">VAT Value:</th>
                    <td class="text-right w50">${vatValue}</td>
                </tr>
                <tr class="or_value_s">
                    <th class="text-left w50">Service Charges:</th>
                    <td class="text-right w50">${serviceCharges}</td>
                </tr>
                <tr class="or_value">
                    <th class="text-left w50">Total Value:</th>
                    <td class="text-right w50">${finalValue}</td>
                </tr>
            </table>
        </div>
    </div>`;
    return html;    
}

function load_amounts_section_2(order){
    const formatNumber = (num) => {
        return new Intl.NumberFormat('en-US', { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
    };

    let totalValue = formatNumber(order.total_value);
    let vatValue = formatNumber(order.vat_value);
    let serviceCharges = formatNumber(order.service_charges);
    let finalValue = formatNumber(order.final_value);
    let html = `
    <div class="row mt-2">
        <div class="col-sm-7">
            <div class="usy-dt">
                <div class="usy-name">
                    <span><small><i class="fa fa-clock"></i><span class="phone_no"> ${order.created_date_time}</span></small></span>
                </div>
            </div>
        </div>
        <div class="col-sm-5 text-right m0 p0 fl_right">
            <table class="w100 text-right m0 p0 fl_right">
                <tr class="or_value_s">
                    <th class="text-left m0 p0 w50">Order Value:</th>
                    <td class="text-right m0 p0 w50">${totalValue}</td>
                </tr>
                <tr class="or_value_s">
                    <th class="text-left m0 p0 w50">VAT Value:</th>
                    <td class="text-right m0 p0 w50">${vatValue}</td>
                </tr>
                <tr class="or_value_s">
                    <th class="text-left m0 p0 w50">Service Charges:</th>
                    <td class="text-right m0 p0 w50">${serviceCharges}</td>
                </tr>
                <tr class="or_value">
                    <th class="text-left m0 p0 w50">Total Value:</th>
                    <td class="text-right m0 p0 w50">${finalValue}</td>
                </tr>
            </table>
        </div>
    </div>`;
    return html;    
}

function load_incoming_buttons(order){
    let html = `
    <div class="row nomodal">
        <div class="col-sm-12">
            <div class="w50 fl_left">
                <a class="btn btn-outline-danger decline_btn" data-toggle="modal" data-target="#decline_modal">Decline</a>
            </div>
            <div class="w50 fl_left">
                <a class="btn btn-primary accept_btn" data-toggle="modal" data-target="#accept_modal">Accept</a>
            </div>
        </div>
    </div>`;
    return html;
}

function load_outgoing_buttons(order){
    let html = `
    <div class="row nomodal">
        <div class="col-sm-12">
            <div class="w30 fl_left">
                <a class="btn btn-outline-danger decline_btn" data-toggle="modal" data-target="#decline_modal">Decline</a>
            </div>
            <div class="w30 fl_left">
                <a class="btn btn-outline-warning reschedule_btn" data-toggle="modal" data-target="#reschedule_modal">Reschedule</a>
            </div>
            <div class="w40 fl_left">
                <a class="btn btn-primary ready_btn" style="display: block;width: 100%;">Ready to Serve</a>
            </div>
        </div>
    </div>`;
    return html;
}

function load_schedule_buttons(order){
    let html = `
    <div class="row nomodal">
        <div class="col-sm-12">
            <div class="w85 txt_center m_auto">
                <a class="btn btn-primary move_btn" style="display: block;width: 100%;">Move to Outgoing</a>
            </div>
        </div>
    </div>`;
    return html;
}

function load_ready_to_serve_buttons(order){
    let html = `
    <div class="row nomodal">
        <div class="col-sm-12">
            <div class="w50 fl_left">
                <a class="btn btn-outline-danger decline_btn" data-toggle="modal" data-target="#decline_modal">Decline</a>
            </div>
            <div class="w50 fl_left">
                <a class="btn btn-primary accept_btn" data-toggle="modal" data-target="#accept_modal">Accept</a>
            </div>
        </div>
    </div>`;
    return html;
}

function load_ready_buttons(order){
    let html = `
    <div class="row nomodal">
        <div class="col-sm-12 txt_right">
            <div class="w50 txt_right">
                <a class="btn btn-primary pickup_btn" data-toggle="modal" data-target="#ready_modal" ui-toggle-class="bounce" ui-target="#ready_animate" >Serve</a>
            </div>
        </div>
    </div>`;
    return html;
}

function load_hr_section(order){
    let html = `
    <div class="order_divider">&nbsp;</div>`;
    return html;
}
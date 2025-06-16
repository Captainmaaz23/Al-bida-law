function handle_common_orders(response){
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0){
            var status = parseInt(order.status);
            switch(status){
                case 3:
                handle_order_details(order, 'col-sm-12 order_no incoming_order', 'incoming_orders', 'incoming');
                    break;
                case 6:
                handle_order_details(order, 'col-sm-12 order_no outgoing_order', 'outgoing_orders', 'outgoing');
                    break;
                case 5:
                handle_order_details(order, 'col-sm-12 order_no scheduled_order', 'scheduled_orders', 'schedule');
                    break;
                default:
                    alert(status);
                    break;
            }
        }
    }; 
    /*if(exists){
        play_notification_sound();
    } */
}

function handle_order_details(order, order_class, target_div, order_type){
    if(parseInt(order.id) > 0 && $('#'+order.id).length == 0){
        let orderHtml = `
        <div class="${order_class}" id="${order.id}" data-lastid="${order.id}" data-lat="${order.lat}" data-lng="${order.lng}">
                <div class="row">`;
                    orderHtml += load_basic_info_section(order);
                    orderHtml += load_timer_section(order);
            orderHtml += `
                </div>`;
                orderHtml += load_user_section(order);
                orderHtml += load_items_section(order);
                orderHtml += load_amounts_section(order);
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
    //let allOrdersHtml = '';
    var exists = 0;
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0 && $('#'+order.id).length == 0){
            exists = 1;
            // Start creating HTML structure
            let orderHtml = `
            <div class="col-sm-12 order_no incoming_order" id="${order.id}" data-lastid="${order.id}" data-lat="${order.lat}" data-lng="${order.lng}">
                    <div class="row">`;
                        orderHtml += load_basic_info_section(order);
                        orderHtml += load_timer_section(order);
                orderHtml += `
                    </div>`;
                    orderHtml += load_user_section(order);
                    orderHtml += load_items_section(order);
                    orderHtml += load_amounts_section(order);
                    orderHtml += load_incoming_buttons(order);
                    orderHtml += load_hr_section(order);
                orderHtml += `
            </div>`;
            
            $("#incoming_orders").prepend(orderHtml);
            //allOrdersHtml += orderHtml;
        }
    };   

    //$("#incoming_orders").append(allOrdersHtml); 
    if(exists){
        play_notification_sound();
    } 
}

function handle_outgoing_orders(response){
    //let allOrdersHtml = '';
    var exists = 0;
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0 && $('#'+order.id).length == 0){
            exists = 1;
            // Start creating HTML structure
            let orderHtml = `
            <div class="col-sm-12 order_no outgoing_order" id="${order.id}" data-lastid="${order.id}" data-lat="${order.lat}" data-lng="${order.lng}">
                    <div class="row">`;
                        orderHtml += load_basic_info_section(order);
                        orderHtml += load_timer_section(order);
                orderHtml += `
                    </div>`;
                    orderHtml += load_user_section(order);
                    orderHtml += load_items_section(order);
                    orderHtml += load_amounts_section(order);
                    orderHtml += load_outgoing_buttons(order);
                    orderHtml += load_hr_section(order);
                orderHtml += `
            </div>`;
            
            $("#outgoing_orders").prepend(orderHtml);
            //allOrdersHtml += orderHtml;
        }
    }; 

    //$("#outgoing_orders").append(allOrdersHtml);
    if(exists){
        play_notification_sound();
    }    
}

function handle_scheduled_orders(response){
    //let allOrdersHtml = '';
    var exists = 0;
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0 && $('#'+order.id).length == 0){
            exists = 1;
            // Start creating HTML structure
            let orderHtml = `
            <div class="col-sm-12 order_no scheduled_order" id="${order.id}" data-lastid="${order.id}" data-lat="${order.lat}" data-lng="${order.lng}">
                    <div class="row">`;
                        orderHtml += load_basic_info_section(order);
                        orderHtml += load_timer_section(order);
                orderHtml += `
                    </div>`;
                    orderHtml += load_user_section(order);
                    orderHtml += load_items_section(order);
                    orderHtml += load_amounts_section(order);
                    orderHtml += load_schedule_buttons(order);
                    orderHtml += load_hr_section(order);
                orderHtml += `
            </div>`;
            
            $("#scheduled_orders").prepend(orderHtml);
            //allOrdersHtml += orderHtml;
        }
    };

    //$("#scheduled_orders").append(allOrdersHtml);
    if(exists){
        play_notification_sound();
    }    
}

function handle_ready_orders(response){
    //let allOrdersHtml = '';
    var exists = 0;
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0 && $('#'+order.id).length == 0){
            exists = 1;
            // Start creating HTML structure
            let orderHtml = `
            <div class="col-sm-12 order_no ready_order" id="${order.id}" data-lastid="${order.id}" data-lat="${order.lat}" data-lng="${order.lng}">
                    <div class="row">
                        <div class="col-sm-12">
                            <small>Order # ${order.order_no}</small><br>
                            <small>For ${order.table_name}</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
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
                    orderHtml += load_amounts_section(order);
                    orderHtml += load_ready_buttons(order);
                    orderHtml += load_hr_section(order);
                orderHtml += `
            </div>`;
            
            $("#ready_orders").prepend(orderHtml);
            //allOrdersHtml += orderHtml;
        }
    };   

    //$("#ready_orders").append(allOrdersHtml);
    if(exists){
        play_notification_sound();
    }  
}

function handle_ready_details(response){
    //let allOrdersHtml = '';
    var exists = 0;
    for (var i = 0; i < response.length; i++) {
        let order = response[i];
        if(parseInt(order.id) > 0){
            exists = 1;
            // Start creating HTML structure
            let orderHtml = `
            <div class="col-sm-12" data-lastid="${order.id}" data-lat="${order.lat}" data-lng="${order.lng}">
                    <div class="row">`;
                        orderHtml += load_basic_info_section(order);
                        orderHtml += load_timer_section(order);
                orderHtml += `
                    </div>`;
                    orderHtml += load_user_section(order);
                    orderHtml += load_items_section(order);
                    orderHtml += load_amounts_section(order);
                    orderHtml += load_outgoing_buttons(order);
                    orderHtml += load_hr_section(order);
                orderHtml += `
            </div>`;
            
            $("#ready_order_details").prepend(orderHtml);
            //allOrdersHtml += orderHtml;
        }
    };

    //$("#ready_order_details").append(allOrdersHtml);
    if(exists){
        play_notification_sound();
    }
}

function play_notification_sound(){
    $('#play_audio').click();  
}

function load_basic_info_section(order){
    let html = `
        <div class="col-sm-6">
            <small>Order # ${order.order_no}</small><br>
            <small>For ${order.table_name}</small>
        </div>
        <div class="col-sm-2 txt_right nomodal">
            <a class="order_fullscreen" data-toggle="modal" data-target="#full_screen_modal" title="View Order Details">
                <i class="fa fa-expand-arrows-alt"></i>
            </a>
        </div>`;
    return html
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
    return html
}

function load_items_section(order){
    let html = ``; 
    // Loop through each item in the order
    order.items?.forEach(item => {
        html += `
        <div class="row">
            <div class="col-sm-12">
                <span class="w12 fl_left"><b>${item.quantity}x</b></span>
                <span class="w88 fl_left"><b>${item.details.name}</b></span>`;

                // Loop through each addon in the order
                item.addons?.forEach(addon => {
                    html += `
                        <span class="w88 fl_right or_addon">
                            <small>${addon.details.name}</small>
                        </span>`;
                });
                html += `<span class="w88 fl_right or_notes">
                    <small class="nomodal" title="${item.notes}">
                        ${item.notes.substring(0, 20)}...
                    </small>
                </span>
                <span class="w88 fl_right"><hr></span>
            </div>
        </div>`;
    });
    return html;
}

function load_amounts_section(order){
    let html = `
    <div class="row">
        <div class="col-sm-12">
            <h5 class="or_value_s">Order Value: ${order.total_value}</h5>
            <h5 class="or_value_s">VAT Value: ${order.vat_value}</h5>
            <h5 class="or_value_s">Service Charges: ${order.service_charges}</h5>
            <h3 class="or_value">Total Value: ${order.final_value}</h3>
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
            <div class="w88 txt_center m_auto">
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
            <div class="w88 txt_center m_auto">
                <a class="btn btn-primary move_btn" style="display: block;width: 100%;">Move to Outgoing</a>
            </div>
        </div>
    </div>`;
    return html;
}

function load_ready_buttons(order){
    let html = `
    <div class="row nomodal">
        <div class="col-sm-12">
            <div class="w88 txt_center m_auto">
                <a class="btn btn-primary pickup_btn" data-toggle="modal" data-target="#ready_modal" ui-toggle-class="bounce" ui-target="#ready_animate" >Serve</a>
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

function load_hr_section(order){
    let html = `
    <div class="row nomodal">
        <div class="col-sm-12">
            <hr>
        </div>
    </div>`;
    return html;
}
@if(!empty($UserOrders_exists))                   

<form method="post" role="form" id="data-search-form">
    <div class="table-responsive">
        <table class="table table-striped table-hover" id="myDataTable2">
            <thead>
                <tr role="row" class="heading">                          
                    <th>
                        <input type="text" class="form-control" id="search_order_no" autocomplete="off" placeholder="Order No">
                    </th>
                    <th>
                        <input type="number" class="form-control" id="search_order_value" autocomplete="off" placeholder="Order Value">
                    </th>
                    <th>
                        <select class="form-control" id="search_order_status">
                            <option value="-1">Select</option>
                            <option value="3">Confirmed</option>
                            <option value="4">Declined</option>
                            <option value="5">Accepted</option>
                            <option value="6">Preparing</option>
                            <option value="7">Ready</option>
                            <option value="8">Collected</option>
                        </select>
                    </th>
                    <th></th>
                </tr>
                <tr role="row" class="heading">
                    <th>Order No</th>
                    <th>Order Value</th>
                    <th>Status</th>
                    <th>Action</th>                                                   
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</form>

@else

<p style="text-align:center; font-weight:bold; padding:50px;">No Records Available</p>

@endif
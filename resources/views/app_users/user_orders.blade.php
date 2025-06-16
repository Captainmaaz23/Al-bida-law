@if($UserOrders_exists == 1)                   
<?php
$AUTH_USER = Auth::user();
?>
<!--    <form method="post" role="form" id="data-search-form">-->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="myDataTable2">
                <thead>
                    <tr role="row" class="heading">
                        @if($AUTH_USER->rest_id == 0) 
                        <td>
                            <select class="form-control" id="s_rest_id">
                                <option value="-1">Select</option>
                                @foreach($restaurants as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </td>
                        @endif                                           
                        <td>
                            <input type="text" class="form-control" id="s_order_no_2" autocomplete="off" placeholder="Order No">
                        </td>                                                

                        <td>
                            <input type="number" class="form-control" id="s_order_value" autocomplete="off" placeholder="Order Value">
                        </td>

                        <td>
                            <select class="form-control" id="s_order_status">
                                <option value="-1">Select</option>
                                @foreach([
                                    3 => 'Confirmed',
                                    4 => 'Declined',
                                    5 => 'Accepted',
                                    6 => 'Preparing',
                                    7 => 'Ready',
                                    8 => 'Picked',
                                    9 => 'Collected'
                                ] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr role="row" class="heading">
                        @if($AUTH_USER->rest_id == 0)
                        <th>Restaurant</th>
                        @endif
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
<!--    </form>-->

@else

    <p class="text-center font-weight-bold py-5">No Records Available</p>

@endif
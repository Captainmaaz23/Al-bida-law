<div class="block-content">                   
    @if($Order_Details->isNotEmpty())
        <div class="table-responsive"> 
            <div class="table-container">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr role="row" class="heading">
                            <th>#</th>  
                            <th>Order ID</th>  
                            <th>Item</th> 
                            <th>QTY</th>
                            <th>Item Value</th>                 
                            <th>Discount</th>
                            <th>Sub Total</th>       
                            <th>Created AT</th>       
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $addOnsTotal = 0;
                        @endphp

                        @foreach($Order_Details as $index => $record)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $record->order_id }}</td>
                                <td>{{ $record->item_name }}</td>
                                <td>{{ $record->quantity }}</td>
                                <td>${{ number_format($record->item_value, 2) }}</td>
                                <td>${{ number_format($record->discount, 2) }}</td> 
                                <td>${{ number_format($record->total_value, 2) }}</td>
                                <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                                @php
                                    $addOnsTotal += $record->total_value;
                                @endphp
                            </tr>
                        @endforeach

                        <tr class="bg-muted text-light">
                            <td colspan="6" class="text-right">Total =</td>
                            <td>${{ number_format($addOnsTotal, 2) }}</td>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-12">
                No records found
            </div>
        </div>
    @endif
</div>
<div class="row form-group">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <label for="id">Order No:</label>
            </div>
            <div class="col-sm-6">
                <p>{{ $Model_Data->order_no }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label for="table_id">Table:</label>
            </div>
            <div class="col-sm-6">
                <p>{{ get_table_name($Model_Data->table_id) }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label for="status">Order Status:</label>
            </div>
            <div class="col-sm-6">
                @php
                    $statusLabels = [
                        1 => 'Waiting',
                        2 => 'Cancelled',
                        3 => 'Confirmed',
                        4 => 'Declined',
                        5 => 'Accepted',
                        6 => 'Preparing',
                        7 => 'Ready',
                        8 => 'Picked',
                        9 => 'Collected'
                    ];
                @endphp
                <p>{{ $statusLabels[$Model_Data->status] ?? 'Unknown' }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label for="order_value">Order Value:</label>
            </div>
            <div class="col-sm-6">
                <p>{{ $Model_Data->order_value }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label for="vat_value">GST:</label>
            </div>
            <div class="col-sm-6">
                <p>{{ $Model_Data->vat_value }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label for="service_charges">Service Charges:</label>
            </div>
            <div class="col-sm-6">
                <p>{{ $Model_Data->service_charges }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label for="total_value">Total Value:</label>
            </div>
            <div class="col-sm-6">
                <p>{{ $Model_Data->final_value }}</p>
            </div>
        </div>
    </div>
</div>
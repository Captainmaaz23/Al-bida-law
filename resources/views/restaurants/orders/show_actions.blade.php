<div class="row form-group">
    <div class="col-sm-12">
        @foreach([
            'Confirmation' => 'confirmed_time',
            'Declined' => 'declined_time',
            'Accepted' => 'accepted_time',
            'Preparing' => 'preparation_time',
            'Ready' => 'ready_time',
            'Picked' => 'picked_time',
            'Collected' => 'collected_time'
        ] as $label => $timeField)

            @if($Model_Data->status >= constant("App\Models\Order::STATUS_$label"))
                <div class="row">
                    <div class="col-sm-6">
                        <label for="lbl_{{ $label }}">{{ $label }}:</label>
                    </div>
                    <div class="col-sm-6">
                        <p>
                            @if($Model_Data->$timeField != 0)
                                {{ \Carbon\Carbon::createFromTimestamp($Model_Data->$timeField)->format('Y-m-d H:i') }}
                            @endif
                        </p>
                    </div>
                </div>
            @endif

        @endforeach
    </div>
</div>

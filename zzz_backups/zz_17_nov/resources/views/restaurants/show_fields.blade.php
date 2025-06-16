<div class="row justify-content-center mt-2 mb-2 form-group">
    <div class="col-sm-6">
        
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="is_open">Restaurant Status:</label>
            </div>
            <div class="col-sm-8">
                @php
                    $Auth_User = Auth::user();
                    $is_open = $Model_Data->is_open;
                    $status = [
                        1 => ['Open', 'btn-outline-success'],
                        2 => ['Busy', 'btn-outline-primary'],
                        0 => ['Closed', 'btn-outline-danger']
                    ];
                    $currentStatus = $status[$is_open] ?? ['Closed', 'btn-outline-danger'];
                @endphp

                <div class="btn-group" role="group" aria-label="Restaurant Status">
                    <a class="btn {{ $currentStatus[1] }}" href="#" title="Restaurant is {{ $currentStatus[0] }}">
                        {{ $currentStatus[0] }}
                    </a>

                    @can('restaurants-status') 
                        <a class="btn btn-outline-success" href="{{ route('restaurant-open', $Model_Data->id) }}" title="Make Restaurant Open">
                            <i class="fa fa-check"></i>
                        </a>
                        <a class="btn btn-outline-primary" href="{{ route('restaurant-busy', $Model_Data->id) }}" title="Make Restaurant Busy">
                            <i class="fa fa-check"></i>
                        </a>
                        <a class="btn btn-outline-danger" href="{{ route('restaurant-close', $Model_Data->id) }}" title="Make Restaurant Close">
                            <i class="fa fa-times"></i>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="is_open">Restaurant is:</label>
            </div>
            <div class="col-sm-8">
                @php
                    $open_data = is_restaurant_open($Model_Data);
                @endphp
                <p>{{ $open_data['open_str'] }}</p>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="name">Name [En]:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->name }}</p>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="rest_status">Activity Status:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $activity_status }}</p>
            </div>
        </div>
        
<!--        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="arabic_name">Name [Urdu]:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->arabic_name }}</p>
            </div>
        </div>-->
        
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="avg_time">Average Preparation Time:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->avg_time }}</p>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="featured">Is Featured:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->is_featured == 1 ? 'Yes' : 'No' }}</p>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="status">Status:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->status == 1 ? 'Active' : 'Inactive' }}</p>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="created_at">Created At:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->created_at }}</p>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="updated_at">Updated At:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->updated_at }}</p>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-sm-12 text-center">
                <a href="{{ route('restaurants.edit', $Model_Data->id) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('restaurants.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6">        
        @if(!empty($Model_Data->qrcode))
            <div class="row text-center">
                <div class="col-sm-12">
                    <img id="qrimage" src="{{ uploads('restaurants/qrcodes/'.$Model_Data->qrcode) }}" class="img-thumbnail img-responsive cust_img_cls" alt="QR Code" />
                </div>
            </div>
        @endif
        
        @if(!empty($Model_Data->profile))
            <div class="row mt-3 text-center">
                <div class="col-sm-12">
                    <img id="image" src="{{ uploads('restaurants/'.($Model_Data->profile == 'restaurant.png' ? 'defaults/'.$Model_Data->profile : $Model_Data->profile)) }}" class="img-thumbnail img-responsive cust_img_cls" alt="Restaurant Profile" />
                </div>
            </div>
        @endif
    </div>
</div>
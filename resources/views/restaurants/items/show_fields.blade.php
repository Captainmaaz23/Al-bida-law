<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6"> 
        <div class="form-group">  
            @if(isset($Model_Data->image))
                @php
                    $image = $Model_Data->image;
                    $image_path = $image == 'item.png' ? 'defaults/' : 'items/';
                    $image_path .= $image;
                @endphp
                <img src="{{ uploads($image_path) }}" class="img-thumbnail img-responsive cust_img_cls" alt="Image" />
            @endif  
        </div>
    </div>
    <div class="col-sm-3"></div> 
</div>

<div class="row">
    @if(Auth::user()->rest_id == 0 && isset($restaurants_array[$Model_Data->rest_id]))
        <div class="col-sm-6">
            <div class="form-group">
                <label for="restaurant">Restaurant:</label>
                <p>{{ $restaurants_array[$Model_Data->rest_id] }}</p>
            </div>
        </div> 
    @endif
    <div class="col-sm-6">
        <div class="form-group">
            <label for="menu">Menu:</label>
            <p>{{ $menus[$Model_Data->menu_id] }}</p>
        </div>
    </div> 
</div>

@foreach (['name' => 'Name', 'description' => 'Description'] as $field => $label)
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="{{ $field }}">{{ $label }}:</label>
                <p>{{ $Model_Data->$field }}</p>
            </div>
        </div> 
    </div>
@endforeach

<div class="row">
    <div class="col-sm-4">
        <label for="price">Price:</label>
    </div>
    <div class="col-sm-4">
        <p>{{ get_decimal($Model_Data->price) }}</p>
    </div>  
</div>

<div class="row">
    <div class="col-sm-4">
        <label for="tag">Tag:</label>
    </div>
    <div class="col-sm-4">
        <p>{{ $Model_Data->selling_status == 0 ? 'New Arrival' : 'Best Selling' }}</p>
    </div>
</div>

@foreach (['created_at' => 'Created At', 'updated_at' => 'Updated At'] as $field => $label)
    <div class="row">
        <div class="col-sm-4">
            <label for="{{ $field }}">{{ $label }}:</label>
        </div>
        <div class="col-sm-4">
            <p>{{ $Model_Data->$field }}</p>
        </div> 
    </div>
@endforeach

<div class="row mt-3 mb-3">
    <div class="col-sm-12 text-center">
        <a href="{{ route('items.edit', $Model_Data->id) }}" class='btn btn-primary'>Edit</a>
        <a href="{{ route('items.index') }}" class="btn btn-outline-dark">Cancel</a>
    </div>
</div>
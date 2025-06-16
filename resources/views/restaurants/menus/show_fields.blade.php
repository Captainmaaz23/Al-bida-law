<div class="row justify-content-center mt-2 mb-2 form-group">
    <div class="col-sm-6">
        @if(Auth::user()->rest_id == 0 && isset($restaurants_array[$Model_Data->rest_id]))
            <div class="row mt-3">
                <div class="col-md-4">
                    <label for="restaurant">Restaurant:</label>
                </div>
                <div class="col-md-8">
                    <p>{{ $restaurants_array[$Model_Data->rest_id] }}</p>
                </div>
            </div>
        @endif

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="title">Title:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->title }}</p>
            </div>
        </div>

        {{-- Uncomment if needed --}}
        {{-- 
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="ar_title">Title [Ar]:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->ar_title }}</p>
            </div>
        </div>
        --}}

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
            <div class="col-8 text-right">
                <a href="{{ route('menus.edit', $Model_Data->id) }}" class="btn btn-primary">
                    Edit
                </a>
                <a href="{{ route('menus.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div> 

    @if(isset($Model_Data->icon))
        @php
            $image_path = $Model_Data->icon === 'menu.png' ? 'defaults/' : 'menus/';
            $image_path .= $Model_Data->icon;
        @endphp
        <div class="col-sm-6">
            <img id="image" src="{{ uploads($image_path) }}" class="img-thumbnail img-responsive cust_img_cls" alt="Image" />
        </div>
    @endif
</div>
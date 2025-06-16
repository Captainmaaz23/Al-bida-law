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

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="created_at">Created At:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="updated_at">Updated At:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->updated_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-8 text-right">
                <a href="{{ route('serve-tables.edit', $Model_Data->id) }}" class='btn btn-primary'>
                    Edit
                </a>
                <a href="{{ route('serve-tables.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div>

    @if(isset($Model_Data->icon))
        <div class="col-sm-6">
            <?php
            $image = $Model_Data->icon;
            $image_path = ($image == 'table.png') ? 'defaults/' : 'serve_tables/';
            $image_path .= $image;
            ?>
            <img id="image" src="{{ uploads($image_path) }}" class="img-thumbnail img-responsive cust_img_cls" alt="Image" />
        </div>
    @endif
</div>
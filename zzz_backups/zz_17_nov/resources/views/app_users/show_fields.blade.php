<div class="row form-group">
    <div class="col-sm-6">
        @foreach ([
            'name' => 'Name',
            'phone' => 'Phone',
            'username' => 'Username',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ] as $field => $label)
            <div class="row">
                <div class="col-sm-4">
                    <label for="{{ $field }}">{{ $label }}:</label>
                </div>
                <div class="col-sm-8">
                    <p>{{ $Model_Data->$field }}</p>
                </div>
            </div>
        @endforeach

        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <a href="{{ route('app-users.edit', $Model_Data->id) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('app-users.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        @if(isset($Model_Data->photo))
            @php
                $image = $Model_Data->photo;
                $image_path = $image === 'app_user.png' ? 'defaults/' : 'app_users/';
                $image_path .= $image;
            @endphp
            <img id="image" src="{{ uploads($image_path) }}" class="img-thumbnail img-responsive cust_img_cls" alt="Image" />
        @endif
    </div>
</div>
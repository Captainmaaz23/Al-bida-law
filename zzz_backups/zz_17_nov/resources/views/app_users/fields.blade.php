<?php
$user_types_array = ['1' => 'Waiter', '2' => 'Table-User'];
$show_2nd_col = isset($Model_Data->photo) ? 1 : 0;
$cols_span = $show_2nd_col ? 'col-sm-6' : 'col-sm-8';
?>

<div class="row justify-content-center mt-2 mb-2 form-group">
    <div class="{{ $cols_span }}">
        <div class="row">
            <div class="col-sm-4">
                <label for="name">Name:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="name" class="form-control" value="{{ (isset($Model_Data->name)) ? $Model_Data->name : '' }}">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="phone">Phone:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="phone" class="form-control" value="{{ (isset($Model_Data->phone)) ? $Model_Data->phone : '' }}">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="user_type">User Type:</label>
            </div>
            <div class="col-sm-8">
                <select name="user_type" class="form-control">
                    <option value="" selected disabled>select</option>
                    @foreach ($user_types_array as $value => $label)
                        <option value="{{ $value }}" {{ (isset($Model_Data->user_type) && $value == $Model_Data->user_type) ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mt-3" id="user_type_data">
            @if (isset($Model_Data) && $Model_Data->user_type == 2)
                <div class="col-sm-4">
                    <label for="table_id">Assign[ed] Table:</label>
                </div>
                <div class="col-sm-8">
                    <select name="table_id" class="form-control">
                        <option value="">select</option>
                        @foreach ($Tables as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="username">Username:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="username" class="form-control" value="{{ (isset($Model_Data->username)) ? $Model_Data->username : '' }}" {{ isset($Model_Data) ? 'disabled' : '' }}>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="password">Password:</label>
            </div>
            <div class="col-sm-8">
                <input type="password" class="form-control" id="password" name="password" value="" />
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="file_upload">Image:</label>
            </div>
            <div class="col-sm-8">
                <input type="file" name="file_upload" class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('app-users.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div>

    @if (isset($Model_Data->photo))
        <?php
        $image = $Model_Data->photo;
        $image_path = $image == 'app_user.png' ? 'defaults/' : 'app_users/';
        $image_path .= $image;
        ?>
        <div class="col-sm-6">
            <img id="image" src="{{ uploads($image_path) }}" class="img-thumbnail img-responsive cust_img_cls" alt="Image" />
        </div>
    @endif
</div>

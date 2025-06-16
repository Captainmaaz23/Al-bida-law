<?php
$segment1 = Request::segment(3);
?>
<div class="row mt-2 mb-2 form-group">
    <div class="col-sm-8">

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="name">Name:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="name" class="form-control" value="">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="arabic_name">Name[ Urdu ]:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="arabic_name" class="form-control" value="">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="phoneno">Phone#:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="phoneno" class="form-control" value="">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="email">Email:</label>
            </div>
            <div class="col-sm-8">
                <input type="email" name="email" class="form-control" value="">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="website">Website:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="website_link" class="form-control" value="">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="avg_time">Average Preparation Time:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="avg_time" class="form-control" value="">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="profile">Profile:</label>
            </div>
            <div class="col-sm-8">
                <input type="file" name="profile" class="form-control">
            </div>
        </div>
        <div class="mt-4 row pb-4">
            <div class="col-sm-12 text-right">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('restaurants.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>

    </div>
    <div class="col-sm-4">
        <?php
        if (isset($Model_Data->profile)) {
            $image = $Model_Data->profile;
            $image_path = 'restaurants/';
            if ($image == 'brand.png') {
                $image_path = 'defaults/';
            }
            $image_path .= $image;
            ?>
            <div class="row text-center">
                <div class="col-sm-12">
                    <img id="image" src="{{ uploads($image_path) }}" class="img-thumbnail img-responsive cust_img_cls" alt="Image" />
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
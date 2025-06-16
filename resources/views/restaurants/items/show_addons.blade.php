<div class="row">
    <?php
    if (isset($variant->type_id) && isset($addon_types[$variant->type_id])) {
        ?>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="type">Type:</label>
                <p>{{ $addon_types[$variant->type_id] }}</p>
            </div>
        </div> 
        <?php
    }
    ?>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="name">Name:</label>
            <p>{{ $variant->name }}</p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="price">Price:</label>
            <p>{{ get_decimal($variant->price) }}</p>
        </div>
    </div>
</div>

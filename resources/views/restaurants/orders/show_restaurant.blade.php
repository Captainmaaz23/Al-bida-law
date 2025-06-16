<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name">Name [ English ]:</label>
            <p>{{ $Model_Data->r_name }}</p>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="arabic_name">Name [ Urdu ]:</label>
            <p>{{ $Model_Data->r_a_name }}</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="email">Email:</label>
            <p>{{ $Model_Data->r_email }}</p>
        </div>

    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="phoneNo">Phone#:</label>
            <p>{{ $Model_Data->r_phone }}</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="location">Location:</label>
            <p>{{ $Model_Data->r_location }}</p>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="website">Website:</label>
            <p>{{ $Model_Data->r_website }}</p>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-3">
        <label for="email">Email:</label>
    </div>
    <div class="col-md-9">
        <p> {!! $Model_Data->email !!} </p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <label for="phone">Phone:</label>
    </div>
    <div class="col-md-9">
        <p> {!! $Model_Data->phoneno !!} </p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <label for="website">Website:</label>
    </div>
    <div class="col-md-9">
        <p> <a href="{!! $Model_Data->website_link !!}" target="_blank">{!! $Model_Data->website_link !!} </a></p>
    </div>
</div>


<?php
$menus = $menus ?? [];

// $availability = [
//     '0' => 'Not Available',
//     '1' => 'Available',
// ];

?>

<div class="row">
    @if(Auth::user()->rest_id == 0)
        <div class="form-group col-sm-6">
            <label for="rest_id">Restaurant:</label>
            <select name="rest_id" class="form-control js-select2 form-select rest_select2" placeholder="select" {{ isset($Model_Data) || isset($item_rest_id) ? 'disabled' : '' }}>
                <option value="" selected disabled>select</option>
                @foreach ($restaurant as $value => $label)
                    <option value="{{ $value }}" {{ (isset($Model_Data) && $value == $Model_Data->rest_id) || (isset($item_rest_id) && $value == $item_rest_id) ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    @else
        <input type="hidden" id="rest_id" name="rest_id" value="{{ Auth::user()->rest_id }}" />
    @endif

    <div class="form-group col-sm-6 d-none">
        <label for="menu_id">Menu:</label>
        <select name="menu_id" class="form-control js-select2 form-select menu_select2" placeholder="select">
            <option value="" selected disabled>select</option>
            @foreach ($menus as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>   
    </div>
</div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="fullname">Full Name:</label>
            <input type="text" name="fullname" class="form-control">
        </div>
        <div class="form-group col-sm-6">
            <label for="email">Email:</label>
            <input type="text" name="email" class="form-control">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="phone">Phone:</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="form-group col-sm-6">
            <label for="city">City:</label>
            <input type="text" name="city" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12 mt-4">
            <label for="city">Date :</label>
            <input type="date" name="date" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12 mt-4">
            <label for="message">Message:</label>
            <textarea 
                name="message" 
                class="form-control" 
                placeholder="Description"
                style="text-align: start;"
                rows="5"
            >{!! isset($slidder->text) ? $slidder->text : '' !!}</textarea>
        </div>
    </div>

<div class="form-group col-sm-12 text-right">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('contact.index') }}" class="btn btn-outline-dark">Cancel</a>
</div>



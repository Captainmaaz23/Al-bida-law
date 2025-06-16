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
            <label for="email">Email:</label>
            <input type="text" name="email" class="form-control" value="{{ isset(($companydetail->email)) ? $companydetail->name : '' }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="fullname">Phone Number:</label>
            <input type="text" name="phonenumber" class="form-control" value="{{ isset(($companydetail->phonenumber)) ? $companydetail->phonenumber : '' }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="phone">Facebook Link:</label>
            <input type="text" name="facebook" class="form-control" value="{{ isset(($companydetail->facebook)) ? $companydetail->facebook : '' }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="city">Snapchat:</label>
            <input type="text" name="snapchat" class="form-control" value="{{ isset(($companydetail->snapchat)) ? $companydetail->snapchat : '' }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="phone">Instagram Link:</label>
            <input type="text" name="instagram" class="form-control" value="{{ isset(($companydetail->instagram)) ? $companydetail->instagram : '' }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="city">Twitter:</label>
            <input type="text" name="twitter" class="form-control" value="{{ isset(($companydetail->twitter)) ? $companydetail->twitter : '' }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="phone">Youtube Link:</label>
            <input type="text" name="youtube" class="form-control" value="{{ isset(($companydetail->youtube)) ? $companydetail->youtube : '' }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="city">Address:</label>
            <input type="text" name="address" class="form-control" value="{{ isset(($companydetail->address)) ? $companydetail->address : '' }}">
        </div>
    </div>

<div class="form-group col-sm-12 text-right">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('company-detail.index') }}" class="btn btn-outline-dark">Cancel</a>
</div>



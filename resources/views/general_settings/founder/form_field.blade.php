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
            <label for="email">Founder Name :</label>
            <input type="text" name="founder_name" placeholder="Founder Name" class="form-control" value="{{ isset(($founder->founder_name)) ? $founder->founder_name : '' }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="email" dir="rtl" class="text-end d-flex">اسم المؤسس:</label>
            <input type="text" name="arabic_name" placeholder="اسم المؤسس" dir="rtl" class="form-control" value="{{ isset(($founder->arabic_name)) ? $founder->arabic_name : '' }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="fullname">English Designation :</label>
            <input type="text" name="designation" placeholder="Designation" class="form-control" value="{{ isset(($founder->designation)) ? $founder->designation : '' }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="email" class="text-end d-flex" dir="rtl">التسمية العربية:</label>
            <input type="text" name="arabic_designation" dir="rtl" placeholder="التسمية العربية" class="form-control" value="{{ isset(($founder->arabic_designation)) ? $founder->arabic_designation : '' }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="city">English Message :</label>
            <input type="text" placeholder="Message" name="message" class="form-control" value="{{ isset(($founder->message)) ? $founder->message : '' }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="city" class="text-end d-flex" dir="rtl">الرسالة العربية:</label>
            <input type="text" name="arabic_message" placeholder="الرسالة العربية:"  dir="rtl" class="form-control" value="{{ isset(($founder->arabic_message)) ? $founder->arabic_message : '' }}">
        </div>
    </div>

    <div class="form-group col-sm-6">
        <label for="phone">Image :</label>
        <input type="file" name="image" class="form-control">
    </div>
    

<div class="form-group col-sm-12 text-right">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('founder.index') }}" class="btn btn-outline-dark">Cancel</a>
</div>



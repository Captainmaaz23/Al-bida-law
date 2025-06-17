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
            <label for="email">English Title :</label>
            <input type="text" name="title" placeholder="Title" class="form-control" value="{{ isset(($case_study->title)) ? $case_study->title : '' }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="email" dir="rtl" class="d-flex text-end">العنوان العربي :</label>
            <input type="text" name="arabic_title" dir="rtl" placeholder="العنوان العربي :" class="form-control" value="{{ isset(($case_study->arabic_title)) ? $case_study->arabic_title : '' }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="fullname">English Attorny :</label>
            <input type="text" placeholder="Attorny" name="attorny" class="form-control" value="{{ isset(($case_study->attorny)) ? $case_study->attorny : '' }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="fullname" dir="rtl" class="text-end d-flex">المحامي العربي :</label>
            <input type="text" name="arabic_attorny" dir="rtl" placeholder="المحامي العربي" class="form-control" value="{{ isset(($case_study->arabic_attorny)) ? $case_study->arabic_attorny : '' }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="phone">English Client :</label>
            <input type="text" placeholder="Client" name="client" class="form-control" value="{{ isset(($case_study->client)) ? $case_study->client : '' }}">
        </div>

        <div class="form-group col-sm-6">
            <label for="phone" dir="rtl" class="text-end d-flex">العميل العربي :</label>
            <input type="text" name="arabic_client" dir="rtl" placeholder="العميل العربي " class="form-control" value="{{ isset(($case_study->arabic_client)) ? $case_study->arabic_client : '' }}">
        </div>
        
    </div>

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="city">English Decision :</label>
            <input type="text" placeholder="Decision" name="decision" class="form-control" value="{{ isset(($case_study->decision)) ? $case_study->decision : '' }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="city" dir="rtl" class="text-end d-flex">القرار العربي :</label>
            <input type="text" name="arabic_decision" dir="rtl" placeholder="القرار العربي :" class="form-control" value="{{ isset(($case_study->arabic_decision)) ? $case_study->arabic_decision : '' }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="phone">Start Date:</label>
            <input type="date" name="started_date" class="form-control" value="{{ isset(($case_study->started_date)) ? $case_study->started_date : '' }}">
        </div>
        <div class="form-group col-sm-6">
            <label for="city">End Date:</label>
            <input type="date" name="end_date" class="form-control" value="{{ isset(($case_study->end_date)) ? $case_study->end_date : '' }}">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6">
            <label for="phone">Image :</label>
            <input type="file" name="image" class="form-control" value="{{ isset(($case_study->image)) ? $case_study->image : '' }}">
        </div>
    </div>

<div class="form-group col-sm-12 text-right">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('case-study.index') }}" class="btn btn-outline-dark">Cancel</a>
</div>



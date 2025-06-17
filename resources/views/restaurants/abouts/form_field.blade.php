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
        <label for="title">Title English:</label>
        <input type="text" name="title" class="form-control" value="{{ (isset($about->title)) ? $about->title : '' }}">
    </div>
    <div class="form-group col-sm-6">
        <label for="name">Title Arabic:</label>
        <input type="text" name="arabic_title" dir="rtl" class="form-control" value="{{ (isset($about->arabic_title)) ? $about->arabic_title : '' }}">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-6">
        <label for="description">English Description:</label>
        <textarea 
            name="description" 
            class="form-control" 
            placeholder="Your Description" 
            cols="10" 
            rows="5" 
             
            maxlength="200">{{ isset($about->description) ? $about->description : '' }}</textarea>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="description">Arabic Description:</label>
        <textarea 
            name="arabic_description" 
            class="form-control" 
            placeholder="Your Description" 
            cols="10" 
            rows="5" 
             
            maxlength="200">{{ isset($about->arabic_description) ? $about->arabic_description : '' }}</textarea>
    </div>
</div>


<div class="row">
    <div class="form-group col-sm-6">
        <label for="price">English Image Title:</label>
        <div class="input-group">			
            <input type="text" name="image_title" value="{{ (isset($about->image_title)) ? $about->arabic_title : '' }}" class="form-control txt_price" step="0.001">
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="price">Arabic Image Title:</label>
        <div class="input-group">			
            <input type="text" name="arabic_imagetitle" dir="rtl" value="{{ (isset($about->arabic_imagetitle)) ? $about->arabic_imagetitle : '' }}" class="form-control txt_price" step="0.001">
        </div>
    </div>

    <div class="form-group col-sm-12">
        <label for="image">Image:</label>
        <input type="file" name="image" class="form-control">
    </div>
</div>

<div class="form-group col-sm-12 text-right">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('about.index') }}" class="btn btn-outline-dark">Cancel</a>
</div>
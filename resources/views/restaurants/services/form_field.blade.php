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
        <label for="name">Title English:</label>
        <input type="text" name="name" class="form-control" value="{{ (isset($services->name)) ? $services->name : '' }}">
    </div>
    <div class="form-group col-sm-6">
        <label for="name">Title Arabic:</label>
        <input type="text" name="title_arabic" class="form-control" value="{{ (isset($services->title_arabic)) ? $services->title_arabic : '' }}">
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
            required 
            maxlength="500">{{ isset($services->description) ? $services->description : '' }}</textarea>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="description">Arabic Description:</label>
        <textarea 
            name="arabic_description" 
            class="form-control" 
            placeholder="Your Description" 
            cols="10" 
            rows="5" 
             
            maxlength="500">{{ isset($services->arabic_description) ? $services->arabic_description : '' }}</textarea>
    </div>
</div>


<div class="row">
    <div class="form-group col-sm-6 d-none">
        <label for="price">Price:</label>
        <div class="input-group">			
            <input type="number" name="price" class="form-control txt_price" step="0.001">
            <span class="input-group-text">PKR</span>
        </div>
    </div>

    <div class="form-group col-sm-12">
        <label for="image">Image:</label>
        <input type="file" name="image" class="form-control">
    </div>

    <div style="display: none">
        <label for="discount">Discount %:</label>
        <div class="input-group">			
            <input type="number" name="discount" class="form-control txt_discount" value="0" step="0.001">
            <span class="input-group-text">%</span>
        </div>        
    </div>

    <div style="display: none">
        <label for="total_value">Total:</label>
        <div class="input-group">
            <input type="text" name="total_value" class="form-control txt_total" readonly>
            <span class="input-group-text">PKR</span>
        </div>        
    </div>
</div>

{{-- <div class="row d-none">
    <div class="form-group col-sm-6">
        <label for="availability">Availability:</label>
        <select name="availability" class="form-control" required>
            <option value="" selected disabled>select</option>
            @foreach ($availability as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-6">
        <label>Tag:</label>
        <div>
            <input type="radio" name="selling_status" value="0" checked>
            <label for="selling_status">New Arrival</label>
        </div>
        <div>
            <input type="radio" name="selling_status" value="1">
            <label for="selling_status">Best Seller</label>
        </div>
    </div>
</div> --}}

<div class="form-group col-sm-12 text-right">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('law-services.index') }}" class="btn btn-outline-dark">Cancel</a>
</div>
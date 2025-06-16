<?php
$array = [
    '0' => 'No',
    '1' => 'Yes'
];

$item_id = isset($Model_Data) ? $Model_Data->item_id : 0;
$class = isset($Model_Data) ? 'col-sm-8' : 'col-sm-12';
?>

<div class="row justify-content-center mt-2 mb-2 form-group">
    <div class="{{ $class }}">
        <div class="form-group row mt-3">
            <div class="col-sm-4">
                <label for="item_id">Item:</label>
            </div>
            <div class="col-sm-8">
                <select name="{{ isset($Model_Data->item_id) ? 'item_id_2' : 'item_id' }}" class="form-control js-select2 form-select item_select2" {{ isset($Model_Data->item_id) ? 'disabled' : '' }}>
                    <option value="" selected disabled>select</option>
                    @foreach ($Items as $id => $name)
                        <option value="{{ $id }}" {{ (isset($Model_Data->item_id) && $id == $Model_Data->item_id) ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if(isset($Model_Data->item_id))
            <input type="hidden" id="item_id" name="item_id" value="{{ $Model_Data->item_id }}" />
        @endif

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="title">Name:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="title" class="form-control" value="">
            </div>
        </div>

        <div class="form-group row mt-3">
            <div class="col-sm-4">
                <label for="is_mandatory">Mandatory:</label>
            </div>
            <div class="col-sm-8">
                <select name="is_mandatory" class="form-control" required>
                    <option value="" selected disabled>select</option>
                    @foreach ($array as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row mt-3">
            <div class="col-sm-4">
                <label for="is_multi_select">Multi-Select:</label>
            </div>
            <div class="col-sm-8">
                <select name="is_multi_select" class="form-control" required>
                    <option value="" selected disabled>select</option>
                    @foreach ($array as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row mt-3">
            <div class="col-sm-4">
                <label for="max_selection">Maximum Selection:</label>
            </div>
            <div class="col-sm-8">			
                <input type="number" name="max_selection" class="form-control" required min="0" max="10">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="file_upload">Image:</label>
            </div>
            <div class="col-sm-8">
                <input type="file" name="file_upload" class="form-control">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
                @if(isset($Model_Data->item_id))
                    <a href="{{ route('items.edit', $Model_Data->item_id) }}" class="btn btn-outline-dark">Cancel</a>
                @endif
            </div>
        </div>
    </div>

    @if(isset($Model_Data->icon))
        <div class="col-sm-4">
            <img id="image" src="{{ uploads(isset($Model_Data->icon) && $Model_Data->icon == 'addon_type.png' ? 'defaults/' : 'addon_types/' . $Model_Data->icon) }}" class="img-thumbnail img-responsive cust_img_cls" alt="Image" />
        </div>
    @endif
</div>
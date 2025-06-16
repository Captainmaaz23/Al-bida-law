<?php
$array = [
    '0' => "No",
    '1' => "Yes",
];
?>

<div class="row justify-content-center mt-2 mb-2 form-group">
    <div class="col-sm-8">
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="item_id">Item:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Items[$Model_Data->item_id] }}</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="name">Name:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->title }}</p>
            </div>
        </div>

        {{-- Uncomment this section if needed --}}
        {{-- 
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="name_ar">Name [Ar]:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->title_ar }}</p>
            </div>
        </div>
        --}}

        <div class="form-group row mt-3">
            <div class="col-sm-4">
                <label for="is_mandatory">Mandatory:</label>
            </div>
            <div class="col-sm-8">
                <p>{!! $array[$Model_Data->is_mandatory] !!}</p>
            </div>
        </div>

        <div class="form-group row mt-3">
            <div class="col-sm-4">
                <label for="is_multi_select">Multi-Select:</label>
            </div>
            <div class="col-sm-8">
                <p>{!! $array[$Model_Data->is_multi_select] !!}</p>
            </div>
        </div>

        <div class="form-group row mt-3">
            <div class="col-sm-4">
                <label for="max_selection">Maximum Selection:</label>
            </div>
            <div class="col-sm-8">
                <p>{!! $Model_Data->max_selection !!}</p>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="created_at">Created At:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->created_at->format('Y-m-d H:i') }}</p> {{-- Format as needed --}}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="updated_at">Updated At:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->updated_at->format('Y-m-d H:i') }}</p> {{-- Format as needed --}}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-12 text-center">
                <a href="{{ route('addon-types.edit', $Model_Data->id) }}" class='btn btn-primary'>
                    Edit
                </a>
                <a href="{{ route('items.edit', $Model_Data->item_id) }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div>

    @if(isset($Model_Data->icon))
        @php
            $image = $Model_Data->icon;
            $image_path = ($image == 'addon_type.png') ? 'defaults/' : 'addon_types/';
            $image_path .= $image;
        @endphp
        <div class="col-sm-4">
            <img id="image" src="{{ uploads($image_path) }}" class="img-thumbnail img-responsive cust_img_cls" alt="Image" />
        </div>
    @endif
</div>

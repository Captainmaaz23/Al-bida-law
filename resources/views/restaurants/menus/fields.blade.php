@php
$availability = [
    '0' => "Not Available",
    '1' => "Available",
];
@endphp

<div class="row justify-content-center mt-2 mb-2 form-group">
    <div class="col-sm-6">

        @if(Auth::user()->rest_id == 0)
            <div class="form-group row mt-3">
                <div class="col-sm-4">
                    <label for="rest_id">Restaurant:</label>
                </div>
                <div class="col-sm-8">
                    <select name="rest_id" class="form-control js-select2 form-select rest_select2" {{ isset($Model_Data->rest_id) ? 'disabled' : '' }}>
                        <option value="" {{ !isset($Model_Data->rest_id) ? 'disabled selected' : '' }}>Select</option>
                        @foreach($restaurants_array as $value => $label)
                            <option value="{{ $value }}" {{ isset($Model_Data->rest_id) && $value == $Model_Data->rest_id ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <input type="hidden" id="rest_id" name="rest_id" value="{{ Auth::user()->rest_id }}" />
        @endif

        <div class="form-group row mt-3">
            <div class="col-sm-4">
                <label for="title">Title:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="title" class="form-control" value="{{ (isset($Model_Data->title)) ? $Model_Data->title : old('title') }}">
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group row mt-3">
            <div class="col-sm-4">
                <label for="file_upload">Image:</label>
            </div>
            <div class="col-sm-8">
                <input type="file" name="file_upload" class="form-control">
            </div>
        </div>

        <div class="form-group row mt-3">
            <div class="col-sm-4">
                <label for="availability">Availability:</label>
            </div>
            <div class="col-sm-8">
                <select name="availability" class="form-control" required>
                    <option value="" selected disabled>Select</option>
                    @foreach ($availability as $value => $label)
                        <option value="{{ $value }}" {{ (isset($Model_Data->availability) && $value == $Model_Data->availability) ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row mt-3 mb-3">
            <div class="col-sm-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('menus.index') }}" class='btn btn-outline-dark'>Cancel</a>
            </div>
        </div>
    </div>

    @if(isset($Model_Data->icon))
        @php
            $image = $Model_Data->icon;
            $image_path = ($image == 'menu.png') ? 'defaults/' : 'menus/';
            $image_path .= $image;
        @endphp
        <div class="col-sm-6 text-center">
            <img id="image" src="{{ uploads($image_path) }}" class="img-thumbnail img-responsive cust_img_cls" alt="Image" />
        </div>
    @endif
</div>

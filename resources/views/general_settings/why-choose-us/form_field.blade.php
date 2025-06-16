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
        <label for="heading">Heading:</label>
        <input type="text" name="heading" class="form-control" value="{{ $chooseUs->heading }}">
    </div>
    <div class="form-group col-sm-6">
        <label for="summary">Summary:</label>
        <input type="text" name="summary" class="form-control" value="{{ $chooseUs->summary }}">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-12">
        <label for="image">Main Image:</label>
        <input type="file" name="image" class="form-control">
        @if($chooseUs->image)
            <img src="{{ url('public/uploads/why-choose/image/' . $chooseUs->image) }}" width="100" class="mt-2">
        @endif
    </div>    
</div>

<div id="dynamic-fields-container">
    @foreach($chooseUs->details as $index => $detail)
    <div class="dynamic-field-set" id="dynamic-field-set-{{ $index }}" data-record-id="{{ $detail->id }}">
        <input type="hidden" name="detail_ids[{{ $index }}]" value="{{ $detail->id }}">

        <div class="row">
            <div class="form-group col-sm-5">
                <label>Sub Heading:</label>
                <input type="text" name="sub_heading[{{ $index }}]" class="form-control" value="{{ $detail->sub_heading }}">
            </div>

            <div class="form-group col-sm-5">
                <label>Sub Summary:</label>
                <input type="text" name="sub_summary[{{ $index }}]" class="form-control" value="{{ $detail->sub_summary }}">
            </div>

            <div class="form-group col-sm-2">
                <label>&nbsp;</label><br>
                <button type="button" class="btn btn-sm btn-danger remove-field" data-field-id="dynamic-field-set-{{ $index }}">Remove</button>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-12">
                <label>Sub Image:</label>
                <input type="file" name="sub_image[{{ $index }}]" class="form-control">
                @if($detail->sub_image)
                    <img src="{{ url('public/uploads/why-choose/sub_image/' . $detail->sub_image) }}" width="80" class="mt-2">
                @endif
            </div>
        </div>
        <hr>
    </div>
@endforeach


</div>

<div class="row">
    <div class="form-group col-sm-12">
        <button type="button" id="add-more-btn" class="btn btn-sm btn-primary">Add More Sub Fields</button>
    </div>
</div>

<div class="form-group col-sm-12 text-right">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('chooseus.index') }}" class="btn btn-outline-dark">Cancel</a>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

    <script>
        $(document).ready(function() {
            let fieldCounter = {{ $chooseUs->details->count() }};
        
            // Add new dynamic fields
            $('#add-more-btn').click(function() {
                fieldCounter++;
        
                const newFieldSet = `
                <div class="dynamic-field-set" id="dynamic-field-set-${fieldCounter}">
                    <div class="row">
                        <div class="form-group col-sm-5">
                            <label>Sub Heading:</label>
                            <input type="text" name="sub_heading[${fieldCounter}]" class="form-control">
                        </div>
                        <div class="form-group col-sm-5">
                            <label>Sub Summary:</label>
                            <input type="text" name="sub_summary[${fieldCounter}]" class="form-control">
                        </div>
                        <div class="form-group col-sm-2">
                            <label>&nbsp;</label><br>
                            <button type="button" class="btn btn-sm btn-danger remove-field" data-field-id="dynamic-field-set-${fieldCounter}">Remove</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label>Sub Image:</label>
                            <input type="file" name="sub_image[${fieldCounter}]" class="form-control">
                        </div>
                    </div>
                    <hr>
                </div>`;
        
                $('#dynamic-fields-container').append(newFieldSet);
            });
        
            // Handle remove field (with or without recordId)
            $(document).on('click', '.remove-field', function() {
                const fieldId = $(this).data('field-id');
                const $field = $('#' + fieldId);
                const recordId = $field.data('record-id');
                if (recordId) {
                    if (confirm('Are you sure you want to remove this field?')) {
                        $.ajax({
                            url: '{{ url("/delete-field") }}/' + recordId,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $field.remove();
                                showToast('Field removed successfully');
                            },
                            error: function(xhr) {
                                alert('Error removing field: ' + (xhr.responseJSON?.message || 'Unknown error'));
                            }
                        });
                    }
                } else {
                    $field.remove();
                }
            });

        });
        
        // Notification helper
        function showToast(message) {
            alert(message); // You can integrate Toastr or Bootstrap Toast later
        }
        </script>
        
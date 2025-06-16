@php
$segment3 = request()->segment(3);

$permissions = [
    'mod_list' => 1,
    'mod_add' => 1,
    'mod_edit' => 1,
    'mod_view' => 1,
    'mod_status' => 1,
    'mod_delete' => 1,
];

if ($segment3 === 'edit') {
    $permissions = [
        'mod_list' => $Model_Data->mod_list,
        'mod_add' => $Model_Data->mod_add,
        'mod_edit' => $Model_Data->mod_edit,
        'mod_view' => $Model_Data->mod_view,
        'mod_status' => $Model_Data->mod_status,
        'mod_delete' => $Model_Data->mod_delete,
    ];
}
@endphp

<div class="row justify-content-center mt-2 mb-2 form-group">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-4">
                <label for="module_name">Name:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="module_name" class="form-control" value="">
            </div>
        </div>

        @foreach (['list' => 'View Listing', 'add' => 'Add New Record', 'edit' => 'Update Record', 'view' => 'View Record Details', 'status' => 'Change Record Status', 'delete' => 'Delete Record'] as $key => $label)
            @php
                $is_mod = $permissions['mod_' . $key];
                $field = 'mod_' . $key;
            @endphp        
            <div class="row mt-3">
                <div class="col-sm-4">
                    <label for="value">Allow: {{ $label }}:</label>
                </div>
                <div class="col-sm-8">
                    <div class="btn-group radioBtn">
                        <a class="btn btn-primary btn-sm {{ $is_mod ? 'active' : 'notActive' }}" data-toggle="{{ $field }}" data-title="1">Yes</a>
                        <a class="btn btn-primary btn-sm {{ !$is_mod ? 'active' : 'notActive' }}" data-toggle="{{ $field }}" data-title="0">No</a>
                    </div>
                    <input type="hidden" name="{{ $field }}" id="{{ $field }}" value="{{ $is_mod }}">
                </div>
            </div>
        @endforeach

        <div class="row mt-3">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('modules.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div>
</div>

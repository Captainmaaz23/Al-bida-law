<?php
$mod_permissions = [
    'mod_list' => $Model_Data->mod_list,
    'mod_add' => $Model_Data->mod_add,
    'mod_edit' => $Model_Data->mod_edit,
    'mod_view' => $Model_Data->mod_view,
    'mod_status' => $Model_Data->mod_status,
    'mod_delete' => $Model_Data->mod_delete,
];
?>

<div class="row justify-content-center mt-2 mb-2 form-group">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-4">
                <label for="module_name">Name:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->module_name }}</p>
            </div>
        </div>

        @foreach ($mod_permissions as $field => $value)
            <div class="row mt-3">
                <div class="col-sm-4">
                    <label for="value">Allow: {{ ucfirst(str_replace('_', ' ', $field)) }}:</label>
                </div>
                <div class="col-sm-8">
                    <div class="btn-group radioBtn">
                        @foreach ([1 => 'Yes', 0 => 'No'] as $key => $text)
                            <a class="btn btn-primary btn-sm {{ $value == $key ? 'active' : 'notActive' }}" data-toggle="{{ $field }}" data-title="{{ $key }}">
                                {{ $text }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="created_at">Created At:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->created_at }}</p>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="updated_at">Updated At:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->updated_at }}</p>
            </div>
        </div>

        @if(Auth::user()->can('modules-edit') || Auth::user()->can('all'))
            <div class="row mt-3">
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    <a href="{{ route('modules.edit', $Model_Data->id) }}" class='btn btn-primary'>Edit</a>
                    <a href="{{ route('modules.index') }}" class="btn btn-outline-dark">Cancel</a>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="row justify-content-center mt-2 mb-2 form-group">
    <div class="col-sm-8">
        <div class="row">
            <div class="col-sm-4">
                <label for="name">Name:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->name }}</p>
            </div>
        </div>
        <?php /*?><div class="row">
            <div class="col-sm-4">
                <label for="guard_name">Guard:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->guard_name }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label for="display_to">Display:</label>
            </div>
            @if($Model_Data->display_to == 0)
                <div class="col-sm-8">
                    <p>For Restaurant Users Only</p>
                </div>
            @elseif($Model_Data->display_to == 1)
                <div class="col-sm-8">
                    <p>For Admin Users Only</p>
                </div>
            @endif
        </div><?php */?>
        <div class="row">
            <div class="col-sm-4">
                <label for="created_at">Created At:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->created_at }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label for="updated_at">Updated At:</label>
            </div>
            <div class="col-sm-8">
                <p>{{ $Model_Data->updated_at }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
            </div>
            <div class="col-sm-8">
                <a href="{{ route('roles.edit', $Model_Data->id) }}" class='btn btn-primary'>
                   Edit
                </a>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div>
</div>
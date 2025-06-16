<div class="row form-group">
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-4">
                <label for="title">Setting Name:</label>
            </div>
            <div class="col-sm-8">
                <div>{{ $Model_Data->title }}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label for="value">Setting Value:</label>
            </div>
            <div class="col-sm-8">
                <div>{{ $Model_Data->value }}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label for="created_at">Created At:</label>
            </div>
            <div class="col-sm-8">
                <div>{{ \Carbon\Carbon::parse($Model_Data->created_at)->format('d M Y, h:i A') }}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label for="updated_at">Updated At:</label>
            </div>
            <div class="col-sm-8">
                <div>{{ \Carbon\Carbon::parse($Model_Data->updated_at)->format('d M Y, h:i A') }}</div>
            </div>
        </div>

        @if(Auth::user()->can('general-settings-edit') || Auth::user()->can('all'))
        <div class="row mt-3">
            <div class="col-sm-4">
                <!-- Removed unnecessary HTML entity -->
            </div>
            <div class="col-sm-8">
                <a href="{{ route('general-settings.edit', $Model_Data->id) }}" class='btn btn-primary'>
                    Edit
                </a>
                <a href="{{ route('general-settings.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div> 
        @endif
    </div>
</div>
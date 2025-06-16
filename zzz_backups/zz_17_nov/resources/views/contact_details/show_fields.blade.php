<div class="row form-group">
    <div class="col-sm-6">
        <dl class="row">
            <dt class="col-sm-4">Setting Name:</dt>
            <dd class="col-sm-8">{{ $Model_Data->title }}</dd>

            <dt class="col-sm-4">Setting Value:</dt>
            <dd class="col-sm-8">{{ $Model_Data->value }}</dd>

            <dt class="col-sm-4">Created At:</dt>
            <dd class="col-sm-8">{{ \Carbon\Carbon::parse($Model_Data->created_at)->format('d M Y, H:i') }}</dd>

            <dt class="col-sm-4">Updated At:</dt>
            <dd class="col-sm-8">{{ \Carbon\Carbon::parse($Model_Data->updated_at)->format('d M Y, H:i') }}</dd>
        </dl>

        @if(Auth::user()->can('contact-details-edit') || Auth::user()->can('all'))
            <div class="row mt-3">
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    <a href="{{ route('contact-details.edit', $Model_Data->id) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('contact-details.index') }}" class="btn btn-outline-dark">Cancel</a>
                </div>
            </div> 
        @endif
    </div>
</div>
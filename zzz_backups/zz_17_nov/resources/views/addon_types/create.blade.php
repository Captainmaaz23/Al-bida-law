@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Create New Addon Type</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('items.index') }}">Items</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="#">Addon Types</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>
            <a href="{{ route('items.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Create New Addon Type</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('addon-types.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('addon_types.fields')
            </form>
        </div>
    </div>                    
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#rest_id').on('change', function () {
        $('#item_id').html('');
        var itemid = $('#item_id');
        console.log(this.value);
        $.get('{{ url("/") }}/restaurants/' + this.value + '/items.json', function (items) {
            itemid.find('option').remove().end();
            itemid.append('<option value="">Select</option>');

            if (items.length > 0) {
                $.each(items, function (index, item_) {
                    itemid.append('<option value="' + item_.id + '">' + item_.name + '</option>');
                });
                item_select2();
            }
        });
    });
});
</script>
@endpush
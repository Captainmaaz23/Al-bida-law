@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Edit App User: {{ $Model_Data->name }} 
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('app-users.index') }}">App Users</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Details
                    </li>
                </ol>
            </nav>               
            <a href="{{ route('app-users.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Edit User Details</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('app-users.update', $Model_Data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                @include('app_users.fields')

                <div id="user_type_data"></div>
            </form>
        </div>
    </div>                 
</div>

<div id="type_div_data" class="hide">
    <div class="col-sm-4">
        <label for="table_id">Assign[ed] Table:</label>
    </div>
    <div class="col-sm-8">
        <select name="table_id" class="form-control" id="table_id">
            <option value="" selected disabled>select</option>
            @foreach ($Tables as $id => $name)
            <option value="{{ $id }}" {{ $id == old('table_id', $Model_Data->table_id) ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#user_type').on('change', function () {
            if ($(this).val() == 2) {
                $('#user_type_data').html($('#type_div_data').html());
            } else {
                $('#user_type_data').html('');
            }
        }).change();
    });
</script>
@endpush
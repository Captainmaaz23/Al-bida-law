@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Create New Team Member 
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('our-team.index') }}">App Users</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Create New
                    </li>
                </ol>
            </nav>               
            <a href="{{ route('our-team.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Please Provide User Details</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('our-team.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('app_users.our-teams.form_field')

                <div id="user_type_data"></div>
            </form>
        </div>
    </div>                    
</div>

{{-- <div id="type_div_data" class="d-none">
    <div class="col-sm-4">
        <label for="table_id">Assign[ed] Table:</label>
    </div>
    <div class="col-sm-8">
        <select name="table_id" class="form-control" id="table_id">
            <option value="">select</option>
            @foreach ($Tables as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div> --}}

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#user_type').on('change', function () {
            if ($(this).val() == 2) {
                $('#user_type_data').html($('#type_div_data').html());
            }
            else {
                $('#user_type_data').html('');
            }
        });
    });
</script>
@endpush

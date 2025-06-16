@extends('layouts.app')

@section('content')
<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Edit Role Details: {{ $Model_Data->name }}</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('roles.index') }}">Roles</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Details</li>
                </ol>
            </nav>               
            <a href="{{ route('roles.index') }}" class="btn btn-dark btn-return">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Edit Role</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('roles.update', $Model_Data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') 

                <div class="row justify-content-center mt-2 mb-2">
                    <div class="col-sm-8">
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label">Name:</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" class="form-control" value="{{ $Model_Data->name }}">
                            </div>
                        </div>

                        <input type="hidden" name="guard_name" value="web">
                        <input type="hidden" name="display_to" value="0">
                        <?php /* ?><div class="row mt-3">
                          <div class="col-sm-4">
                          <label for="display">Display:</label>
                          </div>
                          <div class="col-sm-8">
                          <select name="display_to" class="form-control">
                          <option value="0">For Restaurant Users Only</option>
                          <option value="1">For Admin Users Only</option>
                          </select>

                          </div>
                          </div><?php */ ?>

                        <div class="row mt-3">
                            <div class="col-sm-8 offset-sm-4">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('roles.index') }}" class="btn btn-outline-dark">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div> 


    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Edit Role Permissions</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('permissions_update',$Model_Data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="total_modules" value="<?php echo count($Modules); ?>" />
                <div id="edit_view">
                    <div class="table-responsive">
                        <div class="table-container">
                            @include('roles._modules_table_edit', ['title' => 'Restaurants', 'modules' => $Modules_1])
                            @include('roles._modules_table_edit', ['title' => 'App Users', 'modules' => $Modules_2])
                            @include('roles._modules_table_edit', ['title' => 'Users', 'modules' => $Modules_3])
                            @include('roles._modules_table_edit', ['title' => 'Settings', 'modules' => $Modules_4])
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row m-t-md">
                    <div class="col-sm-4"></div>

                    <div class="col-sm-4" id="edit_buttons">
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-dark">
                            Cancel
                        </a>
                        <br />
                        <br />
                    </div>

                    <div class="col-sm-4"></div>
                </div>

            </form>
        </div>
    </div>                    
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.radioBtn a').on('click', function () {
            const sel = $(this).data('title');
            const tog = $(this).data('toggle');
            $('#' + tog).val(sel);
            
            $('a[data-toggle="' + tog + '"]').removeClass('active').addClass('notActive');
            $('a[data-toggle="' + tog + '"][data-title="' + sel + '"]').removeClass('notActive').addClass('active');
        });
    });
</script>  
@endpush
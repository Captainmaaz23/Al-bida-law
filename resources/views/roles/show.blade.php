@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Role Details: {{ $Model_Data->name }}</h1>
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
                    <li class="breadcrumb-item active" aria-current="page">View Details</li>
                </ol>
            </nav>
            <a href="{{ route('roles.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Role Details</h3>
        </div>
        <div class="block-content">
            @include('roles.show_fields')
        </div>
    </div> 

    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Role Permissions</h3>
        </div>
        <div class="block-content"> 
            <div class="table-responsive"> 
                <div class="table-container">
                    @include('roles._modules_table_show', ['title' => 'Restaurants', 'modules' => $Modules_1])
                    @include('roles._modules_table_show', ['title' => 'App Users', 'modules' => $Modules_2])
                    @include('roles._modules_table_show', ['title' => 'Users', 'modules' => $Modules_3])
                    @include('roles._modules_table_show', ['title' => 'Settings', 'modules' => $Modules_4])                
                </div>
            </div>
        </div>
    </div>                    
</div>

@endsection

@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">User Details</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('users.index') }}">Users</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User Details</li>
                </ol>
            </nav>               
            <a href="{{ route('users.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">User Details</h3>
        </div>
        <div class="block-content">
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Role</label>
                </div>
                <div class="col-md-9">
                    {{ $roleName }}
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>User Name</label>
                </div>
                <div class="col-md-9">
                    {{ $user->name }}
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Email</label>
                </div>
                <div class="col-md-9">
                    {{ $user->email }}
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Phone</label>
                </div>
                <div class="col-md-9">
                    {{ $user->phone }}
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Status</label>
                </div>
                <div class="col-md-9">
                    {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                </div>
            </div>

            @if(Auth::user()->can('users-edit') || Auth::user()->can('all'))
                <div class="row text-right mt-4">
                    <div class="form-group col-sm-12">
                        <a href="{{ route('users.edit', $user->id) }}" class='btn btn-primary'>Edit</a>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>  
            @endif

        </div>
    </div>
</div>
@endsection
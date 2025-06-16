@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Change Password</h1>
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
                    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
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
    @if(session()->has('success'))
        <div class="alert alert-success container">
            <i class="fa fa-check-circle"></i> {{ session()->get('success') }}
        </div>
    @elseif(session()->has('error'))
        <div class="alert alert-danger container">
            <i class="fa fa-exclamation-circle"></i> {{ session()->get('error') }}
        </div>
    @endif

    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Change Password</h3>
        </div>
        <div class="block-content">
            <form class="bg-light rounded px-4 py-2 mb-4 col-md-6" action="{{ route('users.updatePassword') }}" method="POST">
                @csrf
                <div class="my-2">
                    <label for="currentPass">Current Password</label>
                    <input type="password" class="form-control" name="current_password" id="currentPass" required>
                </div>

                <div class="my-2">
                    <label for="newPass">New Password</label>
                    <input type="password" class="form-control" name="new_password" id="newPass" required>
                </div>

                <div class="mt-2 my-3">
                    <input type="submit" value="Change Password" class="btn btn-dark">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
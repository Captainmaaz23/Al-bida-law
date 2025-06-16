@extends('layouts.app')

@section('content')
    
    <div class="bg-body-light">
        <div class="content">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Edit User 
                </h1>
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
                        <li class="breadcrumb-item active" aria-current="page">
                            Edit User Details
                        </li>
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
                <h3 class="block-title">Edit User </h3>
            </div>
            <div class="block-content">
                <form action="{{ route('users.update', $user->id) }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}" />
                    
                    <div class="mt-4 row">
                        <div class="col-md-3">
                            <label for="name">Name</label>
                        </div>
                        <div class="col-md-9">
                            <input id="name" class="form-control" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus />
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 row">
                        <div class="col-md-3">
                            <label for="email">Email</label>
                        </div>
                        <div class="col-md-9">
                            <input id="email" readonly class="form-control" type="email" name="email" value="{{ $user->email }}" required />
                        </div>
                    </div>
                    
                    <div class="mt-4 row">
                        <div class="col-md-3">
                            <label for="phone">Phone</label>
                        </div>
                        <div class="col-md-9">
                            <input id="phone" class="form-control" type="text" name="phone" value="{{ old('phone', $user->phone) }}" />
                            @error('phone')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 row">
                        <div class="col-md-3">
                            <label for="password">Password</label>
                        </div>
                        <div class="col-md-9">
                            <input id="password" class="form-control" type="password" name="password" autocomplete="off" />
                            <small class="text-muted">Leave it empty to keep it unchanged</small>
                            @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="my-4">
                        <input class="btn btn-primary" type="submit" value="Update User">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

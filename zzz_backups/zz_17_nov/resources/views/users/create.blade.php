@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Create New User</h1>
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
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
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
    <div class="row">
        @if (auth()->user()->rest_id == 0)
        <div class="col-sm-6">        
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Create New Admin User</h3>
                </div>
                <div class="block-content">
                    <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
                        @csrf

                        <input type="hidden" name="rest_id" value="0" />
                        
                        <div class="mt-4 row">
                            <div class="col-md-3">
                                <label for="name">Name</label>
                            </div>
                            <div class="col-md-9">
                                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus />
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
                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required />
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4 row">
                            <div class="col-md-3">
                                <label for="phone">Phone</label>
                            </div>
                            <div class="col-md-9">
                                <input id="phone" class="form-control" type="text" name="phone" value="{{ old('phone') }}" />
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
                                <input id="password" class="form-control" type="password" name="password" required />
                                @error('password')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4 row">
                            <div class="col-md-3">
                                <label for="role">Role</label>
                            </div>
                            <div class="col-md-9">
                                <select id="role" class="custom-select" name="role" required>
                                    @foreach($roles as $role)
                                        @if($role->display_to == 1)
                                            <option value="{{$role->name}}">{{$role->name}}</option>
                                        @endif  
                                    @endforeach            
                                </select>
                                @error('role')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4 row">
                            <div class="col-md-3">
                                <label for="status">Status</label>
                            </div>
                            <div class="col-md-9">
                                <select id="status" class="custom-select" name="status" required>
                                    <option value="inactive">Inactive</option>
                                    <option selected value="active">Active</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-3 offset-md-3 text-center">
                                <input class="btn btn-primary" type="submit" value="Create New User">
                            </div>
                            <div class="col-md-3 text-center">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <div class="col-sm-6">        
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Create New Restaurant User</h3>
                </div>
                <div class="block-content">
                    <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
                        @csrf

                        @if(Auth::user()->rest_id==0)
                        <div class="row">
                            <div class="col-md-3">
                                <label for="rest_id">Restaurant:</label>
                            </div>
                            <div class="col-md-9">
                                <select name="rest_id" class="form-control js-select2 form-select rest_select2" required>
                                    <option value="" selected disabled>select</option>
                                    @foreach ($restaurants_array as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @else
                            <input type="hidden" name="rest_id" value="{{ Auth::user()->rest_id }}" />
                        @endif
                        
                        <div class="mt-4 row">
                            <div class="col-md-3">
                                <label for="name">Name</label>
                            </div>
                            <div class="col-md-9">
                                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus />
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
                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required />
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4 row">
                            <div class="col-md-3">
                                <label for="phone">Phone</label>
                            </div>
                            <div class="col-md-9">
                                <input id="phone" class="form-control" type="text" name="phone" value="{{ old('phone') }}"/>
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
                                <input id="password" class="form-control" type="password" name="password" required />
                                @error('password')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4 row">
                            <div class="col-md-3">
                                <label for="role">Role</label>
                            </div>
                            <div class="col-md-9">
                                <select id="role" class="custom-select" name="role" required>
                                    @foreach($roles as $role)
                                        @if($role->display_to == 1)
                                            <option value="{{$role->name}}">{{$role->name}}</option>
                                        @endif  
                                    @endforeach            
                                </select>
                                @error('role')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4 row">
                            <div class="col-md-3">
                                <label for="status">Status</label>
                            </div>
                            <div class="col-md-9">
                                <select id="status" class="custom-select" name="status" required>
                                    <option value="inactive">Inactive</option>
                                    <option selected value="active">Active</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-3 offset-md-3 text-center">
                                <input class="btn btn-primary" type="submit" value="Create New User">
                            </div>
                            <div class="col-md-3 text-center">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
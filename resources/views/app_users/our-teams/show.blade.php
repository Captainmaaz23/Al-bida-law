@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Team Details</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('our-team.index') }}">Team</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Team Details</li>
                </ol>
            </nav>               
            <a href="{{ route('our-team.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Team
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Team Details</h3>
        </div>
        <div class="block-content">
            
            <div class="row">
                <div class="col-6">
                    <div class="mt-4 row">
                        <div class="col-md-3">
                            <label>Name: </label>
                        </div>
                        <div class="col-md-9">
                            {{ $team->name }}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mt-4 row">
                        <div class="col-md-3">
                            <label>Image</label>
                        </div>
                        <div class="col-md-9">
                            <img src="{{ url('public/uploads/our-teams/'.$team->image) }}" alt="" style="width: 150px; height: auto;">
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mt-4 row">
                        <div class="col-md-3">
                            <label>Title: </label>
                        </div>
                        <div class="col-md-9">
                            {{ $team->title }}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mt-4 row">
                        <div class="col-md-3">
                            <label>Bio</label>
                        </div>
                        <div class="col-md-9">
                            {{ $team->bio }}
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="row">
                    <div class="col-6">
                        <div class="mt-4 row">
                            <div class="col-md-3">
                                <label>Position</label>
                            </div>
                            <div class="col-md-9">
                                {{ $team->position }}
                            </div>
                            
                        </div>
                    </div>
            </div>
        
            

            @if(Auth::user()->can('users-edit') || Auth::user()->can('all'))
                <div class="row text-right mt-4">
                    <div class="form-group col-sm-12">
                        <a href="{{ route('our-team.edit', $team->id) }}" class='btn btn-primary'>Edit</a>
                        <a href="{{ route('our-team.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>  
            @endif

        </div>
    </div>
</div>
@endsection
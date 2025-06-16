@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Founder Details</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('founder.index') }}">Founder</a>
                    </li>
                    <li class="breadcrumb-item active d-none" aria-current="page">Edit Founder Detail</li>
                </ol>
            </nav>               
            <a href="{{ route('founder.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return 
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Case Study Details</h3>
        </div>
        <div class="block-content">
            
            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-5">
                        <label>Founder Name <small>English</small> </label>
                    </div>
                    <div class="col-md-9">
                        {{ $founder->founder_name }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-md-5">
                        <label>Founder Name <small>Arabic</small> </label>
                    </div>
                    <div class="col-md-9">
                        {{ $founder->arabic_name }}
                    </div>
                </div>                
            </div>
            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-4">
                        <label> Designation : <small>English</small> </label>
                    </div>
                    <div class="col-md-9">
                        {{ $founder->designation }}
                    </div>
                </div>

                <div class="col-6">
                    <div class="col-md-4">
                        <label> Designation : <small>Arabic</small> </label>
                    </div>
                    <div class="col-md-9">
                        {{ $founder->arabic_designation }}
                    </div>
                </div>
            </div>

            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-3">
                        <label>Message <small>English</small> </label>
                    </div>
                    <div class="col-md-9">
                        {{ $founder->message }}
                    </div>
                </div>

                <div class="col-6">
                    <div class="col-md-4">
                        <label>Message : <small>Arabic</small> </label>
                    </div>
                    <div class="col-md-9">
                        {{ $founder->arabic_message }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6 mt-4">
                    <div class="col-md-6">
                        <label>Image</label>
                    </div>
                    <div class="col-md-9">
                        <img src="{{ url('public/uploads/founders/'.$founder->image) }}" alt="" style="width: 150px; height: auto;">
                    </div>
                </div>
            </div>
            
            

            @if(Auth::user()->can('users-edit') || Auth::user()->can('all'))
                <div class="row text-right mt-4">
                    <div class="form-group col-sm-12">
                        <a href="{{ route('founder.edit', $founder->id) }}" class='btn btn-primary'>Edit</a>
                        <a href="{{ route('founder.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>  
            @endif

        </div>
    </div>
</div>
@endsection
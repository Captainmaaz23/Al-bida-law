@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Services Details</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('law-services.index') }}">Services</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Services Details</li>
                </ol>
            </nav>               
            <a href="{{ route('law-services.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Services Details</h3>
        </div>
        <div class="block-content">
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Title English</label>
                </div>
                <div class="col-md-9">
                    {{ $services->name }}
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Title Arabic</label>
                </div>
                <div class="col-md-9">
                    {{ $services->title_arabic ?? '' }}

                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>English Description</label>
                </div>
                <div class="col-md-9">
                    {{ $services->description }}
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Arabic Description</label>
                </div>
                <div class="col-md-9">
                    {{ $services->arabic_description ?? '' }}
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Image</label>
                </div>
                <div class="col-md-9">
                    <img src="{{ url('public/uploads/services/'.$services->image) }}" alt="" style="width: 150px; height: auto;">
                </div>
                
            </div>
            

            @if(Auth::user()->can('users-edit') || Auth::user()->can('all'))
                <div class="row text-right mt-4">
                    <div class="form-group col-sm-12">
                        <a href="{{ route('law-services.edit', $services->id) }}" class='btn btn-primary'>Edit</a>
                        <a href="{{ route('law-services.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>  
            @endif

        </div>
    </div>
</div>
@endsection
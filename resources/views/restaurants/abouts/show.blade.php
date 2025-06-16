@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">About Details</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('about.index') }}">About</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit About Details</li>
                </ol>
            </nav>               
            <a href="{{ route('about.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return 
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">About Details</h3>
        </div>
        <div class="block-content">
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Title English</label>
                </div>
                <div class="col-md-9">
                    {{ $about->title }}
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Title Arabic</label>
                </div>
                <div class="col-md-9">
                    {{ $about->arabic_title ?? '' }}
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>English Description</label>
                </div>
                <div class="col-md-9">
                    {{ $about->description }}
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Arabic Description</label>
                </div>
                <div class="col-md-9">
                    {{ $about->arabic_description ?? '' }}
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Image</label>
                </div>
                <div class="col-md-9">
                    <img src="{{ url('public/uploads/abouts/'.$about->image) }}" alt="" style="width: 150px; height: auto;">
                </div>
                
            </div>
            

            @if(Auth::user()->can('users-edit') || Auth::user()->can('all'))
                <div class="row text-right mt-4">
                    <div class="form-group col-sm-12">
                        <a href="{{ route('about.edit', $about->id) }}" class='btn btn-primary'>Edit</a>
                        <a href="{{ route('about.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>  
            @endif

        </div>
    </div>
</div>
@endsection
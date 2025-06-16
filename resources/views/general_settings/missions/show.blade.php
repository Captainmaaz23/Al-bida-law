@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Mssion Details</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('mission.index') }}">Mission</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Mission Detail</li>
                </ol>
            </nav>               
            <a href="{{ route('mission.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return 
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Certificate Details</h3>
        </div>
        <div class="block-content">
            
            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-5">
                        <label>Tite English</label>
                    </div>
                    <div class="col-md-9">
                        {{ $certificate->title }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-md-6">
                        <label>Title Arabic</label>
                    </div>
                    <div class="col-md-9">
                        {{ $certificate->arabic_title }}
                    </div>
                </div>
                
            </div>
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label style="font-weight: bold;">الوصف بالعربية</label>
                </div>
                <div class="col-md-9" dir="rtl" style="font-family: 'Arial', sans-serif; text-align: right;">
                    {!! $certificate->arabic_description ?? '' !!}
                </div>
            </div>

            <div class="mt-4 row">
                <div class="col-md-3">
                    <label style="font-weight: bold;">Description</label>
                </div>
                <div class="col-md-9" dir="rtl" style="font-family: 'Arial', sans-serif; text-align: right;">
                    {!! $certificate->description ?? '' !!}
                </div>
            </div>
            

            @if(Auth::user()->can('users-edit') || Auth::user()->can('all'))
                <div class="row text-right mt-4">
                    <div class="form-group col-sm-12">
                        <a href="{{ route('mission.edit', $certificate->id) }}" class='btn btn-primary'>Edit</a>
                        <a href="{{ route('mission.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>  
            @endif

        </div>
    </div>
</div>
@endsection
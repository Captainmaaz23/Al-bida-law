@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Company Details</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('company-detail.index') }}">Company</a>
                    </li>
                    <li class="breadcrumb-item active d-none" aria-current="page">Edit Company Details</li>
                </ol>
            </nav>               
            <a href="{{ route('company-detail.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return 
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Company Details</h3>
        </div>
        <div class="block-content">
            
            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-3">
                        <label>Email</label>
                    </div>
                    <div class="col-md-9">
                        {{ $company->email }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-md-6">
                        <label>Phone Number</label>
                    </div>
                    <div class="col-md-9">
                        {{ $company->phonenumber }}
                    </div>
                </div>
                
            </div>
            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-3">
                        <label>Facebook</label>
                    </div>
                    <div class="col-md-9">
                        {{ $company->snapchat }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-md-3">
                        <label>Instagram</label>
                    </div>
                    <div class="col-md-9">
                        {{ $company->instagram }}
                    </div>
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-3">
                        <label>Twitter</label>
                    </div>
                    <div class="col-md-9">
                        {{ $company->twitter }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-md-3">
                        <label>Youtube</label>
                    </div>
                    <div class="col-md-9">
                        {{ $company->youtube }}
                    </div>
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="col-md-3">
                        <label>Message</label>
                    </div>
                    <div class="col-md-9">
                        {{ $company->address }}
                    </div>
                </div>
                
            </div>
            

            @if(Auth::user()->can('users-edit') || Auth::user()->can('all'))
                <div class="row text-right mt-4">
                    <div class="form-group col-sm-12 d-none">
                        <a href="{{ route('company-detail.edit', $company->id) }}" class='btn btn-primary'>Edit</a>
                        <a href="{{ route('company-detail.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>  
            @endif

        </div>
    </div>
</div>
@endsection
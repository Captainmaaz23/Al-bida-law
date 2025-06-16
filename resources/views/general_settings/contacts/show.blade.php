@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Contact Details</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('contact.index') }}">Contact</a>
                    </li>
                    <li class="breadcrumb-item active d-none" aria-current="page">Edit Slidder Details</li>
                </ol>
            </nav>               
            <a href="{{ route('contact.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Contact
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Contact Details</h3>
        </div>
        <div class="block-content">
            
            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-3">
                        <label>Full Name</label>
                    </div>
                    <div class="col-md-9">
                        {{ $contact->fullname }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-md-3">
                        <label>Email</label>
                    </div>
                    <div class="col-md-9">
                        {{ $contact->fullname }}
                    </div>
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-3">
                        <label>Phone</label>
                    </div>
                    <div class="col-md-9">
                        {{ $contact->phone }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-md-3">
                        <label>City</label>
                    </div>
                    <div class="col-md-9">
                        {{ $contact->city }}
                    </div>
                </div>
            </div>
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="col-md-3">
                        <label>Message</label>
                    </div>
                    <div class="col-md-9">
                        {{ $contact->message }}
                    </div>
                </div>
                
            </div>
            

            @if(Auth::user()->can('users-edit') || Auth::user()->can('all'))
                <div class="row text-right mt-4">
                    <div class="form-group col-sm-12 d-none">
                        <a href="{{ route('contact.edit', $contact->id) }}" class='btn btn-primary'>Edit</a>
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>  
            @endif

        </div>
    </div>
</div>
@endsection
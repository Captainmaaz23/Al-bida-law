@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Create New Service</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('items.index') }}">Service</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>
            <a href="{{ route('law-services.index') }}" class="btn btn-dark btn-return">
                <i class="fa fa-chevron-left mr-2"></i> Return to Service
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Create New Service</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('law-services.store') }}" method="POST" enctype="multipart/form-data" id="myForm">
                @csrf
                @include('restaurants.services.form_field')
            </form>
        </div>
    </div>
</div>

@endsection


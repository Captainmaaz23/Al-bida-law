@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Create New Founder</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('founder.index') }}">Founder Detail</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>
            <a href="{{ route('founder.index') }}" class="btn btn-dark btn-return">
                <i class="fa fa-chevron-left mr-2"></i> Return
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Create New Founder</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('founder.store') }}" method="POST" enctype="multipart/form-data" id="myForm">
                @csrf
                @include('general_settings.founder.form_field')
            </form>
        </div>
    </div>
</div>

@endsection


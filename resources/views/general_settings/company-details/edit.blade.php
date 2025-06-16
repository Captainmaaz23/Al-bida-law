@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Edit Company Details: {{ '' }}
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{route('company-detail.index') }}">Logo</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Company Detail
                    </li>
                </ol>
            </nav>               
            <a href="{{ route('web-logo.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Logo
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">        
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Edit Detail`</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('company-detail.update', $companydetail->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                @include('general_settings.company-details.form_field')

            </form>
        </div>
    </div>                    
</div>
@endsection
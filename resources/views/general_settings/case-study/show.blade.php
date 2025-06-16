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
                        <a class="link-fx" href="{{ route('case-study.index') }}">Case Study</a>
                    </li>
                    <li class="breadcrumb-item active d-none" aria-current="page">Edit Case Study Details</li>
                </ol>
            </nav>               
            <a href="{{ route('case-study.index') }}" class="btn btn-dark btn-return pull-right">
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
                    <div class="col-md-4">
                        <label>English Title</label>
                    </div>
                    <div class="col-md-9">
                        {{ $case_study->title }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-md-4">
                        <label>Arabic Title</label>
                    </div>
                    <div class="col-md-9">
                        {{ $case_study->arabic_title }}
                    </div>
                </div>
            </div>

            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-6">
                        <label>English Attorny:</label>
                    </div>
                    <div class="col-md-9">
                        {{ $case_study->attorny }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-md-6">
                        <label>Arabic Attorny</label>
                    </div>
                    <div class="col-md-9">
                        {{ $case_study->arabic_attorny }}
                    </div>
                </div>
            </div>

            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-6">
                        <label>English Client</label>
                    </div>
                    <div class="col-md-9">
                        {{ $case_study->client }}
                    </div>
                </div>

                <div class="col-6">
                    <div class="col-md-6">
                        <label>Arabic Client</label>
                    </div>
                    <div class="col-md-9">
                        {{ $case_study->arabic_client }}
                    </div>
                </div>
            </div>

            
            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-6">
                        <label>English Decision</label>
                    </div>
                    <div class="col-md-9">
                        {{ $case_study->decision }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-md-6">
                        <label>Arabic Decision</label>
                    </div>
                    <div class="col-md-9">
                        {{ $case_study->arabic_decision }}
                    </div>
                </div>
            </div>

            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-6">
                        <label>Image</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="{{ url('public/uploads/case-study/'.$case_study->image) }}" alt="" style="width: 150px; height: auto;">
                </div>
            </div>

            <div class="mt-4 row">
                <div class="col-6">
                    <div class="col-md-4">
                        <label>Start Date</label>
                    </div>
                    <div class="col-md-9">
                        {{ $case_study->started_date }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-md-3">
                        <label>End Date</label>
                    </div>
                    <div class="col-md-9">
                        {{ $case_study->end_date }}
                    </div>
                </div>
            </div>
            

            @if(Auth::user()->can('users-edit') || Auth::user()->can('all'))
                <div class="row text-right mt-4">
                    <div class="form-group col-sm-12 d-none">
                        <a href="{{ route('case-study.edit', $case_study->id) }}" class='btn btn-primary'>Edit</a>
                        <a href="{{ route('case-study.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>  
            @endif

        </div>
    </div>
</div>
@endsection
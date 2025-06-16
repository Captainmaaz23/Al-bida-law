@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Question Details</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('question.index') }}">Question</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Question Posts</li>
                </ol>
            </nav>               
            <a href="{{ route('blog-post.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Question
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Question Details</h3>
        </div>
        <div class="block-content">
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Question English</label>
                </div>
                <div class="col-md-9">
                    {{ $question->english_question }}
                </div>
            </div>
            <div class="mt-4 row" dir="rtl">
                <div class="col-md-3 text-start">
                    <label style="font-weight: bold;">العنوان بالعربية</label>
                </div>
                <div class="col-md-9 text-end" style="font-family: 'Arial', sans-serif; font-weight:bold">
                    {{ $question->arabic_question ?? '' }}
                </div>
            </div>
            
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>English Description</label>
                </div>
                <div class="col-md-9">
                    {!! $question->english_description !!}
                </div>
            </div>
            
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label style="font-weight: bold;">الوصف بالعربية</label>
                </div>
                <div class="col-md-9" dir="rtl" style="font-family: 'Arial', sans-serif; text-align: right;">
                    {!! $question->arabic_description ?? '' !!}
                </div>
            </div>
            

            @if(Auth::user()->can('users-edit') || Auth::user()->can('all'))
                <div class="row text-right mt-4">
                    <div class="form-group col-sm-12">
                        <a href="{{ route('question.edit', $question->id) }}" class='btn btn-primary'>Edit</a>
                        <a href="{{ route('question.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>  
            @endif

        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Blog Details</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('blog-post.index') }}">Blog</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Blog Posts</li>
                </ol>
            </nav>               
            <a href="{{ route('blog-post.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Blogs
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Blog Details</h3>
        </div>
        <div class="block-content">
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Title English</label>
                </div>
                <div class="col-md-9">
                    {{ $blog->name }}
                </div>
            </div>
            <div class="mt-4 row" dir="rtl">
                <div class="col-md-3 text-start">
                    <label style="font-weight: bold;">العنوان بالعربية</label>
                </div>
                <div class="col-md-9 text-end" style="font-family: 'Arial', sans-serif; font-weight:bold">
                    {{ $blog->arabic_title ?? '' }}
                </div>
            </div>
            
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>English Description</label>
                </div>
                <div class="col-md-9">
                    {!! $blog->description !!}
                </div>
            </div>
            
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label style="font-weight: bold;">الوصف بالعربية</label>
                </div>
                <div class="col-md-9" dir="rtl" style="font-family: 'Arial', sans-serif; text-align: right;">
                    {!! $blog->arabic_description ?? '' !!}
                </div>
            </div>
            
            
            <div class="mt-4 row">
                <div class="col-md-3">
                    <label>Image</label>
                </div>
                <div class="col-md-9">
                    <img src="{{ url('public/uploads/blogs/'.$blog->image) }}" alt="" style="width: 150px; height: auto;">
                </div>
                
            </div>
            

            @if(Auth::user()->can('users-edit') || Auth::user()->can('all'))
                <div class="row text-right mt-4">
                    <div class="form-group col-sm-12">
                        <a href="{{ route('blog-post.edit', $blog->id) }}" class='btn btn-primary'>Edit</a>
                        <a href="{{ route('blog-post.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>  
            @endif

        </div>
    </div>
</div>
@endsection
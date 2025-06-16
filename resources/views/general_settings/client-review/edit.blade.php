@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Edit Client Reviews: {{ '' }}
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{route('client_review.index') }}">Client Review</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Slidder
                    </li>
                </ol>
            </nav>               
            <a href="{{ route('client_review.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return back
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">        
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Edit Table</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('client_review.update', $client_review->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                @include('general_settings.client-review.form_field')

            </form>
        </div>
    </div>                    
</div>
@endsection
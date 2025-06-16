@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Edit Table Details: {{ $Model_Data->title }}
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{route('serve-tables.index') }}">Tables</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Details
                    </li>
                </ol>
            </nav>               
            <a href="{{ route('serve-tables.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
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
            <form action="{{ route('serve-tables.update', $Model_Data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                @include('restaurants.serve_tables.fields')

            </form>
        </div>
    </div>                    
</div>
@endsection
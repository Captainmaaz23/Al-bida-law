@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Edit Case Study Details: {{ '' }}
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{route('founder.index') }}">Case Study</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Slidder
                    </li>
                </ol>
            </nav>               
            <a href="{{ route('founder.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Founder
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
            <form action="{{ route('founder.update', $founder->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                @include('general_settings.founder.form_field')

            </form>
        </div>
    </div>                    
</div>
@endsection
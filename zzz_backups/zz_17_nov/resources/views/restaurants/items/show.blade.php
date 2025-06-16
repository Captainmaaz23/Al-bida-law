@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Item Details: {{ $Model_Data->name }} 
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('items.index') }}">Items</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        View Details
                    </li>
                </ol>
            </nav>               
            <a href="{{ route('items.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Item Details</h3>
        </div>
        <div class="block-content">
            @include('restaurants.items.show_fields')
        </div>
    </div>
</div>

@if($Model_Data->variations > 0)
    @foreach ($variants as $key => $variant)
        <div class="content">           
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Addon {{ $key + 1 }} Details: 
                        {{ isset($variant->type_id) && isset($addon_types[$variant->type_id]) ? $addon_types[$variant->type_id] : '' }}
                    </h3>
                </div>
                <div class="block-content">
                    @include('restaurants.items.show_addons')
                </div>
            </div>
        </div>
    @endforeach
@endif

@endsection
@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a class="link-fx" href="{{ route('orders.index') }}">Order</a>
    </li>
    <li class="breadcrumb-item active">Create</li>
</ol>

<div class="container-fluid">
    @include('flash::message')

    <div class="animated fadeIn">
        <div class="block block-rounded block-themed">
            <div class="block-header">
                <i class="fa fa-plus-square-o fa-lg"></i>
                <strong>Create Order</strong>  <!-- Corrected the closing tag -->
            </div>
            <div class="block-content">
                <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('restaurants.orders.fields')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
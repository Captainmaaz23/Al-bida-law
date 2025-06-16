@extends('layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a class="link-fx" href="{{ route('orders.index') }}">Order</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        @include('flash::message')

        <div class="block block-rounded block-themed">
            <div class="block-header">
                <i class="fa fa-edit fa-lg"></i>
                <strong>Edit Order</strong>
            </div>
            <div class="block-content">
                <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    @include('restaurants.orders.fields')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
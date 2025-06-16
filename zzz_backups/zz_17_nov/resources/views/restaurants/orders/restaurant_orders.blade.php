@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Restaurant: {{ $Model_Data[0]->r_name }}
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('orders.index') }}">Orders</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('orders.index') }}">Details</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        View Details
                    </li>
                </ol>
            </nav>               
            <a href="{{ route('orders.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content"> 

    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Restaurant Details</h3>
        </div>
        <div class="block-content">
            <div class="row form-group">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="name">Name [EN]:</label>
                            <p>{{ $Model_Data[0]->r_name }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label for="name">Name [AR]:</label>
                            <p>{{ $Model_Data[0]->r_a_name }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="phone">Phone:</label>
                            <p>{{ $Model_Data[0]->r_phone }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label for="email">Email:</label>
                            <p>{{ $Model_Data[0]->r_emai }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="location">Location:</label>
                            <p>{{ $Model_Data[0]->r_location }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label for="website">Website:</label>
                            <p>{{ $Model_Data[0]->r_website }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="order_count">Total Order:</label>
                            <p>{{ $order_count }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Order Details</h3>
                </div>
                <div class="block-content">
                    <div class="table-responsive">
                        <div class="table-container">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th>#</th>  
                                        <th>Order ID</th>  
                                        <th>User</th> 
                                        <th>Promo</th>
                                        <th>Promo Value</th>                 
                                        <th>Order Value</th>
                                        <th>Total Value</th>
                                        <th>Order Date</th>       
                                        <th>Action</th>       
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_order_val = 0;
                                    @endphp
                                    @foreach($Model_Data as $index => $record)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $record->o_id }}</td>
                                        <td>{{ $record->u_name }}</td>
                                        <td>{{ $record->p_id ? $record->p_name : 'N/A' }}</td>
                                        <td>${{ $record->p_id ? $record->promo_value : 0 }}</td>
                                        <td>${{ $record->order_value }}</td>
                                        <td>${{ $record->total_value }}</td>
                                        <td>{{ $record->created_at }}</td>
                                        <td>
                                            <a class="btn btn-outline-primary" href="{{ route('order-user-orders', [$record->u_id, $record->r_id]) }}" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @php
                                        $total_order_val += $record->total_value;
                                    @endphp
                                    @endforeach
                                    <tr class="bg-muted text-light">
                                        <td colspan="6" class="text-right">Total:</td>
                                        <td>${{ $total_order_val }}</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Order Addons</h3>
                </div>
                <div class="block-content">
                    @include('restaurants.orders.show_order_details')
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
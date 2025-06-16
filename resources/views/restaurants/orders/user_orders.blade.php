@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                User  :  {{ $Model_Data[0]->u_name }} 
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
                        <a class="link-fx" href="#">Details</a>
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
            <h3 class="block-title">User Details</h3>
        </div>
        <div class="block-content">
            <div class="row form-group">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="name">Name:</label>
                        </div>
                        <div class="col-sm-8">
                            <p>{{ $Model_Data[0]->u_name }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="phone">Phone:</label>
                        </div>
                        <div class="col-sm-8">
                            <p>{{ $Model_Data[0]->u_phone }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="email">Email:</label>
                        </div>
                        <div class="col-sm-8">
                            <p>{{ $Model_Data[0]->u_emai }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="order_count">Total Order:</label>
                        </div>
                        <div class="col-sm-8">
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
                    @if($Model_Data->isNotEmpty())
                        <div class="table-responsive">
                            <div class="table-container">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th>#</th>  
                                            <th>Order ID</th>  
                                            <th>Restaurant</th> 
                                            <th>Promo</th>
                                            <th>Promo Value</th>                 
                                            <th>Order Value</th>
                                            <th>Total Value</th>
                                            <th>Order Date</th>       
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total_order_val = 0; @endphp
                                        @foreach($Model_Data as $index => $record)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $record->o_id }}</td>
                                                <td>{{ $record->r_name }}</td>
                                                <td>{{ $record->p_id ? $record->p_name : 'N/A' }}</td>
                                                <td>${{ $record->p_id ? number_format($record->promo_value, 2) : 0 }}</td>
                                                <td>${{ number_format($record->order_value, 2) }}</td>
                                                <td>${{ number_format($record->total_value, 2) }}</td>
                                                <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                                                @php $total_order_val += $record->total_value; @endphp
                                            </tr>
                                        @endforeach
                                        <tr class="bg-muted text-light">
                                            <td colspan="6" class="text-right">Total =</td>
                                            <td>${{ number_format($total_order_val, 2) }}</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sm-12">
                                No records found
                            </div>
                        </div>
                    @endif
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
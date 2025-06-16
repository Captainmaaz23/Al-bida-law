@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">User Details</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('users.index') }}">Users</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User Details</li>
                </ol>
            </nav>
            <a href="{{ route('users.index') }}" class="btn btn-dark btn-return pull-right">
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

            @foreach ([
                'Company Trading Name' => $user->name,
                'License No.' => $user->license_no,
                'Valid Till' => $user->license_expiry,
                'Business Activities' => $user->activities,
                'Principal Place of Business' => $user->principal_place,
                'Business Address' => $user->address,
                'Manager Name' => $user->name,
                'Email' => $user->email,
                'Phone' => $user->phone,
                'Status' => $user->status ? 'Active' : 'Inactive',
                'Approval Status' => $user->approval_status == 1 ? 'Approved' : ($user->approval_status == 2 ? 'Rejected' : 'Pending'),
                'Address' => $user->address,
                'Lat, Lng:' => "{$user->lat}, {$user->lng}",
            ] as $label => $value)
                <div class="mt-4 row">
                    <div class="col-md-3"><label>{{ $label }}</label></div>
                    <div class="col-md-9">{{ $value }}</div>
                </div>
            @endforeach

            <div class="mt-4 row">
                <div class="col-md-12">
                    <div id="mymap" style="width:100%; height:400px; border:1px solid; margin-bottom: 15px"></div>
                </div>
            </div>

            <div class="row text-right mt-4">
                <div class="form-group col-sm-12">
                    @if ($user->approval_status == 0)
                        <a href="{{ route('users.approve', ['user_id' => $user->id]) }}" class="btn btn-success">Approve</a>
                        <a href="{{ route('users.reject', ['user_id' => $user->id]) }}" class="btn btn-danger">Reject</a>
                    @endif
                    <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>

<script>
    var lat = {{ $user->lat ?? -34.397 }};
    var lng = {{ $user->lng ?? 150.644 }};
    
    function initMap() {
        const map = new google.maps.Map(document.getElementById("mymap"), {
            zoom: 13,
            center: { lat: lat, lng: lng },
        });
        new google.maps.Marker({
            map: map,
            position: { lat: lat, lng: lng },
        });
    }

    google.maps.event.addDomListener(window, "load", initMap);
</script>
@endsection

@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Create New Restaurant</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('restaurants.index') }}">Restaurants</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>               
            <a href="{{ route('restaurants.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">
    <form action="{{ route('restaurants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="block block-rounded block-themed">
            <div class="block-header">
                <h3 class="block-title">Create New Restaurant</h3>
            </div>
            <div class="block-content">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @include('restaurants.fields')
            </div>
        </div>

        <div class="block block-rounded block-themed">
            <div class="block-header">
                <h3 class="block-title">Location</h3>
            </div>
            <div class="block-content pb-4">
                @include('restaurants.locations')
            </div>
        </div>

        <div class="block block-rounded block-themed">
            <div class="block-header">
                <h3 class="block-title">Social Media</h3>
            </div>
            <div class="block-content pb-4">
                @include('restaurants.social_media')
            </div>
        </div>

        <div class="block block-rounded block-themed">
            <div class="block-header">
                <h3 class="block-title">Working Hours</h3>
            </div>
            <div class="block-content">
                @include('restaurants.working_hours')
                <div class="mt-4 row">
                    <div class="col-sm-12 text-right pb-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('restaurants.index') }}" class="btn btn-outline-dark">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.radioBtn a').on('click', function () {
            const sel = $(this).data('title');
            const tog = $(this).data('toggle');
            $('#is_' + tog).prop('value', sel);

            $('a[data-toggle="' + tog + '"]').not('[data-title="' + sel + '"]').removeClass('active').addClass('notActive');
            $('a[data-toggle="' + tog + '"][data-title="' + sel + '"]').removeClass('notActive').addClass('active');

            const from_div = tog + '_from_div';
            const to_div = tog + '_to_div';

            if (sel == 1) {
                $('.' + from_div).removeClass('hide');
                $('.' + to_div).removeClass('hide');
            } else {
                $('.' + from_div).addClass('hide');
                $('.' + to_div).addClass('hide');
            }
        });

        $(".available_from").change(function () {
            check_time_from($(this).index());
        });

        $(".available_to").change(function () {
            check_time_to($(this).index());
        });
    });

    function check_time_from(ind) {
        const obj_from = $(".available_from").eq(ind);
        const obj_to = $(".available_to").eq(ind);
        let from = parseInt(obj_from.val());
        let to = parseInt(obj_to.val());

        if (from >= to && from < 47) {
            to = (from + 1);
            obj_to.val(to);
        } else if (from >= to && from === 47) {
            from = (from - 1);
            obj_from.val(from);
            to = (from + 1);
            obj_to.val(to);
        } else if (from === to) {
            to = (from + 1);
            obj_to.val(to);
        }
    }

    function check_time_to(ind) {
        const obj_from = $(".available_from").eq(ind);
        const obj_to = $(".available_to").eq(ind);
        let from = parseInt(obj_from.val());
        let to = parseInt(obj_to.val());

        if (from >= to && to === 0) {
            obj_from.val(0);
            obj_to.val(1);
        } else if (from >= to && to > 0 && to < 47) {
            from = (to - 1);
            obj_from.val(from);
        } else if (from === to && to === 47) {
            obj_from.val(to - 1);
        } else if (from === to) {
            to = (to + 1);
            obj_to.val(to);
        }
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>

<script>
    function initMap() {
        const map = new google.maps.Map(document.getElementById("mymap"), {
            zoom: 8,
            center: {lat: -34.397, lng: 150.644},
        });
        const infoWindow = new google.maps.InfoWindow;
        const geocoder = new google.maps.Geocoder();

        document.getElementById("searchmap").addEventListener("change", () => {
            geocodeAddress(geocoder, map);
        });

        google.maps.event.addListener(map, "click", function (e) {
            const latLng = e.latLng;
            $("#lat").val(latLng.lat());
            $("#lng").val(latLng.lng());

            if (marker && marker.setMap) {
                marker.setMap(null);
            }
            marker = new google.maps.Marker({
                position: latLng,
                map: map
            });
            infoWindow.setPosition(latLng);
            infoWindow.setContent('You have selected:<br>Lat: ' + latLng.lat() + '<br>Long: ' + latLng.lng());
            infoWindow.open(map, marker);
        });
    }

    function geocodeAddress(geocoder, resultsMap) {
        const address = document.getElementById("searchmap").value;
        geocoder.geocode({address: address}, (results, status) => {
            if (status === "OK") {
                resultsMap.setCenter(results[0].geometry.location);
                new google.maps.Marker({
                    map: resultsMap,
                    position: results[0].geometry.location,
                });
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
    }

    initMap();
</script>
@endpush
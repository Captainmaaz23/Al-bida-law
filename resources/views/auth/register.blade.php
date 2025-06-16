@extends('layouts.guest')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-12 col-lg-10 col-xl-8">
            <div class="content">
                <div class="block block-rounded block-themed">

                    <div class="block-header">
                        <h3 class="block-title">Register As Seller</h3>
                    </div>

                    <div class="block-content">
                        @include('flash::message')
                        <form action="{{ url('seller-signup') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="company_name">Seller Trading Name</label>
                                    <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name')}}" autofocus required="">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="license_no">License No.</label>
                                    <input type="text" id="license_no" name="license_no" class="form-control" value="{{ old('license_no')}}" required="">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="license_expiry">Valid Till</label>
                                    <input type="text" id="license_expiry" name="license_expiry" class="form-control" value="{{ old('license_expiry')}}" required="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="activities" class="d-block">Business Activities</label>
                                    <textarea id="activities" name="activities" class="form-control" rows="5">{{ old('activities')}}</textarea>

                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="principal_place">Principal Place of Business</label>
                                    <input type="text" id="principal_place" name="principal_place" class="form-control" value="{{ old('principal_place')}}" required="">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="address">Business Address</label>
                                    <input type="text" id="searchmap" name="address" class="form-control" value="{{ old('address')}}" required="">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <div id="map" style="width:100%; height:400px; border:1px solid"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="lat">Latitude</label>
                                    <input type="text" name="latitude" class="form-control" id="lat" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="lng">Longitude</label>
                                    <input type="text" name="longitude" class="form-control" id="lng" required>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="name">Manager Name</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name')}}" required="">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="phone">Contact Number</label>
                                    <input type="phone" id="phone" name="phone" class="form-control form-control-lg iti-phone" value="{{ old('phone')}}" autocomplete="off" required="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email')}}" required="">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="password" class="d-block">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" required="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="website">Website</label>
                                    <input type="text" id="website" name="website" class="form-control" value="{{ old('website')}}" required="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="d-block">Terms & Conditions</label>
                                    <textarea class="form-control" rows="5" readonly="true">{{$app_page->description}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="agree" class="custom-control-input" id="agree" required>
                                    <label class="custom-control-label" for="agree">I agree with the terms and conditions</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    Register
                                </button>
                            </div>

                        </form>

                        <div class="mb-4 text-muted text-center">
                            Already Registered? <a href="{{ url('/login') }}">Login</a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="{{ asset('assets/build/css/intlTelInput.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tel.css') }}">

<?php $site = ''; ?>
<script src="<?php echo $site; ?>//maps.google.com/maps/api/js?key=AIzaSyCD8bY_JqPR9R6H-PaCp06DMc1dpyFaFbg&libraries=places"></script>
<script src="<?php echo $site; ?>//cdn.rawgit.com/mahnunchik/markerclustererplus/master/dist/markerclusterer.min.js"></script>
<script src='<?php echo $site; ?>//cdn.rawgit.com/printercu/google-maps-utility-library-v3-read-only/master/infobox/src/infobox_packed.js' type='text/javascript'></script>
<?php
$segment1 = Request::segment(3);
$location = null;
$lat = -34.397;
$lng = 150.644;
?>

<script>
var geocoder;
var map, infoWindow;
var marker;
var mycenter = {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>};
var map = new google.maps.Map(document.getElementById("map"), {
center: mycenter,
zoom: 13
});
function initMap() {
new google.maps.Marker({
    map: map,
    position: mycenter,
});
infoWindow = new google.maps.InfoWindow;
const geocoder = new google.maps.Geocoder();


bind_autocomplete_search();


google.maps.event.addListener(map, "click", function (e) {
    latLng = e.latLng;

    console.log(latLng);

    $("#lat").val(e.latLng.lat());
    $("#lng").val(e.latLng.lng());
    
    if (marker && marker.setMap) {
        marker.setMap(null);
    }
    marker = new google.maps.Marker({
        position: latLng,
        map: map
    });
    infoWindow.setPosition(latLng);
    infoWindow.setContent('You have selected:<br>Lat: ' + e.latLng.lat() + '<br>Long: ' + e.latLng.lng());
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
    }
    else {
        alert("Geocode was not successful for the following reason: " + status);
    }
});
}


initMap();

function bind_autocomplete_search() {
var input = document.getElementById('searchmap');
var autocomplete = new google.maps.places.Autocomplete(input);
autocomplete.bindTo("bounds", map);

autocomplete.addListener("place_changed", function () {
    var place = autocomplete.getPlace();

    latLng = place.geometry.location;

    $("#lat").val(place.geometry.location.lat());
    $("#lng").val(place.geometry.location.lng());


    if (marker && marker.setMap) {
        marker.setMap(null);
    }
    marker = new google.maps.Marker({
        position: latLng,
        map: map
    });
    infoWindow.setPosition(latLng);
    infoWindow.setContent('You have selected:<br>Lat: ' + place.geometry.location.lat() + '<br>Long: ' + place.geometry.location.lng());
    infoWindow.open(map, marker);



    var request = {
        placeId: place.place_id,
        fields: ['opening_hours']
    };

    service = new google.maps.places.PlacesService(map);

    service.getDetails(request, function (place, status) {
        localStorage.setItem("place_working_hours", JSON.stringify(new GoogleWorkingHoursPeriod(place.opening_hours.periods)));
    });
    map.panTo(place.geometry.location);
    map.setZoom(13);
});
}
</script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@2.1.16/dist/js/coreui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.js"></script>
<script src="{{ asset('assets/build/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('assets/build/js/intlTelInput-jquery.min.js') }}"></script>

<script>

var itiPhone = $(".iti-phone");

itiPhone.intlTelInput({
separateDialCode: false,
initialCountry: "auto",
nationalMode: false,
utilsScript: "{{ asset('assets/build/js/utils.js') }}",
geoIpLookup: function (success) {
    // Get your api-key at https://ipdata.co/
    fetch("https://api.ipdata.co/?api-key=1f9ecc1670c915b3ddd397d233297968ccf720c0861abf9ecac1a8ef")
            .then(function (response) {
                if (!response.ok)
                    return success("");
                return response.json();
            })
            .then(function (ipdata) {
                success(ipdata.country_code);
            });
},
});





function AllowOnlyNumbers(e) {

e = (e) ? e : window.event;
var clipboardData = e.clipboardData ? e.clipboardData : window.clipboardData;
var key = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
var str = (e.type && e.type == "paste") ? clipboardData.getData('Text') : String.fromCharCode(key);

return (/^\d+$/.test(str));
}

</script>

@endsection
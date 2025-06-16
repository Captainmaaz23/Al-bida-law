<?php
$segment1 = Request::segment(3);
$location = $Model_Data->location ?? null;
$lat = $Model_Data->lat ?? -34.397; // Default latitude
$lng = $Model_Data->lng ?? 150.644; // Default longitude
?>

<div class="row">
    <div class="form-group col-sm-6">
        <label for="location">Location:</label>
        <input type="text" name="location" value="{{ $location }}" placeholder="" class="form-control" required id="principal_place">
    </div>
</div>

<div class="form-group">
    <label for="wizard-progress-map">Locate Restaurant on Map</label>
    <input type="text" class="form-control col-sm-6 mb-3" id="searchmap" value="{{ $location }}">
    <div id="map" style="width:100%; height:400px; border:1px solid;"></div>
</div>

<div class="form-group">
    <label for="latitude">Latitude</label>
    <input type="text" name="lat" id="lat" value="{{ $lat }}">
    <label for="longitude">Longitude</label>
    <input type="text" name="lng" id="lng" value="{{ $lng }}">
</div>

<div class="mt-4 row pb-4">
    <div class="col-sm-12 text-right">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-outline-dark">Cancel</a>
    </div>
</div>

<script src="//maps.google.com/maps/api/js?key=AIzaSyCD8bY_JqPR9R6H-PaCp06DMc1dpyFaFbg&libraries=places"></script>
<script src="//cdn.rawgit.com/mahnunchik/markerclustererplus/master/dist/markerclusterer.min.js"></script>
<script src="//cdn.rawgit.com/printercu/google-maps-utility-library-v3-read-only/master/infobox/src/infobox_packed.js"></script>

<script>
let geocoder, map, infoWindow, marker;
const mycenter = { lat: <?= $lat ?>, lng: <?= $lng ?> };

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: mycenter,
        zoom: 13
    });

    marker = new google.maps.Marker({
        map: map,
        position: mycenter,
    });

    infoWindow = new google.maps.InfoWindow();

    bindAutocompleteSearch();

    google.maps.event.addListener(map, "click", function (e) {
        const latLng = e.latLng;

        $("#lat").val(latLng.lat());
        $("#lng").val(latLng.lng());

        if (marker) {
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

function bindAutocompleteSearch() {
    const input = document.getElementById('searchmap');
    const autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo("bounds", map);

    autocomplete.addListener("place_changed", function () {
        const place = autocomplete.getPlace();
        const latLng = place.geometry.location;

        $("#lat").val(latLng.lat());
        $("#lng").val(latLng.lng());

        if (marker) {
            marker.setMap(null);
        }

        marker = new google.maps.Marker({
            position: latLng,
            map: map
        });

        infoWindow.setPosition(latLng);
        infoWindow.setContent('You have selected:<br>Lat: ' + latLng.lat() + '<br>Long: ' + latLng.lng());
        infoWindow.open(map, marker);

        map.panTo(latLng);
        map.setZoom(13);

        const request = {
            placeId: place.place_id,
            fields: ['opening_hours']
        };

        const service = new google.maps.places.PlacesService(map);
        service.getDetails(request, function (place, status) {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                localStorage.setItem("place_working_hours", JSON.stringify(new GoogleWorkingHoursPeriod(place.opening_hours.periods)));
            }
        });
    });
}

initMap();
</script>
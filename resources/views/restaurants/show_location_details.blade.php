<div class="col-md-12">
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="address">Address:</label>
        </div>
        <div class="col-md-8">
            <p>{{ $Model_Data->location }}</p>
        </div>
    </div>

    @if(isset($Model_Data->lat) && isset($Model_Data->lng))
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="lat">Lat, Lng:</label>
            </div>
            <div class="col-md-8">
                <p>{{ $Model_Data->lat }}, {{ $Model_Data->lng }}</p>
            </div>
        </div>
        <div id="mymap" style="width:100%; height:400px; border:1px solid; margin-bottom: 15px"></div>
    @endif
</div>

<script>
    @if(isset($Model_Data->lat) && isset($Model_Data->lng))
        function initMap() {
            var location = { lat: {{ $Model_Data->lat }}, lng: {{ $Model_Data->lng }} };
            var map = new google.maps.Map(document.getElementById('mymap'), {
                zoom: 15,
                center: location
            });
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }
    @endif
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
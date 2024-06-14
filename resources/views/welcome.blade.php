<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <div hidden>
        <meta name="csrf-token" id="csrf-token" content="@csrf">
    </div>
    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

</head>

<body class="antialiased">
    <div id="app">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12 m12 l12">
                        <p class="blue-text">
                            <strong><em><b>Mapa</b></em></strong>
                        </p>
                        <a class="waves-effect waves-light btn" v-on:click="running()">GetData</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-content">
                <div id="map" class="video-container">
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="{{ asset('js/vue/vue.js') }}"></script>
<script src="{{ asset('js/vue-resource/dist/vue-resource.js') }}"></script>
<script src="{{ asset('js/vue/vue-config.js') }}"></script>
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXN-VNUF49c75Mj5su3R_SS80OtQd-RF4&libraries=places&callback=app.initMap">
</script>
<script>
    var app = new Vue({

        el: "#app",
        data: {
            map: '',
            marker: [],
            myLatLng: '',
            contentString: ''
        },
        methods: {
            initMap: function() {
                var environment = this;
                environment.default_location = new google.maps.LatLng(4.632702, -74.112594);
                environment.map = new google.maps.Map(document.getElementById("map"), {
                    center: environment.default_location,
                    zoom: 14,
                });
            },
            clearMarkers: function() {
                for (var i = 0; i < this.marker.length; i++) {
                    this.marker[i].setMap(null);
                }
                this.marker = [];
            },
            running: function() {
                this.$http.post('/location-searching-data').then(response => {
                    console.log(response.body);
                    if (response.body.data.length > 0) {
                        this.clearMarkers();
                        for (let index = 0; index < response.body.data.length; index++) {
                            this.buildMap(response.body.data[index]);
                        }
                    } else {
                        return;
                    }
                }, response => {

                });
            },
            buildMap: function(data) {
                console.log(data);
                var environment = this;
                this.myLatLng = {
                    lat: parseFloat(data.latitud),
                    lng: parseFloat(data.longitud)
                };
                this.contentString = '<div>' +
                    '<blockquote><div>' +
                    '<span class="uppercase blue-text"><em><b>Dispositivo: ' + data.dispositivo +
                    '</b></em></span><br>' +
                    '<span class="uppercase blue-text"><em><b>Placa: ' + data.placa +
                    '</b></em></span><br>' +
                    '<span class="uppercase blue-text"><em><b>Version: ' + data.version +
                    '</b></em></span><br>' +
                    '</div>' +
                    '</div></blockquote>';
                var infowindow = new google.maps.InfoWindow({
                    content: this.contentString
                });
                var markers = new google.maps.Marker({
                    position: this.myLatLng,
                    map: this.map,
                    animation: google.maps.Animation.DROP
                });
                markers.addListener('click', function() {
                    infowindow.open(map, markers);
                });
                this.marker.push(markers);
            }
        },
        mounted() {
        }
    });
</script>
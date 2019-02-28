var markers = [];
var lat = $('#long_lat').data('lat');
var long = $('#long_lat').data('long');
$(function() {
    initMap(myLatlng);
});
if(!lat || !long || lat==0 || long==0){
    var myLatlng = {
        lat:31.817797156213604, lng:117.22220727680337
    }
}
function initMap(myLatlng) {
   document.getElementById('map_tips').innerHTML = ('<span style="color: red;">鼠标右击地图任意位置即可自动填充坐标</span>');
   if(!myLatlng){
       map = new google.maps.Map(document.getElementById('cmmap'), {
           center: {lat: parseFloat(lat), lng: parseFloat(long)},
           zoom: 12
       });
   }else{
       map = new google.maps.Map(document.getElementById('cmmap'), {
           center: {lat: parseFloat(myLatlng.lat), lng: parseFloat(myLatlng.lng)},
           zoom: 15
       });
   }

    $('#modal-table').hide();
    var infoWindow = new google.maps.InfoWindow({map: map});
    // 定位
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            if(pos.lat && pos.lng){
                infoWindow.setPosition(pos);
                infoWindow.setContent('Location found.');
                map.setCenter(pos);
            }
        }, function () {
            handleLocationError(true, infoWindow, map.getCenter());

        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }

    var marker = new google.maps.Marker({
        position: {lat: lat, lng: long},
        map: map,
        title: 'your place!'
    });
    markers.push(marker);
    google.maps.event.addListener(map, "rightclick", function (event) {
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();
        // 经纬度
        // alert("Lat=" + lat + "; Lng=" + lng);
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
         marker = new google.maps.Marker({
            position: {lat: lat, lng: lng},
            map: map,
            title: 'your place!'
        });
        markers.push(marker);
        $('#long_lat').val(lng + ',' + lat);
        alert('设置成功！');
    });



    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesn\'t support geolocation.');
    }

    //搜索
    var service = new google.maps.places.PlacesService(map);

    $('#map-search').submit(function (e) {
        e.preventDefault();
        $('#map-keyword').val($.trim($('#map-keyword').val()));
        if ($('#map-keyword').val().length > 0) {
            performSearch($('#map-keyword').val());
        }
        return false;
    });


    function performSearch(keyword) {
        var request = {
            bounds: map.getBounds(),
            query: keyword
        };
        service.textSearch(request, callback);
    }

    function callback(results, status) {
        if (status !== google.maps.places.PlacesServiceStatus.OK) {
            console.error(status);
            return;
        }
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }

        for (var i = 0, result; result = results[i]; i++) {
            addMarker(result);
        }
    }

    function addMarker(place) {
        var marker = new google.maps.Marker({
            map: map,
            position: place.geometry.location,
            icon: {
                url: 'https://developers.google.com/maps/documentation/javascript/images/circle.png',
                anchor: new google.maps.Point(10, 10),
                scaledSize: new google.maps.Size(10, 17)
            }
        });
        markers.push(marker);
        google.maps.event.addListener(marker, 'click', function() {
            service.getDetails(place, function(result, status) {
                if (status !== google.maps.places.PlacesServiceStatus.OK) {
                    console.error(status);
                    return;
                }
                infoWindow.setContent(result.name);
                infoWindow.open(map, marker);
            });
        });
    }


}


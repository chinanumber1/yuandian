if(typeof(is_google_map)!="undefined"){
    $(function(){
        var local = null;
        var marker = null;
        var infoWindow = null;
        var search_point =[];
        var infoWindows = [];
        var info_opts = {
            width : 220,
            height: 102,
            title : "",
            enableMessage:false,
            message:""
        };
        $('#around-map').on('mousewheel',function(){
            return false;
        });
        var map = new google.maps.Map(document.getElementById('around-map'), {
            mapTypeControl:false,
            zoom: 14,
            center: {lng:117.2713,lat:31.8381}
        });
        var setPoint = function(mk){
            var pt = mk.getPosition();
            var geocoder = new google.maps.Geocoder;
            if(infoWindow == null){
                infoWindow = new google.maps.InfoWindow;
                infoWindows.push(infoWindow);
            }else{
                for(var kk in infoWindows){
                    infoWindows[kk].close();
                }

            }
            geocoder.geocode({'location': pt}, function(results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        // infoWindow.setContent(results[0].formatted_address);
                        infoWindow.setContent('<div class="infowin-box">位置：<em class="poi-address" style="height:40px;display:block;">'+ results[0].formatted_address +'</em><p style="text-align:center;"><a href="javascript:setSelect(\''+ results[0].formatted_address +'\',\''+results[0].geometry.location.lat()+'\',\''+results[0].geometry.location.lng()+'\');" class="J-show-around-deals btn btn-normal btn-small" hidefocus="true" style="color:black;">查看附近'+group_alias_name+'</a></p></div>');
                        infoWindow.open(map, marker);
                    } else {
                        window.alert('No results found');
                    }
                } else {
                    window.alert('Geocoder failed due to: ' + status);
                }
            });
        };

        map.addListener("click",function(e){
                if(marker == null){
                    marker = new google.maps.Marker({
                        position: {lng:e.latLng.lng(),lat:e.latLng.lat()},
                        map:map
                    });
                }else{
                    marker.setPosition({lng:e.latLng.lng(),lat:e.latLng.lat()});
                }
                setPoint(marker);
        });
        //定位
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(position){
                var pos = {lng:parseFloat(position.coords.longitude),lat:parseFloat(position.coords.latitude)};
            });
            if(typeof(pos)!="undefined"){
                $('#cityLL').val(pos.lng+','+pos.lat);
            }else{
                var pos = {lng:117.2112,lat:31.8546};
                $('#cityLL').val(pos.lng+','+pos.lat);
            }

            var smap = new google.maps.Map(document.getElementById('hidden_map'), {
                center:pos,
                zoom: 15
            });

            var request = {
                location: pos,
                radius: '200'
            };

            service = new google.maps.places.PlacesService(smap);
            service.nearbySearch(request, callback);

            function callback(results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    var result = {name:results[0].name};
                    pos = {lng:results[0].geometry.location.lng(),lat:results[0].geometry.location.lat()};
                    map.setCenter({lng:results[0].geometry.location.lng(),lat:results[0].geometry.location.lat()},12)
                }
            }
        }

        function search(keyword) {
            for(var k in search_point){
                search_point[k].setMap(null);
            }
            var request = {
                location: pos,
                radius: '200',
                query: keyword
            };
            service = new google.maps.places.PlacesService(smap);
            service.textSearch(request, callback);

            function callback(results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    if (results.length > 0) {
                        var result_panel = '<p class="search-number">共有' + results.length + '条结果</p><ol>';
                        for (var i = 0; i < results.length; i++) {
                            result_panel += '<li class="result-item" data-lng="' + results[i].geometry.location.lng() + '" data-lat="' + results[i].geometry.location.lat() + '" data-title="' + results[i].name + '" data-content="' + results[i].formatted_address + '"><span class="icon icon-' + i + '"></span><a class="J-show-around-deals btn-selected" href="javascript:;">查看附近' + group_alias_name + '</a><h3>' + results[i].name + '</h3><p class="desc">地址：' + results[i].formatted_address + '</p></li>';

                            if (i == 0) {
                                map.setCenter({
                                    lng: results[i].geometry.location.lng(),
                                    lat: results[i].geometry.location.lat()
                                }, 15);
                            }
                            var point = {
                                lng: results[i].geometry.location.lng(),
                                lat: results[i].geometry.location.lat()
                            };
                            var search_marker = new google.maps.Marker({
                                position: point,
                                map:map,
                                icon:'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
                            });
                            search_point[i] = search_marker;
                        }
                        result_panel += '</ol>';
                        $('#result-panel').html(result_panel);
                        // var result = {name:results[0].name};
                        // map.setCenter({lng:results[0].geometry.location.lng(),lat:results[0].geometry.location.lng()},12)
                        $.each(search_point,function(i,item){
                            (function(){
                                var index = i;
                                search_point[i].addListener('click', function(){
                                    $('#result-panel .result-item').eq(i).click();
                                });
                            })();
                        });
                    }
                }
            }
        }


            $('#result-panel .result-item').live('mouseover mouseout click', function (e) {
                if (e.type == 'mouseover') {
                    $(this).addClass('selected');
                    var a = $(this).index();
                    $.each($('#result-panel .result-item'), function (i, item) {
                        if (i == a) {
                            search_point[i].setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');

                            search_point[i].setZIndex(3);
                        } else if ($(item).data('is_selected') != 1) {
                            search_point[i].setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');

                            search_point[i].setZIndex(1);
                        }
                    });

                } else if (e.type == 'mouseout') {
                    if ($(this).data('is_selected') != 1) {
                        $(this).removeClass('selected');
                    }
                } else {
                    $('#result-panel .result-item').data('is_selected', 0).removeClass('selected');
                    $(this).data('is_selected', 1).addClass('selected').mouseover();
                    var a = $(this).index();
                    var pt = search_point[a].getPosition();
                    (new google.maps.Geocoder).geocode({'location': pt},  function (results,status) {
                        if (status === 'OK') {
                            for(var index_info in infoWindows){
                                infoWindows[index_info].close();
                            }
                            infoWindow = new google.maps.InfoWindow;
                            infoWindow.setContent('<div class="infowin-box">位置：<em class="poi-address" style="height:40px;display:block;">'+ results[0].formatted_address +'</em><p style="text-align:center;"><a href="javascript:setSelect(\''+ results[0].formatted_address +'\',\''+results[0].geometry.location.lat()+'\',\''+results[0].geometry.location.lng()+'\');" class="J-show-around-deals btn btn-normal btn-small" hidefocus="true" style="color:black;">查看附近'+group_alias_name+'</a></p></div>');
                            infoWindow.open(map, search_point[a]);
                            infoWindows.push(infoWindow);

                        }
                    });
                    map.panTo(search_point[a].getPosition());
                    search_point[a].setZIndex(2);
                }
            });

            $('#aroundForm').submit(function () {
                search($('#aroundQ').val());
                return false;
            });

            $("#aroundQ").keyup(function(){
                search($('#aroundQ').val());
                return false;
            });

            $('.J-show-around-deals').live('click', function (event) {
                var result_item = $(this).closest('.result-item');
                setSelect(result_item.attr('data-title'), result_item.attr('data-lat'), result_item.attr('data-lng'));
                event.stopPropagation();
            });
        });
    function setSelect(adress,lat,lng){
        var exp = new Date();
        exp.setTime(exp.getTime() + 365*24*60*60*1000);
        document.cookie = "around_adress=" + encodeURIComponent(adress) + ";expires=" + exp.toGMTString();
        document.cookie = "around_lat=" + lat + ";expires=" + exp.toGMTString();
        document.cookie = "around_long=" + lng + ";expires=" + exp.toGMTString();
        window.location.href = '/group/around/';
    }
}else{
    $(function(){
        var local = null;
        var marker = null;
        var infoWindow = null;
        var search_point =[];
        var info_opts = {
            width : 220,
            height: 102,
            title : "",
            enableMessage:false,
            message:""
        };
        $('#around-map').on('mousewheel',function(){
            return false;
        });
        var map = new BMap.Map("around-map",{"enableMapClick":false});
        var oPoint = new BMap.Point(116.331398,39.897445);
        var setPoint = function(mk){
            var pt = mk.getPosition();
            (new BMap.Geocoder()).getLocation(pt,function(rs){
                addComp = rs.addressComponents;
                infoWindow = new BMap.InfoWindow('<div class="infowin-box">位置：<em class="poi-address" style="height:40px;display:block;">'+ addComp.city + addComp.district + addComp.street +'</em><p style="text-align:center;"><a href="javascript:setSelect(\''+ addComp.city + addComp.district + addComp.street +'\',\''+pt.lat+'\',\''+pt.lng+'\');" class="J-show-around-deals btn btn-normal btn-small" hidefocus="true" style="color:black;">查看附近'+group_alias_name+'</a></p></div>',info_opts);
                marker.openInfoWindow(infoWindow);
            });
        };

        map.addEventListener("click",function(e){
            if(!e.overlay){
                if(marker == null){
                    marker = new BMap.Marker(new BMap.Point(e.point.lng,e.point.lat),{icon:new BMap.Icon("http://map.baidu.com/image/markers_new.png", new BMap.Size(25, 37), {anchor: new BMap.Size(12,15), imageOffset: new BMap.Size(0,-156)}),enableMassClear:false});
                    //marker.enableDragging();
                    map.addOverlay(marker);
                }else{
                    marker.setPosition(new BMap.Point(e.point.lng,e.point.lat));
                }
                setPoint(marker);
            }
        });

        map.enableScrollWheelZoom();

        map.centerAndZoom(oPoint, 12);
        function myFun(result){
            oPoint = new BMap.Point(result.center['lng'],result.center['lat']);
            map.centerAndZoom(oPoint,12);
        }
        var myCity = new BMap.LocalCity();
        myCity.get(myFun);


        map.addControl(new BMap.NavigationControl());
        map.enableScrollWheelZoom();

        local = new BMap.LocalSearch(map,{
            pageCapacity:10,
            onSearchComplete:function(results){
                search_point = [];
                var search_count = results.getCurrentNumPois();
                if(search_count > 0){
                    var result_panel = '<p class="search-number">共有'+search_count+'条结果</p><ol>';
                    for(var i=0;i<search_count;i++){
                        var now_poi = results.getPoi(i);
                        result_panel += '<li class="result-item" data-lng="'+now_poi.point.lng+'" data-lat="'+now_poi.point.lat+'" data-title="'+now_poi.title+'" data-content="'+now_poi.address+'"><span class="icon icon-'+i+'"></span><a class="J-show-around-deals btn-selected" href="javascript:;">查看附近'+group_alias_name+'</a><h3>'+now_poi.title+'</h3><p class="desc">地址：'+now_poi.address+'</p></li>';

                        if(i == 0){
                            map.centerAndZoom(new BMap.Point(now_poi.point.lng,now_poi.point.lat),15);
                        }

                        var search_marker = new BMap.Marker(new BMap.Point(now_poi.point.lng,now_poi.point.lat),{icon:new BMap.Icon("http://map.baidu.com/image/markers_new.png", new BMap.Size(19, 27), {anchor: new BMap.Size(10,9), imageOffset: new BMap.Size(i*-24,-199)})});
                        search_point[i] = search_marker;
                        var pt = new BMap.Point(now_poi.point.lng,now_poi.point.lat);
                        map.addOverlay(search_marker);
                    }
                    result_panel += '</ol>';
                    $('#result-panel').html(result_panel);

                    $.each(search_point,function(i,item){
                        (function(){
                            var index = i;
                            search_point[i].addEventListener('click', function(){
                                $('#result-panel .result-item').eq(i).click();
                                //var pt = search_point[i].getPosition();
                                // (new BMap.Geocoder()).getLocation(pt,function(rs){
                                // addComp = rs.addressComponents;
                                // infoWindow = new BMap.InfoWindow('<div class="infowin-box">位置：<em class="poi-address">'+ addComp.city + addComp.district + addComp.street +'</em><p style="text-align:center;"><a href="javascript: void(0);" class="J-show-around-deals btn btn-normal btn-small" hidefocus="true" onclick="setSelect(this,'+pt.lat+','+pt.lng+')">查看附近团购</a></p></div>',info_opts);
                                // search_point[i].openInfoWindow(infoWindow);
                                // });
                            });
                        })();
                    });
                }
            }
        });
        var around_ac = new BMap.Autocomplete({
            'input':'aroundQ',
            'location':'合肥',
            onSearchComplete:function(results){

            }
        });
        around_ac.addEventListener("onconfirm", function(e){    //鼠标点击下拉列表后的事件
            map.clearOverlays();
            local.search($('#aroundQ').val());
        });

        $('#result-panel .result-item').live('mouseover mouseout click',function(e){
            if(e.type == 'mouseover'){
                $(this).addClass('selected');
                var a = $(this).index();
                $.each($('#result-panel .result-item'),function(i,item){
                    if(i == a){
                        search_point[i].setIcon(new BMap.Icon("http://map.baidu.com/image/markers_new.png", new BMap.Size(26,36), {anchor: new BMap.Size(14,13), imageOffset: new BMap.Size(i*-34,-73)}));
                        search_point[i].setZIndex(3);
                    }else if($(item).data('is_selected') != 1){
                        search_point[i].setIcon(new BMap.Icon("http://map.baidu.com/image/markers_new.png", new BMap.Size(19, 27), {anchor: new BMap.Size(10,9), imageOffset: new BMap.Size(i*-24,-199)}));
                        search_point[i].setZIndex(1);
                    }
                });

            }else if(e.type == 'mouseout'){
                if($(this).data('is_selected') != 1){
                    $(this).removeClass('selected');
                }
            }else{
                $('#result-panel .result-item').data('is_selected',0).removeClass('selected');
                $(this).data('is_selected',1).addClass('selected').mouseover();
                var a = $(this).index();
                var pt = search_point[a].getPosition();
                (new BMap.Geocoder()).getLocation(pt,function(rs){
                    addComp = rs.addressComponents;
                    infoWindow = new BMap.InfoWindow('<div class="infowin-box">位置：<em class="poi-address">'+ addComp.city + addComp.district + addComp.street +'</em><p style="text-align:center;"><a href="javascript:setSelect(\''+ addComp.city + addComp.district + addComp.street +'\',\''+pt.lat+'\',\''+pt.lng+'\');" class="J-show-around-deals btn btn-normal btn-small" hidefocus="true" style="color:black;">查看附近'+group_alias_name+'</a></p></div>',info_opts);
                    search_point[a].openInfoWindow(infoWindow);
                });
                map.panTo(search_point[a].getPosition());
                search_point[a].setZIndex(2);
            }
        });
        $('#aroundForm').submit(function(){
            map.clearOverlays();
            local.search($('#aroundQ').val());
            return false;
        });

        $('.J-show-around-deals').live('click',function(event){
            var result_item = $(this).closest('.result-item');
            setSelect(result_item.attr('data-title'),result_item.attr('data-lat'),result_item.attr('data-lng'));
            event.stopPropagation();
        });
    });

    function setSelect(adress,lat,lng){
        var exp = new Date();
        exp.setTime(exp.getTime() + 365*24*60*60*1000);
        document.cookie = "around_adress=" + encodeURIComponent(adress) + ";expires=" + exp.toGMTString();
        document.cookie = "around_lat=" + lat + ";expires=" + exp.toGMTString();
        document.cookie = "around_long=" + lng + ";expires=" + exp.toGMTString();
        window.location.href = '/group/around/';
    }
}
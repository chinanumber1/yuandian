$(document).ready(function(){
    common.http('Storestaff&a=shopOrderLogs', {'order_id':urlParam.order_id}, function(data){
        var status = data.order.order_status, isShowMap = data.isShowMap;
        laytpl($('#logTpl').html()).render(data.logList, function(html){
            $('.orders_list ul').html(html);
        });
        if (status > 1 && status < 5 && parseInt(isShowMap) == 1) {
            line();
            $('.refresh').click(function(){
                line();
            });
        } else {
            $('.map').remove();
        }
    });
});

function line()
{
    common.http('Storestaff&a=line', {'order_id':urlParam.order_id, 'noTip':true}, function(response){
        console.log(response);
        if (response.err_code == false) {
            var lines = response.lines, center = response.center, points = response.points;
            var map = new BMap.Map("allmap", {"enableMapClick":false});
            var point = new BMap.Point(center.lng, center.lat);
            var from_point = new BMap.Point(points.from_site.lng, points.from_site.lat);
            var aim_point = new BMap.Point(points.aim_site.lng, points.aim_site.lat);
            map.centerAndZoom(point, 13);
            map.enableScrollWheelZoom();

            var deliverIcon = new BMap.Icon("../storestaff/images/map/deliver_pos.png", new BMap.Size(22,60));
            map.addOverlay(new BMap.Marker(point, {icon:deliverIcon}));

            var storeIcon = new BMap.Icon("../storestaff/images/map/store_pos.png", new BMap.Size(22,60));
            map.addOverlay(new BMap.Marker(from_point, {icon:storeIcon}));
            var userIcon = new BMap.Icon("../storestaff/images/map/my_pos.png", new BMap.Size(60,60));
            map.addOverlay(new BMap.Marker(aim_point, {icon:userIcon}));

            if (lines != null) {
                var polygonArr = [];
                for (var i in lines) {
                    polygonArr.push(new BMap.Point(lines[i].lng, lines[i].lat));
                }
                var polyline = new BMap.Polyline(polygonArr, {strokeColor:"green", strokeWeight:2, strokeOpacity:0.5}); //创建折线
                map.addOverlay(polyline); //增加折线

                if (response.status < 4) {
                    var polyline = new BMap.Polyline([point, from_point], {strokeColor:"red", strokeWeight:2, strokeOpacity:0.5, strokeStyle: 'dashed'}); //创建折线
                    map.addOverlay(polyline);
                } else {
                    var polyline = new BMap.Polyline([point, aim_point], {strokeColor:"red", strokeWeight:2, strokeOpacity:0.5, strokeStyle: 'dashed'}); //创建折线
                    map.addOverlay(polyline);
                }
            } else if (response.status < 4) {
                var polyline = new BMap.Polyline([point, from_point, aim_point], {strokeColor:"red", strokeWeight:2, strokeOpacity:0.5, strokeStyle: 'dashed'}); //创建折线
                map.addOverlay(polyline);
            } else {
                var polyline = new BMap.Polyline([from_point, point, aim_point], {strokeColor:"red", strokeWeight:2, strokeOpacity:0.5, strokeStyle: 'dashed'}); //创建折线
                map.addOverlay(polyline);
            }
        }
    });
}
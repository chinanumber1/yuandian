<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  <style type="text/css">
    body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";font-family:"微软雅黑";}
    #allmap{width:100%;height:500px;}
    p{margin-left:5px; font-size:14px;}
  </style>
  <link rel="stylesheet" type="text/css" href="//apps.bdimg.com/libs/todc-bootstrap/3.1.1-3.2.1/todc-bootstrap.min.css">
  <script src="//apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="//apps.bdimg.com/libs/layer/2.1/layer.js"></script>
  <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
  <title>地图定位</title>
</head>
<body>
    <input type="hidden" name="adress" id="adress"/>
    <if condition="$_GET['lat']">
        <input name="long_lat" id="long_lat" value="{pigcms{$_GET['lng']},{pigcms{$_GET['lat']}" type="hidden"/>
    <else/>
        <input name="long_lat" id="long_lat" value="" type="hidden"/>
    </if>
    <div class="table-header" style="padding-left:10px;">拖动红色图标，经纬度框内将自动填充经纬度。</div>
    <form id="map-search" style="margin:10px;">
        <input id="map-keyword" type="textbox" style="width:300px;border:1px solid #ccc;height:24px;line-height:24px;padding-left:6px;" placeholder="尽量填写城市、区域、街道名"/>
        <input type="submit" value="搜索" class="button" style="margin-left:0px;"/>
    </form>
    <div style="height: 440px; min-height: 300px;" id="cmmap"></div>

    <p style="float: right;margin-right: 10px;"><button class="btn btn-primary" onclick="save()">确定</button></p>
</body>
</html>

<script type="text/javascript">

    function save(){
        var long_lat = $('#long_lat').val().split(',');
        var lng = long_lat[0];
        var lat = long_lat[1];
        if(lng == '' || lat == ''){
            layer.alert('请先在地图上标注位置');
            return;
        }
        window.parent.setlnglat(lng,lat);
        window.close();
    }

    $(function(){
        var local = null;

        var map = null;
        if ($('#long_lat').val() == '') {
            var oPoint = new BMap.Point(117.2285309,31.8291397);
        } else {
            var long_lat = $('#long_lat').val().split(',');
            var oPoint = new BMap.Point(long_lat[0], long_lat[1]);
        }
        var marker = new BMap.Marker(oPoint);
        var setPoint = function(mk,b){
            var pt = mk.getPosition();
            $('#long_lat').val(pt.lng+','+pt.lat);
            (new BMap.Geocoder()).getLocation(pt,function(rs){
                addComp = rs.addressComponents;
                if(rs.surroundingPois && rs.surroundingPois.length > 0){
                    $('#adress').val(rs.surroundingPois[0].title);
                }else if(addComp.street != ''){
                    $('#adress').val(addComp.street + addComp.streetNumber);
                }else{
                    $('#adress').val(addComp.city + addComp.district);
                }
            });
        };
        
        map = new BMap.Map("cmmap",{"enableMapClick":false});
        map.enableScrollWheelZoom();
        marker.enableDragging();
        
        if ($('#long_lat').val() == '') {
            map.centerAndZoom(oPoint, 12);
            function myFun(result){
                oPoint = new BMap.Point(result.center['lng'],result.center['lat']);
                map.centerAndZoom(oPoint,12);
                marker.setPosition(oPoint);
            }
            var myCity = new BMap.LocalCity();
            myCity.get(myFun);
        } else {
            map.centerAndZoom(oPoint,18);
        }

        map.addControl(new BMap.NavigationControl());
        map.enableScrollWheelZoom();

        map.addOverlay(marker);
        
        var info_opts = {
          width : 100,
          height: 30,
          title : "提示：",
          enableMessage:false,
          message:""
        };
        var infoWindow = new BMap.InfoWindow("您的坐标设置成功！",info_opts);
        
        marker.addEventListener("dragend", function(){
            setPoint(marker,true);
        });
        marker.addEventListener("click", function(e){   
            setPoint(marker,true);
            map.openInfoWindow(infoWindow,marker.getPosition());
        });
        local = new BMap.LocalSearch(map,{
            pageCapacity:1,
            onSearchComplete:function(results){
                map.centerAndZoom(results.getPoi(0).point, 17);
                marker.setPosition(results.getPoi(0).point);
            }
        });
        
        $('#map-search').submit(function(){
            $('#map-keyword').val($.trim($('#map-keyword').val()));
            if($('#map-keyword').val().length >0){
                local.search($('#map-keyword').val());
            }
            
            return false;
        });
    });
</script>


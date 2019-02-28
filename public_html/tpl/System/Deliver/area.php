<include file="Public:header" />
<script>var data = [];</script>
<style>
    .ft{float: left;}
    .rg{float: right;}
    .clear:after{
        content: " ";
        display: block;
        clear:both;
    }
     .deliver_search a:hover{
        text-decoration: none;
     }
    .deliver_search a{
        display: inline-block;
        width: 100px;
        height: 50px;
        text-align: center;
        line-height: 50px;
        background: #87B97E;
        color: #fff;
        padding: 0;
        border:none;
    }
    .myform{padding-left:30px;}
    .myform ul li{
        margin-right: 30px;
        width: 412px;
        font-size: 0;
    } 
     .myform ul li p{
        border:1px solid #f3f3f3;
        margin: 0;padding: 8px 0;
        padding-left:20px;
        font-size: 14px; 
        color: #666;
        width:290px;
        display: inline-block;
     }
     .myform ul li .btn1{
        height: 43px;
        line-height:43px;
        border: none;
        width: 50px;
        color: #fff;
        background: #169BD5;
     }
     .myform ul li .btn1:last-child{
        background: #990000;
     }
     .itemBot{
        height: 300px;
     }
</style>
<div class="mainbox">
    <div id="nav" class="mainnav_title">
        <ul>
            <a href="{pigcms{:U('Config/index',array('galias'=>'deliver','header'=>'Deliver/header'))}">配送配置</a>|
            <a href="{pigcms{:U('Deliver/area')}" class="on">配送区域管理</a>
        </ul>
    </div>
    <table class="search_table" width="100%">
        <tr>
            <td>
                <div class="deliver_search">
                <a href="javascript:void(0);" class="button" onclick="window.top.artiframe('{pigcms{:U('Deliver/custom')}','新建配送区域',680,480,true,false,false,addbtn,'add',true);">新建配送区域</a>
                </div>
            </td>
        </tr>
    </table>
    <form name="myform" id="myform">
        <div class="myform">
           <ul class="clear">
                <volist name="customs" id="row">
                <li class="ft">
                    <div class="itemTop">
                       <p>名称: <span>{pigcms{$row['name']}</span></p>
                       <button class="btn1" type="button" onclick="window.top.artiframe('{pigcms{:U('Deliver/custom', array('id' => $row['id']))}','编辑配送区域',680,480,true,false,false,editbtn,'edit',true);">编辑</button>
                       <button class="btn1 delete_row" type="button" parameter="id={pigcms{$row['id']}" url="{pigcms{:U('Deliver/customDel')}">删除</button>
                    </div>
                    <div class="itemBot" id="area_{pigcms{$row['id']}"></div>
                    <script>
                    var polygon = '{pigcms{$row['delivery_range_polygon']}';
                    polygon = $.parseJSON(polygon);
                    data.push({'id':'{pigcms{$row["id"]}', 'lat':'{pigcms{$row["lat"]}', 'lng':'{pigcms{$row["lng"]}', 'polygon':polygon});
                    </script>
                </li>
                </volist>
           </ul>
        </div>
    </form>
</div>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=drawing&key={pigcms{$config.google_map_ak}"></script>
    <script>
        $(document).ready(function(){
            for (var k in data) {
                var map = new google.maps.Map(document.getElementById('area_'+ data[k].id), {
                    zoom: 13,
                    center: {lat: parseFloat(data[k].lat), lng: parseFloat(data[k].lng)},
                });
                var marker = new google.maps.Marker({
                    position: {lat:parseFloat(data[k].lat), lng: parseFloat(data[k].lng)},
                    map: map,
                    title: 'your location'
                });
                var polyCoords = [];
                var polygon = data[k].polygon;
                if (polygon != null) {
                    for (var i in polygon) {
                        for (var ii in polygon[i]) {
                            polyCoords.push({lat: +parseFloat(polygon[i][ii].lat), lng: +parseFloat(polygon[i][ii].lng)});
                        }
                        bermudaTriangle = new google.maps.Polygon({
                            paths: polyCoords,
                            strokeColor: '#FF0000',
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: '#FF0000',
                            fillOpacity: 0.35
                        });
                        bermudaTriangle.setMap(map);
                    }
                }
            }
        });
    </script>

    <else />
<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
<script type="text/javascript" src="https://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    for (var k in data) {
        var map = new BMap.Map('area_' + data[k].id ,{"enableMapClick":false}), point = new BMap.Point(data[k].lng, data[k].lat);
        map.centerAndZoom(point, 14);
        map.enableScrollWheelZoom();
        var marker = new BMap.Marker(point);// 创建标注
        map.addOverlay(marker);
        marker.enableDragging();
        var polygon = data[k].polygon;
        if (polygon != null) {
            for (var i in polygon) {
                var polygonArr = [];
                for (var ii in polygon[i]) {
                    polygonArr.push(new BMap.Point(polygon[i][ii].lng, polygon[i][ii].lat));
                }
                var poly = new BMap.Polygon(polygonArr, {strokeColor:"rgb(51, 136, 255)", fillColor:"rgb(51, 136, 255)", strokeWeight:2, fillOpacity: 0.2, strokeOpacity:0.8,strokeStyle:'dashed'});
                map.addOverlay(poly);  //创建多边形
            }
        }
    }
});
</script>
</if>
<include file="Public:footer" />
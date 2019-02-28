<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Shop/shop_amend')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$now_shop.store_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">店铺名称</th>
				<td>{pigcms{$now_shop.name}</td>
			</tr>
			<tr>
				<th width="90">是否是第三方配送转平台配送</th>
				<td>
					<select name="third_send_to_sys" class="valid" tips="选择是，第三方配送自动转平台配送">
						<option value="0" <if condition="$now_shop['third_send_to_sys'] eq 0">selected</if>>否</option>
						<option value="1" <if condition="$now_shop['third_send_to_sys'] eq 1">selected</if>>是</option>
					</select>
				</td>
			</tr>
			<tr>
				<th width="90">服务范围类型</th>
				<td>
					<select name="delivery_range_type" class="valid" tips="半径范围|自定义范围">
					<option value="0" <if condition="$now_shop['delivery_range_type'] eq 0">selected</if>>半径范围</option>
					<option value="1" <if condition="$now_shop['delivery_range_type'] eq 1">selected</if>>自定义范围</option>
					</select>
				</td>
			</tr>
			<tr class="delivery_range_type0">
				<th width="90">服务范围</th>
				<td><input type="text" class="input fl" name="delivery_radius" value="{pigcms{$now_shop.delivery_radius|floatval}" id="reduce_money" size="10" tips="以店铺为起点的半径距离(单位：㎞)"/></td>
			</tr>
            <tr class="delivery_range_type1">
                <th width="90">选择配送区域</th>
                <td>
                    <select name="custom_id" class="valid" tips="半径范围|自定义范围">
                    <option value="0">请选择</option>
                    <volist name="customs" id="row">
                    <option value="{pigcms{$row['id']}" data-value="{pigcms{$row['delivery_range_polygon']}" data-lat="{pigcms{$row['lat']}" data-lng="{pigcms{$row['lng']}" <if condition="$row['id'] eq $now_shop['custom_id']">selected</if>>{pigcms{$row['name']}</option>
                    </volist>
                    </select>
                </td>
            </tr>
			<tr class="delivery_range_type1">
				<td>自定义范围</td>
				<td><input type="button" class="button" value="绘制配送范围" id="baiduMap"/></td>
			</tr>
			<tr class="delivery_range_type1">
			    <input type="hidden" name="delivery_range_polygon" id="delivery_range_polygon" />
				<td colspan="2"><div id="allmap" style="height:350px;"></div></td>
			</tr>
			
			<tr>
				<th width="90">配送时长</th>
				<td><input type="text" class="input fl" name="s_send_time" value="{pigcms{$now_shop.s_send_time|intval}" size="10" tips="配送员从门店取货至送达用户所需要的时间(单位：分钟)"/></td>
			</tr>
            
            <tr>
                <th width="90">起送价</th>
                <td><input type="text" class="input fl" name="s_basic_price" value="{pigcms{$now_shop.s_basic_price|floatval}" size="10" tips="每单达到这个价格才给予配送"/></td>
            </tr>
			
			<tr>
				<th width="90">加价送</th>
				<td><input type="text" class="input fl" name="s_extra_price" value="{pigcms{$now_shop.s_extra_price|floatval}" size="10" tips="当订单的价格没有达到起送价加的时候加价也可以配送"/></td>
			</tr>
            <tr>
                <th width="90">开启虚拟配送费</th>
                <td>
                    <select name="s_is_open_virtual" class="valid" tips=" 开启虚拟配送费之后，用户前台优先显示该虚拟配送费，该虚拟配送费只做显示，不做为计算标准。">
                        <option value="0" <if condition="$now_shop['s_is_open_virtual'] eq 0">selected</if>>关闭</option>
                        <option value="1" <if condition="$now_shop['s_is_open_virtual'] eq 1">selected</if>>开启</option>
                    </select>
                </td>
            </tr>
            <tr class="open_virtual">
                <th width="90">虚拟配送费</th>
                <td>
                    <input type="text" class="input fl" name="virtual_delivery_fee" value="{pigcms{$now_shop.virtual_delivery_fee|floatval}" size="10" tips="虚拟配送费"/>
                </td>
            </tr>
			<tr>
				<th width="90">开启配送设置</th>
				<td>
					<select name="s_is_open_own" class="valid" tips="是否开启平台对店铺的配送费单独设置，如果开启，下面的设置才有用，如果关闭那么采用平台的默认设置">
					<option value="0" <if condition="$now_shop['s_is_open_own'] eq 0">selected</if>>关闭</option>
					<option value="1" <if condition="$now_shop['s_is_open_own'] eq 1">selected</if>>开启</option>
					</select>
				</td>
			</tr>
			<tr class="open_own" >
				<th colspan="2" style="color:red">配送时间段一的设置</th>
			</tr>
            <tr class="open_own">
                <th width="90">时段一起送价</th>
                <td><input type="text" class="input fl" name="s_basic_price1" value="{pigcms{$now_shop.s_basic_price1|floatval}" size="10" tips="每单达到这个价格才给予配送"/></td>
            </tr>
			<tr class="open_own" >
				<th width="90">免配送费设置</th>
				<td>
					<select name="s_free_type" class="valid" tips="订单金额超过下面的[订单满]免配送费">
					<option value="0" <if condition="$now_shop['s_free_type'] eq 0">selected</if>>免配送费</option>
					<option value="1" <if condition="$now_shop['s_free_type'] eq 1">selected</if>>不免配送费</option>
					<option value="2" <if condition="$now_shop['s_free_type'] eq 2">selected</if>>订单金额达条件免</option>
					</select>
				</td>
			</tr>
			<tr class="open_own free_type full_money">
				<th width="90">订单满</th>
				<td><input type="text" class="input fl" name="s_full_money" value="{pigcms{$now_shop.s_full_money|floatval}" id="reduce_money" size="10" tips="（单位:元）上面一项选择了满免后，当订单达到该项指定金额免配送费"/></td>
			</tr>
			<tr class="open_own free_type">
				<th width="90">起步配送费</th>
				<td><input type="text" class="input fl" name="s_delivery_fee" value="{pigcms{$now_shop.s_delivery_fee|floatval}" id="reduce_money" size="10" tips="在起步距离范围内的配送费（单位:元）"/></td>
			</tr>
			<tr class="open_own free_type">
				<th width="90">起步配送距离</th>
				<td><input type="text" class="input fl" name="s_basic_distance" value="{pigcms{$now_shop.s_basic_distance|floatval}" id="reduce_money" size="10" tips="每单在起步距离（单位:公里）"/></td>
			</tr>
			<tr class="open_own free_type">
				<th width="90">每公里的配送费</th>
				<td><input type="text" class="input fl" name="s_per_km_price" value="{pigcms{$now_shop.s_per_km_price|floatval}" id="reduce_money" size="10" tips="超出起步距离的路程每公里的单价，超出部分的配送费计算规则根据【配送条件>配送费计算方式中为准】，配送距离计算规格根据【配送条件>配送距离计算方式中为准】"/></td>
			</tr>
			<if condition="$is_have_two_time">
			<tr class="open_own" >
				<th colspan="2" style="color:red">配送时间段二的设置</th>
			</tr>
            <tr class="open_own">
                <th width="90">时段二起送价</th>
                <td><input type="text" class="input fl" name="s_basic_price2" value="{pigcms{$now_shop.s_basic_price2|floatval}" size="10" tips="每单达到这个价格才给予配送"/></td>
            </tr>
			<tr class="open_own" >
				<th width="90">免配送费设置</th>
				<td>
					<select name="s_free_type2" class="valid" tips="订单金额超过下面的[订单满]免配送费">
					<option value="0" <if condition="$now_shop['s_free_type2'] eq 0">selected</if>>免配送费</option>
					<option value="1" <if condition="$now_shop['s_free_type2'] eq 1">selected</if>>不免配送费</option>
					<option value="2" <if condition="$now_shop['s_free_type2'] eq 2">selected</if>>订单金额达条件免</option>
					</select>
				</td>
			</tr>
			<tr class="open_own free_type2 full_money2">
				<th width="90">订单满</th>
				<td><input type="text" class="input fl" name="s_full_money2" value="{pigcms{$now_shop.s_full_money2|floatval}" id="reduce_money" size="10" tips="（单位:元）上面一项选择了满免后，当订单达到该项指定金额免配送费"/></td>
			</tr>
			<tr class="open_own free_type2">
				<th width="90">起步配送费</th>
				<td><input type="text" class="input fl" name="s_delivery_fee2" value="{pigcms{$now_shop.s_delivery_fee2|floatval}" id="reduce_money" size="10" tips="在起步距离范围内的配送费（单位:元）"/></td>
			</tr>
			<tr class="open_own free_type2">
				<th width="90">起步配送距离</th>
				<td><input type="text" class="input fl" name="s_basic_distance2" value="{pigcms{$now_shop.s_basic_distance2|floatval}" id="reduce_money" size="10" tips="每单在起步距离（单位:公里）"/></td>
			</tr>
			<tr class="open_own free_type2">
				<th width="90">每公里的配送费</th>
				<td><input type="text" class="input fl" name="s_per_km_price2" value="{pigcms{$now_shop.s_per_km_price2|floatval}" id="reduce_money" size="10" tips="超出起步距离的路程每公里的单价，超出部分的配送费计算规则根据【配送条件>配送费计算方式中为准】，配送距离计算规格根据【配送条件>配送距离计算方式中为准】"/></td>
			</tr>
			</if>
            <if condition="$is_have_three_time">
            <tr class="open_own" >
                <th colspan="2" style="color:red">配送时间段三的设置</th>
            </tr>
            <tr class="open_own">
                <th width="90">时段三起送价</th>
                <td><input type="text" class="input fl" name="s_basic_price3" value="{pigcms{$now_shop.s_basic_price3|floatval}" size="10" tips="每单达到这个价格才给予配送"/></td>
            </tr>
            <tr class="open_own" >
                <th width="90">免配送费设置</th>
                <td>
                    <select name="s_free_type3" class="valid" tips="订单金额超过下面的[订单满]免配送费">
                    <option value="4" <if condition="$now_shop['s_free_type3'] eq 4">selected</if>>读取平台统一配置</option>
                    <option value="0" <if condition="$now_shop['s_free_type3'] eq 0">selected</if>>免配送费</option>
                    <option value="1" <if condition="$now_shop['s_free_type3'] eq 1">selected</if>>不免配送费</option>
                    <option value="2" <if condition="$now_shop['s_free_type3'] eq 2">selected</if>>订单金额达条件免</option>
                    </select>
                </td>
            </tr>
            <tr class="open_own free_type3 full_money3">
                <th width="90">订单满</th>
                <td><input type="text" class="input fl" name="s_full_money3" value="{pigcms{$now_shop.s_full_money3|floatval}" id="reduce_money" size="10" tips="（单位:元）上面一项选择了满免后，当订单达到该项指定金额免配送费"/></td>
            </tr>
            <tr class="open_own free_type3">
                <th width="90">起步配送费</th>
                <td><input type="text" class="input fl" name="s_delivery_fee3" value="{pigcms{$now_shop.s_delivery_fee3|floatval}" id="reduce_money" size="10" tips="在起步距离范围内的配送费（单位:元）"/></td>
            </tr>
            <tr class="open_own free_type3">
                <th width="90">起步配送距离</th>
                <td><input type="text" class="input fl" name="s_basic_distance3" value="{pigcms{$now_shop.s_basic_distance3|floatval}" id="reduce_money" size="10" tips="每单在起步距离（单位:公里）"/></td>
            </tr>
            <tr class="open_own free_type3">
                <th width="90">每公里的配送费</th>
                <td><input type="text" class="input fl" name="s_per_km_price3" value="{pigcms{$now_shop.s_per_km_price3|floatval}" id="reduce_money" size="10" tips="超出起步距离的路程每公里的单价，超出部分的配送费计算规则根据【配送条件>配送费计算方式中为准】，配送距离计算规格根据【配送条件>配送距离计算方式中为准】"/></td>
            </tr>
            </if>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=drawing&key={pigcms{$config.google_map_ak}"></script>
    <script>
        var polygon = '{pigcms{$now_shop['delivery_range_polygon']}';
        if(polygon){
            polygon = $.parseJSON(polygon);
            var nowlat = '{pigcms{$now_shop["lat"]}';
            var nowlng = '{pigcms{$now_shop["long"]}';
        }else{
            nowlat = 31.817797156213604;
            nowlng = 117.22220727680337;
        }
        var bermudaTriangle;
        var map;
        var drawingManager;
        var selectedShape;
        initMap();
        function initMap() {
            map = new google.maps.Map(document.getElementById('allmap'), {
                zoom: 14,
                center: {lat: parseFloat(nowlat), lng: parseFloat(nowlng)}
            });
            var marker = new google.maps.Marker({
                position: {lat: parseFloat(nowlat), lng: parseFloat(nowlng)},
                map: map,
                title: '默认位置'
            });
            google.maps.event.addDomListener(document.getElementById('baiduMap'), 'click', deleteSelectedShape);
            // Define the LatLng coordinates for the polygon's path.
            //绘画工具 设置
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        google.maps.drawing.OverlayType.POLYGON
                    ]
                },
                //设置图形显示样式
                circleOptions: {
                    fillColor: '#ffff00',
                    fillOpacity: 1,
                    strokeWeight: 5,
                    clickable: false,
                    editable: true,
                    zIndex: 1
                },
                polygonOptions: {
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35,
                    editable: true,
                }
            });
            drawingManager.setMap(map);
            //注册 多边形 绘制完成事件
            google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
                var array = polygon.getPath().getArray();
                showLonLat(array);
            });
            //将写入的加入数组
            google.maps.event.addListener(drawingManager, 'overlaycomplete', function (e) {
                var newShape = e.overlay;
                newShape.type = e.type;

                if (e.type !== google.maps.drawing.OverlayType.MARKER) {
                    // Switch back to non-drawing mode after drawing a shape.
                    drawingManager.setDrawingMode(null);

                    // Add an event listener that selects the newly-drawn shape when the user
                    // mouses down on it.
                    google.maps.event.addListener(newShape, 'click', function (e) {
                        if (e.vertex !== undefined) {
                            if (newShape.type === google.maps.drawing.OverlayType.POLYGON) {
                                var path = newShape.getPaths().getAt(e.path);
                                path.removeAt(e.vertex);
                                if (path.length < 3) {
                                    newShape.setMap(null);
                                }
                            }
                            if (newShape.type === google.maps.drawing.OverlayType.POLYLINE) {
                                var path = newShape.getPath();
                                path.removeAt(e.vertex);
                                if (path.length < 2) {
                                    newShape.setMap(null);
                                }
                            }
                        }
                        setSelection(newShape);
                    });
                    setSelection(newShape);
                }
                else {
                    google.maps.event.addListener(newShape, 'click', function (e) {
                        setSelection(newShape);
                    });
                    setSelection(newShape);
                }
            });
            //循环显示 经纬度
            var latlng = [];
            function showLonLat(arr) {
                var bounds = new google.maps.LatLngBounds();

                for (var i = 0; i < arr.length; i++) {
                    bounds.extend(arr[i]);
                    latlng.push(arr[i].lat() + '-' + arr[i].lng());
                    $('#delivery_range_polygon').val(latlng.join('|'));
                }
                // $('#lng_lat').val(bounds.getCenter().lng()+','+bounds.getCenter().lat());
            }

            var polyCoords = [];
            var lat_lng = [];
            for (i = 0; i < polygon.length; i++) {
                for (k = 0; k < polygon[i].length; k++) {
                    polyCoords.push({lat: parseFloat(polygon[i][k].lat), lng: parseFloat(polygon[i][k].lng)});
                    lat_lng.push(polygon[i][k].lat + '-' + polygon[i][k].lng);
                }
            }
            $('#delivery_range_polygon').val(lat_lng.join('|'));
            // Construct the polygon.
            bermudaTriangle = new google.maps.Polygon({
                paths: polyCoords,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35
            });
            addPolygon();
            //切换
            var delivery_range_type = $('select[name=delivery_range_type]').val();
            if (delivery_range_type == 0) {
                $('.delivery_range_type0').show();
                $('.delivery_range_type1').hide();
            } else {
                $('.delivery_range_type1').show();
                $('.delivery_range_type0').hide();
            }
            $('select[name=delivery_range_type]').change(function () {
                if ($(this).val() == 0) {
                    $('.delivery_range_type0').show();
                    $('.delivery_range_type1').hide();
                } else {
                    $('.delivery_range_type1').show();
                    $('.delivery_range_type0').hide();
                }
            });

            $('select[name=custom_id]').change(function () {
                    deleteSelectedShape();

                if ($(this).val() != 0) {
                    map.setCenter({lat:parseFloat($(this).find("option:selected").data('lat')),lng:parseFloat($(this).find("option:selected").data('lng'))});
                    polygon = $(this).find("option:selected").data('value').split('|');
                    var polygonArr = [];

                    for (var i in polygon) {
                        var arr = polygon[i].split('-');
                        polygonArr.push({lat: +parseFloat(arr[0]), lng: +parseFloat(arr[1])});
                    }
                    $('#delivery_range_polygon').val($(this).val());
                        bermudaTriangle = new google.maps.Polygon({
                            paths: polygonArr,
                            strokeColor: '#FF0000',
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: '#FF0000',
                            fillOpacity: 0.35
                        });
                        addPolygon();
                }
            });
            //切换代码结束
            function setSelection (shape) {
                selectedShape = shape;
            }
            function deleteSelectedShape(){
                bermudaTriangle.setMap(null);
                if (selectedShape) {
                    selectedShape.setMap(null);
                }
            }

            function addPolygon() {
                bermudaTriangle.setMap(map);
            }

        }
        </script>
    <else />
<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
<script type="text/javascript" src="https://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.js"></script>
<script>
var is_have_two_time = '{pigcms{$is_have_two_time}';
var polygon = '{pigcms{$now_shop['delivery_range_polygon']}';
polygon = $.parseJSON(polygon);
var oldOverlay = [];
$(document).ready(function() {
    var map = new BMap.Map("allmap", {"enableMapClick": false}),
        point = new BMap.Point('{pigcms{$now_shop["long"]}', '{pigcms{$now_shop["lat"]}');
    map.centerAndZoom(point, 15);
    map.enableScrollWheelZoom();
    var marker = new BMap.Marker(point);// 创建标注
    map.addOverlay(marker);
    marker.enableDragging();
    if (polygon != null) {
        for (var i in polygon) {
            var polygonArr = [];
            var lat_lng = [];
            for (var ii in polygon[i]) {
                polygonArr.push(new BMap.Point(polygon[i][ii].lng, polygon[i][ii].lat));
                lat_lng.push(polygon[i][ii].lat + '-' + polygon[i][ii].lng);
            }
            $('#delivery_range_polygon').val(lat_lng.join('|'));

            var poly = new BMap.Polygon(polygonArr, {
                strokeColor:"rgb(51, 136, 255)",    //边线颜色。
				fillColor:"rgb(51, 136, 255)",      //填充颜色。当参数为空时，圆形将没有填充效果。
				strokeWeight: 3,       //边线的宽度，以像素为单位。
				strokeOpacity: 0.8,	   //边线透明度，取值范围0 - 1。
				fillOpacity: 0.6,      //填充的透明度，取值范围0 - 1。
				strokeStyle: 'dashed' //边线的样式，solid或dashed。
            });

            map.addOverlay(poly);  //创建多边形
            poly.enableEditing();
            oldOverlay.push(poly);
			poly.addEventListener("lineupdate", function (e) {
				lat_lng = [];
				delivery_area = $.map(e.target.getPath(), function (item) {
					  lat_lng.push(item.lat + '-' + item.lng);
					  return {'lng': item.lng, 'lat': item.lat};
				});
				$('#delivery_range_polygon').val(lat_lng.join('|'));
			
			});
        }
    }

	var overlays = [];
	var overlaycomplete = function (e) {
        overlays.push(e.overlay);
        var latLng = e.overlay.getPath();
        var lat_lng = [];
		var polygonArr = [];
        for (var i in latLng) {
        	lat_lng.push(latLng[i].lat + '-' + latLng[i].lng);
			 polygonArr.push(new BMap.Point(latLng[i].lng, latLng[i].lat));
        }
		
		var poly = new BMap.Polygon(polygonArr, {strokeColor:"rgb(51, 136, 255)",fillColor:"rgb(51, 136, 255)", strokeWeight:2, fillOpacity: 0.2, strokeOpacity:0.8,strokeStyle:'dashed'});
            
		map.addOverlay(poly);  //创建多边形
		poly.enableEditing();
		poly.addEventListener("lineupdate", function (e) {
			lat_lng = [];
			delivery_area = $.map(e.target.getPath(), function (item) {
				  lat_lng.push(item.lat + '-' + item.lng);
				  return {'lng': item.lng, 'lat': item.lat};
			});
			$('#delivery_range_polygon').val(lat_lng.join('|'));
			
		});
			
		map.removeOverlay(e.overlay);
		  oldOverlay.push(poly);
        $('#delivery_range_polygon').val(lat_lng.join('|'));
		console.log($('#delivery_range_polygon').val())
    };
    var styleOptions = {
        strokeColor:"rgb(51, 136, 255)",    //边线颜色。
		fillColor:"rgb(51, 136, 255)",      //填充颜色。当参数为空时，圆形将没有填充效果。
		strokeWeight: 3,       //边线的宽度，以像素为单位。
		strokeOpacity: 0.8,	   //边线透明度，取值范围0 - 1。
		fillOpacity: 0.6,      //填充的透明度，取值范围0 - 1。
		strokeStyle: 'dashed' //边线的样式，solid或dashed。
    }
    //实例化鼠标绘制工具
    var drawingManager = new BMapLib.DrawingManager(map, {
        isOpen: false, //是否开启绘制模式
        enableDrawingTool: false, //是否显示工具栏
        drawingMode: BMAP_DRAWING_POLYGON,
        drawingToolOptions: {
            anchor: BMAP_ANCHOR_TOP_RIGHT, //位置
            offset: new BMap.Size(5, 5), //偏离值
        },
        circleOptions: styleOptions, //圆的样式
        polylineOptions: styleOptions, //线的样式
        polygonOptions: styleOptions, //多边形的样式
        rectangleOptions: styleOptions //矩形的样式
    });


    $('#baiduMap').click(function () {
        drawingManager.open();
        $('select[name=custom_id]').val(0);
        for (var i = 0; i < overlays.length; i++) {
            map.removeOverlay(overlays[i]);
        }
        if (oldOverlay.length > 0) {
            console.log(oldOverlay);
            for (var i = 0; i < oldOverlay.length; i++) {
                map.removeOverlay(oldOverlay[i]);
            }
        }
        overlays = [];
        oldOverlay = [];
        polygonArr = [];
    });

    //添加鼠标绘制工具监听事件，用于获取绘制结果
    drawingManager.addEventListener('overlaycomplete', overlaycomplete);

    var delivery_range_type = $('select[name=delivery_range_type]').val();
    if (delivery_range_type == 0) {
        $('.delivery_range_type0').show();
        $('.delivery_range_type1').hide();
    } else {
        $('.delivery_range_type1').show();
        $('.delivery_range_type0').hide();
    }
    $('select[name=delivery_range_type]').change(function () {
        if ($(this).val() == 0) {
            $('.delivery_range_type0').show();
            $('.delivery_range_type1').hide();
        } else {
            $('.delivery_range_type1').show();
            $('.delivery_range_type0').hide();
        }
		
		clean_overlays()
    });

    $('select[name=custom_id]').change(function () {
        drawingManager.open();
        for (var i = 0; i < overlays.length; i++) {
            map.removeOverlay(overlays[i]);
        }
        if (oldOverlay.length > 0) {
            for (var i = 0; i < oldOverlay.length; i++) {
                map.removeOverlay(oldOverlay[i]);
            }
        }
        overlays = [];
        if ($(this).val() != 0) {
            map.panTo(new BMap.Point($(this).find("option:selected").data('lng'), $(this).find("option:selected").data('lat')));
            polygon = $(this).find("option:selected").data('value').split('|');
            var polygonArr = [];
            var lat_lng = [];
            for (var i in polygon) {
                var arr = polygon[i].split('-');
                console.log(arr[1]);
                polygonArr.push(new BMap.Point(arr[1], arr[0]));
                lat_lng.push(polygon[i]);
            }
            $('#delivery_range_polygon').val($(this).val());
            var poly = new BMap.Polygon(polygonArr, {
                 strokeColor:"rgb(51, 136, 255)",    //边线颜色。
				fillColor:"rgb(51, 136, 255)",      //填充颜色。当参数为空时，圆形将没有填充效果。
				strokeWeight: 3,       //边线的宽度，以像素为单位。
				strokeOpacity: 0.8,	   //边线透明度，取值范围0 - 1。
				fillOpacity: 0.6,      //填充的透明度，取值范围0 - 1。
				strokeStyle: 'dashed' //边线的样式，solid或dashed。
            });
            map.addOverlay(poly);  //创建多边形
            oldOverlay.push(poly)
        }
    });
});

function clean_overlays(){
	 for(var i = 0; i < overlays.length; i++){
			map.removeOverlay(overlays[i]);
		}
		if (oldOverlay.length > 0) {
			for(var i = 0; i < oldOverlay.length; i++){
				map.removeOverlay(oldOverlay[i]);
			}
		}
		overlays = [];
		oldOverlay=[];
		polygonArr = [];
}
</script>
</if>
<script>
    $(document).ready(function() {
	var s_is_open_own = $('select[name=s_is_open_own]').val(), s_free_type = $('select[name=s_free_type]').val(),s_is_open_virtual = $('select[name=s_is_open_virtual]').val();
	if (s_is_open_own == 1) {
		$('.open_own').show();
		if (s_free_type == 0) {
			$('.free_type').hide();
		} else if (s_free_type == 1) {
			$('.free_type').show();
			$('.full_money').hide();
		} else if (s_free_type == 2) {
			$('.free_type').show();
		}
		<if condition="$is_have_two_time">
		var s_free_type2 = $('select[name=s_free_type2]').val();
		if (s_free_type2 == 0) {
			$('.free_type2').hide();
		} else if (s_free_type2 == 1) {
			$('.free_type2').show();
			$('.full_money2').hide();
		} else if (s_free_type2 == 2) {
			$('.free_type2').show();
		}
		</if>
        <if condition="$is_have_three_time">
        var s_free_type3 = $('select[name=s_free_type3]').val();
        if (s_free_type3 == 0 || s_free_type3 == 4) {
            $('.free_type3').hide();
        } else if (s_free_type3 == 1) {
            $('.free_type3').show();
            $('.full_money3').hide();
        } else if (s_free_type3 == 2) {
            $('.free_type3').show();
        }
        </if>
	} else {
		$('.open_own').hide();
	}
	$('select[name=s_is_open_own]').change(function(){
		if ($(this).val() == 1) {
			$('.open_own').show();
			s_free_type = $('select[name=s_free_type]').val();
			if (s_free_type == 0) {
				$('.free_type').hide();
			} else if (s_free_type == 1) {
				$('.free_type').show();
				$('.full_money').hide();
			} else if (s_free_type == 2) {
				$('.free_type').show();
			}
			<if condition="$is_have_two_time">
			s_free_type2 = $('select[name=s_free_type2]').val();
			if (s_free_type2 == 0) {
				$('.free_type2').hide();
			} else if (s_free_type2 == 1) {
				$('.free_type2').show();
				$('.full_money2').hide();
			} else if (s_free_type2 == 2) {
				$('.free_type2').show();
			}
    		</if>
            <if condition="$is_have_three_time">
            s_free_type3 = $('select[name=s_free_type3]').val();
            if (s_free_type3 == 0 || s_free_type3 == 4) { 
                $('.free_type3').hide();
            } else if (s_free_type3 == 1) {
                $('.free_type3').show();
                $('.full_money3').hide();
            } else if (s_free_type3 == 2) {
                $('.free_type3').show();
            }
        </if>
		} else {
			$('.open_own').hide();
		}
	});
	$('select[name=s_free_type]').change(function(){
		if ($(this).val() == 0) {
			$('.free_type').hide();
		} else if ($(this).val() == 1) {
			$('.free_type').show();
			$('.full_money').hide();
		} else if ($(this).val() == 2) {
			$('.free_type').show();
		}
	});

	$('select[name=s_free_type2]').change(function(){
		if ($(this).val() == 0) {
			$('.free_type2').hide();
		} else if ($(this).val() == 1) {
			$('.free_type2').show();
			$('.full_money2').hide();
		} else if ($(this).val() == 2) {
			$('.free_type2').show();
		}
	});

    $('select[name=s_free_type3]').change(function(){
        if ($(this).val() == 0 || $(this).val() == 4) {
            $('.free_type3').hide();
        } else if ($(this).val() == 1) {
            $('.free_type3').show();
            $('.full_money3').hide();
        } else if ($(this).val() == 2) {
            $('.free_type3').show();
        }
    });
        if (s_is_open_virtual == 1) {
            $('.open_virtual').show();
        } else {
            $('.open_virtual').hide();
        }
        $('select[name=s_is_open_virtual]').change(function(){
            if ($(this).val() == 1) {
                $('.open_virtual').show();
            } else {
                $('.open_virtual').hide();
            }
        });
});
</script>
<include file="Public:footer"/>
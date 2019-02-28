//地图控件
var obj_Overlay = {};
function ComplexCustomOverlay(point, text, address, sid){
  this._point = point;
  this._text = text;
  this._address = address;
  this._sid = sid;
}
ComplexCustomOverlay.prototype = new BMap.Overlay();
ComplexCustomOverlay.prototype.initialize = function(map){
  this._map = map;
  var div = this._div = document.createElement("div");
  div.style.position = "absolute";
  div.style.zIndex = BMap.Overlay.getZIndex(this._point.lat);
  div.style.height = "18px";
  div.style.width = "18px";
  div.style.cursor = "pointer";
  div.style.MozUserSelect = "none";
  div.style.background = "url("+tplPath+"images/Map/"+zb_style_arr[zb_style].img+") no-repeat";

  var that = this;
  div.onmouseover = function(){
	this.style.zIndex = 99;
  }
  div.onmouseout = function(){
	this.style.zIndex = BMap.Overlay.getZIndex(that._point.lat);
  }
  div.onclick = function(){
	showSearchInfoWindow(that._point,that._text,that._address)
  }
  map.getPanes().labelPane.appendChild(div);
  return div;
}
ComplexCustomOverlay.prototype.draw = function(){
  var map = this._map;
  var pixel = map.pointToOverlayPixel(this._point);
  this._div.style.left = pixel.x-11 + "px";
  this._div.style.top  = pixel.y-11 + "px";
}
function showSearchInfoWindow(point,text,address){
	var content = '<div style="margin:0;line-height:20px;padding:2px;">'+address+'</div>';
			var searchInfoWindow = null;
			searchInfoWindow = new BMapLib.SearchInfoWindow(map, content, {
				title  : text,      //标题
				width  : 300,             //宽度
				height : 60,              //高度
				panel  : "panel",         //检索结果面板
				enableAutoPan : true,     //自动平移
				searchTypes   :[
					BMAPLIB_TAB_SEARCH,   //周边检索
					BMAPLIB_TAB_TO_HERE,  //到这里去
					BMAPLIB_TAB_FROM_HERE //从这里出发
				]
			});
			searchInfoWindow.open(point);
}
function addMarker(point,i_title,i_address,i_id){
  obj_Overlay[i_id] = new ComplexCustomOverlay(point,i_title,i_address,i_id);
  map.addOverlay(obj_Overlay[i_id]);
}
var options = {
	onSearchComplete: function(results){
		if (local.getStatus() == BMAP_STATUS_SUCCESS){
			if(!$.isEmptyObject(obj_Overlay)){
				for(var key in obj_Overlay){
					map.removeOverlay(obj_Overlay[key]);
				}
				obj_Overlay={};
				$('#r-result').empty();
			}
			for (var i = 0; i < results.getCurrentNumPois(); i ++){
				var t_lng = results.getPoi(i).point.lng;
				var t_lat = results.getPoi(i).point.lat;
				var p1 = new BMap.Point(t_lng,t_lat);
				addMarker(p1,results.getPoi(i).title,results.getPoi(i).address,results.getPoi(i).uid);
				results.getPoi(i).distance = (map.getDistance(mPoint,p1)).toFixed(0)+'米';
				var TPL=$('#tp').html().replace(/[\n\t\r]/g, '');
				$('#r-result').append(Mustache.to_html(TPL, results.getPoi(i)));
			}
			setTimeout(function(){
				showSearchInfoWindow(new BMap.Point(results.getPoi(0).point.lng,results.getPoi(0).point.lat),results.getPoi(0).title,results.getPoi(0).address);
				$('#r-result').find('li').click(function(e){
					e.preventDefault();
					var p2 = new BMap.Point(parseFloat($(this).attr('data-x')),parseFloat($(this).attr('data-y')));
					var s_title = $(this).find('.s_title').html();
					var s_address = $(this).find('.s_address').html();
					showSearchInfoWindow(p2,s_title,s_address);
					$(this).siblings('li').removeClass('current');
					$(this).addClass('current');
				});
			},200);
		}
	}
};
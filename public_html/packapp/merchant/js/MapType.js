/**
 * [tMapKey 秘钥]
 * @type {Object}
 */

var tMapKey = {
    baidu : '4c1bb2055e24296bbaef36574877b4e2'
}
//百度
var baiduMap ={
    show : function(){
        baiduMap.loScript();
    },
    loScript : function(){
        console.log(2);
        var script = document.createElement("script");
        script.src = "http://api.map.baidu.com/api?v=2.0&ak="+tMapKey.baidu+"&callback=initialize";
        document.getElementsByTagName('head')[0].appendChild(script);
    },
    createMap : function(obj,fun){
        console.log(3);
        var map = new BMap.Map(obj);          // 创建地图实例
        var point = new BMap.Point(116.404, 39.915);  // 创建点坐标
        map.centerAndZoom(point, 15);                 // 初始化地图，设置中心点坐标和地图级别
        map.enableScrollWheelZoom(true);
        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                var mk = new BMap.Marker(r.point);
                map.addOverlay(mk);
                map.panTo(r.point);
                var str=['定位成功'];
              console.log(r.address.city)
                str.push('<div class="map_longitude" data-getLng="' + r.point.lng+'"> 经度：' + r.point.lng+'</div>');
                str.push('<div class="map_latitude" data-getLat="' + r.point.lat+'">纬度：' + r.point.lat+'</div>');
                //document.getElementById('baiduTip').innerHTML = str.join(' ');
                if(fun.callback){
                    fun.callback(r.point.lng,r.point.lat,r.address.city)
                }
            }
            else {
                console.log('failed'+this.getStatus());
            }
        },{enableHighAccuracy: true})
        //关于状态码
        //BMAP_STATUS_SUCCESS	检索成功。对应数值“0”。
        //BMAP_STATUS_CITY_LIST	城市列表。对应数值“1”。
        //BMAP_STATUS_UNKNOWN_LOCATION	位置结果未知。对应数值“2”。
        //BMAP_STATUS_UNKNOWN_ROUTE	导航结果未知。对应数值“3”。
        //BMAP_STATUS_INVALID_KEY	非法密钥。对应数值“4”。
        //BMAP_STATUS_INVALID_REQUEST	非法请求。对应数值“5”。
        //BMAP_STATUS_PERMISSION_DENIED	没有权限。对应数值“6”。(自 1.1 新增)
        //BMAP_STATUS_SERVICE_UNAVAILABLE	服务不可用。对应数值“7”。(自 1.1 新增)
        //BMAP_STATUS_TIMEOUT	超时。对应数值“8”。(自 1.1 新增)
        map.addEventListener('click',function(){
            var center = map.getCenter();
            document.querySelector('.editmap_map .map_longitude').innerHTML ='经度:'+center.lng;
            document.querySelector('.editmap_map .map_latitude').innerHTML ='经度:'+center.lat;
            document.querySelector('.editmap_map  .map_longitude').setAttribute("data-getLng",''+center.lng+'') ;
            document.querySelector('.editmap_map .map_latitude').setAttribute( "data-getLat",''+center.lat+'');

        });
        var ac = new BMap.Autocomplete({    //建立一个自动完成的对象
            "input" : "editmap_id",
            "location" : map
        });

        ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
            var str = "";
            var _value = e.fromitem.value;
            var value = "";
            if (e.fromitem.index > -1) {
                value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            }
            str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;
            value = "";
            if (e.toitem.index > -1) {
                _value = e.toitem.value;
                value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            }
            str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
            document.getElementById("searchResultPanel").innerHTML = str;
        });

        var myValue;
        ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
            var _value = e.item.value;
            myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            document.getElementById("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;

            setPlace();
        });

        function setPlace(){
            map.clearOverlays();    //清除地图上所有覆盖物
            function myFun(){
                var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
                map.centerAndZoom(pp, 18);
                map.addOverlay(new BMap.Marker(pp));    //添加标注
            }
            var local = new BMap.LocalSearch(map, { //智能搜索
                onSearchComplete: myFun
            });
            local.search(myValue);
        }
    }
};

/**
 * [returnMap 调用地图]
 * @param  {[type]} map [高德 百度]
 * @return {[type]}     [description]
 */
var returnMap = function(map){
    console.log(1);
    if(map.show instanceof  Function){
        map.show();
    };
}

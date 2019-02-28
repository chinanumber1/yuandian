$(document).ready(function() {
    //browseCookieSitFun();
    //coordinateAnimate();
    $(document).on('touchend','.tangram-suggestion-main',function(){
        //$("html,body").stop().animate({scrollTop: 0}, 1);
    });
});
//百度地图组件
//setLocation({'locationEle':pEle,'radiusArray':radiusArray,'CallFun':CallFun});
function setLocation(args) {
    var userAgent = navigator.userAgent.toUpperCase(); //取得浏览器的userAgent字符串
    //$('.header').html('<span class="" id="txt1"></span><span id="txt2"></span><span id="txt3"></span>');
    //移动端
    var curHost=location.host;
    var isMobile=curHost.indexOf('wx.')!=-1||curHost.indexOf('ss.')!=-1||curHost.indexOf('m.')!=-1 ? true : false;
    var isWeixin=curHost.indexOf('wx.')!=-1 ? true : false;
    //半径
    var radiusArr=[
        {'value':500,'text':'500米'},
        {'value':1000,'text':'1公里'},
        {'value':2000,'text':'2公里'},
        {'value':5000,'text':'5公里'},
        {'value':8000,'text':'8公里'},
        {'value':0,'text':'全城'}
    ];
    if(args.radiusArray){
        radiusArr=args.radiusArray;
    }
    //回调
    if(args.CallFun){
       var locationCallFun=args.CallFun;
    }
    var locationEle=args.locationEle;
    var map;
    var ac;
    var markerEle;
    var circleEle;
    var mapCenterPoint;//上海
    var ipip_location;
    //输入搜索Fun start
    var blurSearchTrigger = null;
    function G(id) {
        return document.getElementById(id);
    }
    if($(locationEle).hasClass('popUp')){
        $(locationEle).preventWheel();
    }
    //map
    if($(locationEle).length>0){
        var coordinateEle=$(locationEle);
        var mapId=$('.js_coordinate_map',coordinateEle).attr('id');
        //初始化
        var coordinateVal=$('.js_coordinate',coordinateEle).val();
        var mapInitState=true;

        //ip定位 初始化调用start
        if(getCookie_g('ipip_location')!=''&&getCookie_g('ipip_location')!=null){
            //有cookie
            if(mapInitState){
                //初始化函数
                mapInit();
                mapInitState=false;
            }
        }else{
            //无cookie
            //var mapurl='/map/ip.html';
            var mapurl='/map/ip';
            $.ajax({
                url: mapurl,
                type: 'POST',
                dataType:'json',
                success: function(data){
                    if(data.status==1){
                        if(data.coordinate.length>0){
                            ipip_location=data.coordinate;
                        }else{
                            ipip_location=data.city_name;
                        }
                        setCookiePath_g('ipip_location',ipip_location);
                        if(mapInitState){
                            //初始化函数
                            mapInit();
                            mapInitState=false;
                        }
                    }
                }
            });
            //2000 渲染到上海
            setTimeout(function(){
                if(mapInitState){
                    //初始化函数
                    mapInit();
                    mapInitState=false;
                }
            },2000);
        }
        //ip定位 初始化调用end

        //初始化函数
        function mapInit(){
            //ip定位 start
            if(getCookie_g('ipip_location')!=''&&getCookie_g('ipip_location')!=null){
               ipip_location=getCookie_g('ipip_location');
            }
            if(typeof(ipip_location)!='undefined'){
                if(ipip_location.length>0){
                    if(ipip_location.indexOf(',')!=-1){
                        var curPoint=ipip_location.split(',');
                        mapCenterPoint= new BMap.Point(curPoint[0],curPoint[1]);//ipip_location
                    }else{
                        mapCenterPoint=ipip_location;
                    }
                }else{
                    mapCenterPoint=new BMap.Point(121.491, 31.233);//上海
                }
            }else{
                mapCenterPoint=new BMap.Point(121.491, 31.233);//上海
            }
            //ip定位 end
            map = new BMap.Map(mapId,{minZoom:4});
            map.centerAndZoom(mapCenterPoint,12);//500:14;1000:13;2000:12;5000:11;8000:10;
            map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
            //移动端
            if(isMobile){
                map.disableDragging();//禁止拖拽
            }
            //移动端
            if(isMobile){
                var opts = {type: BMAP_NAVIGATION_CONTROL_SMALL}
                map.addControl(new BMap.NavigationControl(opts));
            }
            //初始化
            if(coordinateVal.length==0){
    /*            if(getCookie_g('curLocationPoint').length>0&&getCookie_g('curLocationAddress').length){
                    //设置coordinate
                    var curVal=$('.js_coordinate:eq(0)',coordinateEle).val();
                    if(curVal.length==0){
                        $('.js_coordinate:eq(0)',coordinateEle).val(getCookie_g('curLocationPoint'));
                        $('.js_coordinate_address:eq(0)',coordinateEle).val(getCookie_g('curLocationAddress'));
                        //定位标注
                        map.clearOverlays();    //清除地图上所有覆盖物
                        setMarker(coordinateEle);
                        //输入搜索Fun
                        addressAutocomplete();
                    }

                }else{
                    $('.js_coordinate:eq(0)',coordinateEle).val(shanghai.lng+','+shanghai.lat);
                    GeocoderFun(shanghai,function(address){
                        $('.js_coordinate_address:eq(0)',coordinateEle).val(address);
                    })
                    //定位标注
                    map.clearOverlays();    //清除地图上所有覆盖物
                    setMarker(coordinateEle);*/
                    //输入搜索Fun
                    addressAutocomplete();
    /*            }*/
                //console.log('f1');
            }else{
                //console.log('f2');
                //定位标注
                map.clearOverlays();    //清除地图上所有覆盖物
                setMarker(coordinateEle);
                //输入搜索Fun
                addressAutocomplete();
            }
        }
        //输入搜索Fun
        function addressAutocomplete(){
            $('.anim_loading2',coordinateEle).remove();
            //输入搜索
            $('.js_coordinate_address',coordinateEle).each(function(index, el) {
                $(this).attr('initVal',$(this).val());
                var inputObj=$(this);
                //移动端
                if(isMobile){
                    $(inputObj).after('        <input class="js_coordinate_address_focus" type="text"><div class="clear"></div>');
                }
                var myValue;
                var addressIndex=index;
                var suggestId=$(this).attr('id');
                var ac=new BMap.Autocomplete({
                    "input" : suggestId,
                    "location" : map
                });
                ac.setLocation(mapCenterPoint);
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
                    //G("searchResultPanel").innerHTML = str;
                })
                ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
                    clearTimeout(blurSearchTrigger);
                    var _value = e.item.value;
                    myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
                    //G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;
                    $(inputObj).val(myValue);
                    //console.log('onconfirm'+myValue);
                    //$(inputObj).trigger('blur');
                    setPlace(coordinateEle,addressIndex,myValue);
                    $('.tangram-suggestion-main').hide();
                    //移动端
                    if(isMobile){
                        $(inputObj).trigger('blur');
                        clearTimeout(blurSearchTrigger);
                    }
                });
                ac.setInputValue($(this).attr('initVal'));
                $(inputObj).on('focus',function(){
                    //移动端
                    if(isMobile){
                        var focusAnimate=$(inputObj).attr('focusAnimate');
                        if(focusAnimate!='false'){
                            var caLeft=$(inputObj).offset().left;
                            var caW=$(inputObj).outerWidth();
                            var headerH= $('.header').length>0 ? $('.header').outerHeight() : 0 ;
                            var formPercentH=$('.js_map_topfixed').length>0 ? $('.js_map_topfixed').outerHeight() : 0;
                            var caTop=headerH+formPercentH;
                            var maskW=$(window).width();
                            var maskH=$(window).height()-caTop;
                            $("html,body").stop().animate({scrollTop: 0}, 1);
                            $('body').append('<div class="focusMask"></div>')
                            $('.focusMask').css({'width':maskW+'px','top':caTop+'px'});
                            $(inputObj).css({'left':caLeft+'px','top':(caTop+15)+'px','width':caW+'px'});
                            $(inputObj).addClass('focusinput');
                        }
                    }
                });

                $(inputObj).on('keyup',function(e){
                    if(e.keyCode==13){
                        $(inputObj).trigger('blur');
                    }
                })
                var firstTime=0;
                $(inputObj).on('blur',function(e){
                    //移动端
                    if(isMobile){
                        var focusAnimate=$(inputObj).attr('focusAnimate');
                        if(focusAnimate!='false'){
                            $('.focusMask').remove();
                            $(inputObj).css({'left':'','top':'','width':''});
                            $(inputObj).removeClass('focusinput');
                            $("html,body").stop().animate({scrollTop: 0}, 1);
                            setTimeout(function(){
                                console.log('index:'+index);
                                $('.js_coordinate_address_focus:eq('+index+')',coordinateEle).trigger('focus');
                                $('.js_coordinate_address_focus:eq('+index+')',coordinateEle).trigger('blur');
                            },10);
                        }
                    }
                    if(userAgent.indexOf('BAIDU')!=-1&&userAgent.indexOf("IPHONE")!= -1){
                        if(firstTime==0){
                            firstTime=1;
                        }else{
                            blurSearchTrigger = setTimeout(function(){agentSearchFun($(inputObj),ac,coordinateEle,addressIndex);},10);
                        }
                    }else{
                        blurSearchTrigger = setTimeout(function(){agentSearchFun($(inputObj),ac,coordinateEle,addressIndex);},10);
                    }

                })

            });
        }
        //代理搜索
        function agentSearchFun(obj,acobj,coordinateEle,addressIndex){
            var objVal=$(obj).val();
            var checkIndex=false;
            //console.log(objVal);
            if(objVal.length==0){
                map.clearOverlays();    //清除地图上所有覆盖物
                //console.log('0');
                //console.log($(this).parent());
                $('.js_coordinate',$(obj).parent()).val('');
                $('.js_city_name',$(obj).parent()).val('');
                $('.js_coordinate',$(obj).parent()).trigger('change');
                return;
            }
            if(acobj.Ea==null){
                setPlace(coordinateEle,addressIndex,objVal);
            }else{
                //console.log('3');
                var ImObj=acobj.Ea.Im;
                if(ImObj.length>0){
                    var blurlock=true;
                    for(var i=0; i<ImObj.length; i++){
                        var ImValObj=ImObj[i].value;
                        var ImVal = ImValObj.province +  ImValObj.city +  ImValObj.district +  ImValObj.street +  ImValObj.business;
                        if(objVal==ImVal){
                            checkIndex=i;
                            blurlock=false;
                        }
                    }
                    if(blurlock){
                        var ImValObjSearch=ImObj[0].value;
                    }else{
                        var ImValObjSearch=ImObj[checkIndex].value;
                    }

                    var ImValSearch = ImValObjSearch.province +  ImValObjSearch.city +  ImValObjSearch.district +  ImValObjSearch.street +  ImValObjSearch.business;
                    $(obj).val(ImValSearch);
                    $('.tangram-suggestion-main').hide();
                    setPlace(coordinateEle,addressIndex,ImValSearch);
                    //console.log('blur'+ImValSearch);
                    $('.tangram-suggestion-main').hide();
                }else{
                    setPlace(coordinateEle,addressIndex,objVal);
                }
            }

        }
        //setPlace 搜索 结果
        function setPlace(pEle,index,myValue,tempIndex){
            //console.log('myValue0'+myValue);
            //console.log(tempIndex);
            var tempI=0;
            if(tempIndex){
                tempI=tempIndex;
            }
            function resultsFun(){
                if(local.getResults().getPoi(0)){
                    map.clearOverlays();    //清除地图上所有覆盖物
                    var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
                    //设置coordinate
                    $('.js_coordinate:eq('+index+')',pEle).val(pp.lng+','+pp.lat);
                    $('.js_coordinate:eq('+index+')',pEle).trigger('change');
                    $('.tangram-suggestion-main').hide();
                    //console.log('myValue'+myValue);
                    setMarker(pEle);
                    //移动端
                    if(isMobile){
                        var focusAnimate=$('.js_coordinate_address:eq('+index+')',pEle).attr('focusAnimate');
                        if(focusAnimate!='false'){
                            $('.focusMask').remove();
                            $('.js_coordinate_address:eq('+index+')',pEle).css({'left':'','top':'','width':''});
                            $('.js_coordinate_address:eq('+index+')',pEle).removeClass('focusinput');
                            $("html,body").stop().animate({scrollTop: 0}, 1);

                            setTimeout(function(){
                                $('.js_coordinate_address_focus:eq('+index+')',pEle).trigger('focus');
                                $('.js_coordinate_address_focus:eq('+index+')',pEle).trigger('blur');
                            },10);
                        }
                    }
                    //console.log('0');
                }else{
                    $('.js_coordinate:eq('+index+')',pEle).val('');
                    $('.js_city_name',pEle).val('');
                    $('.js_coordinate:eq('+index+')',pEle).trigger('change');
                    var keyArr=['号','路','县','区','市'];
                    if(tempI<keyArr.length){
                        var tempVale=myValue;
                        //console.log('tempVale'+tempVale+'tempI:'+tempI);
                        for(var i=tempI; i<keyArr.length;i++){
                            //console.log(keyArr[i]);
                            if(tempVale.indexOf(keyArr[i])!=-1){
                                var lastIndex=tempVale.indexOf(keyArr[i])+1;
                                tempVale=tempVale.substring(0,lastIndex);
                                break;
                            }
                        }
                        //console.log(tempVale);
                        tempI++;
                        setPlace(pEle,index,tempVale,tempI);
                        //移动端
                        if(isMobile){
                            var focusAnimate=$('.js_coordinate_address:eq('+index+')',pEle).attr('focusAnimate');
                            if(focusAnimate!='false'){
                                $('.focusMask').remove();
                                $('.js_coordinate_address:eq('+index+')',pEle).css({'left':'','top':'','width':''});
                                $('.js_coordinate_address:eq('+index+')',pEle).removeClass('focusinput');
                                $("html,body").stop().animate({scrollTop: 0}, 1);
                                setTimeout(function(){
                                    $('.js_coordinate_address_focus:eq('+index+')',pEle).trigger('focus');
                                    $('.js_coordinate_address_focus:eq('+index+')',pEle).trigger('blur');
                                },10);

                            }
                        }
                        //console.log('1');
                    }else{
                        //console.log(tempI+'----------------------');
                        //小提示层;
                        validatePop({
                            "popconMsg":"请填写正确的地址"
                        });
                        $('.js_coordinate:eq('+index+')',pEle).val('');
                        $('.js_coordinate:eq('+index+')',pEle).trigger('change');
                        //移动端
                        if(isMobile){
                            var focusAnimate=$('.js_coordinate_address:eq('+index+')',pEle).attr('focusAnimate');
                            if(focusAnimate!='false'){
                                $('.focusMask').remove();
                                $('.js_coordinate_address:eq('+index+')',pEle).css({'left':'','top':'','width':''});
                                $('.js_coordinate_address:eq('+index+')',pEle).removeClass('focusinput');
                                $("html,body").stop().animate({scrollTop: 0}, 1);
                                setTimeout(function(){
                                    $('.js_coordinate_address_focus:eq('+index+')',pEle).trigger('focus');
                                    $('.js_coordinate_address_focus:eq('+index+')',pEle).trigger('blur');
                                },10);

                            }
                        }
                        //console.log('2');
                    }


                }
            }
            var local = new BMap.LocalSearch(map, { //智能搜索
              onSearchComplete: resultsFun
            });
            local.search(myValue);
        }
        //定位标注
        function setMarker(pEle){
            var coordinateArr=$('.js_coordinate',pEle);
            var coordinateVal;
            var defaultPoint;
            var valArr=[];

            if(coordinateArr.length>1){
                var driveState=true;
            }else{
                var driveState=false;
            }
            $('.js_coordinate',pEle).each(function(index, el) {
                //定位 中心点
                coordinateVal=$(this).val();
                if(coordinateVal.length==0||coordinateVal==''){
                    driveState=false;
                    return;
                }
                //marker1
                markerEle = new BMap.Marker(mapCenterPoint);
                //circle1
                circleEle = new BMap.Circle(mapCenterPoint,8000,{strokeColor:"#5898ea",fillColor:'#ffffff', strokeWeight:1, setStrokeOpacity:1,setFillOpacity:0.5});

                coordinateVal=coordinateVal.split(',');
                defaultPoint=new BMap.Point(coordinateVal[0],coordinateVal[1]);
                //定位 marker
                map.addOverlay(markerEle);
                markerEle.setPosition(defaultPoint);
                valArr.push(defaultPoint);
            });
            //设置城市
            var getCity = new BMap.Geocoder();
            getCity.getLocation(valArr[0], function(rs){
                var city_name = rs.addressComponents.city;
                if(city_name=='澳门特别行政区'){
                    city_name= rs.addressComponents.district;
                    if(city_name=='氹仔'){
                        city_name='澳门离岛';
                    }
                }
                if(city_name=='西双版纳傣族自治州'){
                    city_name= '西双版纳';
                }

                if($('.js_city_name',pEle).length==0){
                    $('.js_coordinate:eq(0)',pEle).after('<input class="js_city_name" type="hidden" name="city_name" value="'+city_name+'" />');
                    $('.js_coordinate_address:eq(0)',pEle).on('focus',function(){
                        $('.js_city_name').val('');
                    })
                }else{
                    $('.js_city_name',pEle).val(city_name);
                }
            });
            if(driveState){
                map.clearOverlays();    //清除地图上所有覆盖物
                //两点开车
                var output = "参考行车距离：";
                var driver_distance;
                var searchComplete = function (results){
                    if (transit.getStatus() != BMAP_STATUS_SUCCESS){
                        return ;
                    }
                    var plan = results.getPlan(0);
                   // output += plan.getDuration(true) + "\n";                //获取时间
                    //output += "总路程为：" ;
                    output += plan.getDistance(true);             //获取距离
                    driver_distance=plan.getDistance(true);
                    if(/公里/.test(driver_distance)){
                        driver_distance=parseFloat(plan.getDistance(true))*1000;
                    }else{
                        driver_distance=parseFloat(plan.getDistance(true));
                    }
                }
                var transit = new BMap.DrivingRoute(map, {renderOptions: {map: map},
                    onSearchComplete: searchComplete,
                    onPolylinesSet: function(){
                        $('.drive-distance',pEle).html('<p><i class="ico ico-drive"></i>'+output+'</p><input class="js_driver_distance" type="hidden" name="driver_distance" value="'+driver_distance+'" />')
                }});
                transit.search(valArr[0], valArr[1]);
            }else{
                //单点定位
                map.setZoom(17);
                map.setCenter(defaultPoint);
                map.panTo(defaultPoint);
                if($('.js_radius_val',pEle).length>0){
                    var radiusSliderPeleFirst=$('.js_radius_val:eq(0)',pEle).closest('.radius-slider');
                    //半径  circle
                    map.addOverlay(circleEle);
                    circleEle.setCenter(defaultPoint);
                    var selectRadius=$('.js_radius_val',radiusSliderPeleFirst).val();
                    if(selectRadius=='false'){
                        selectRadius=0;
                    }
                    circleEle.setRadius(selectRadius);
                    selectRadiusFun(selectRadius,pEle,radiusSliderPeleFirst);
                   $('.js_radius_val',pEle).attr('eventReady',true);
                }
            }
            //回调
            if(locationCallFun){
                locationCallFun.call(this,locationEle);
            }
       }
    }
    //set zoom
   function selectRadiusFun(selectRadius,radiusPele,radiusSliderPele){
        var centerP=map.getCenter();
        var mapZoom=$('.js_radius_val',radiusSliderPele).attr('mapZoom');
        mapZoom=eval(mapZoom);
        map.setZoom(mapZoom[selectRadius]);
   }
    //半径 回调
    function slideCallMap(mapArgs){
        var radiusArr=mapArgs.radiusArr;
        var radiusPele=mapArgs.radiusPele;
        var radiusSliderPele=mapArgs.radiusSliderPele;
        var selectEle=$('.js_radius_ele',radiusSliderPele);
        var valEle=$('.js_radius_val',radiusSliderPele);
        var selectRadius=$(valEle).val();
        if($('.js_radius_val',radiusSliderPele).attr('eventReady')=='true'){
            if(selectRadius=='false'){
                selectRadius=0;
            }
            circleEle.setRadius(selectRadius);
            selectRadiusFun(selectRadius,radiusPele,radiusSliderPele);
        }
    }
    //半径
    if($('.js_radius_ele',locationEle).length>0){
        radiusSliderFun({'radiusArr':radiusArr,'radiusPele':locationEle,'slideCall':slideCallMap});
    }
}
function radiusSliderFun(args){
    var radiusArr=args.radiusArr;
    var radiusPele=$(args.radiusPele);
    var selectEle=$('.js_radius_ele',radiusPele);
    $(selectEle).each(function(index, el) {
        var selectEleCur=$(this);
        var radiusSliderPele=$(selectEleCur).closest('.radius-slider');
        var valEle=$('.js_radius_val',radiusSliderPele);
        var value=$(valEle).val()!='' ? $(valEle).val() : 8000;
        var selIndex=4;
        for(var i=0; i<radiusArr.length;i++){
            if(value==radiusArr[i].value){
                selIndex=i;
            }
        }
        $(selectEleCur).val(selIndex);
        $(selectEleCur).attr('max',radiusArr.length-1);
        $(selectEleCur).slider({
          create: function( event, ui ) {
            setHandle($(this).val(),$(this),radiusSliderPele);
          }
        });
        $(selectEleCur).on( "change", function( event ) {
            setHandle($(this).val(),$(this),radiusSliderPele);
        });

    });
    function setHandle(handVal,eventobj,radiusSliderPele) {
        var valEle=$('.js_radius_val',radiusSliderPele);
        $(valEle).val(radiusArr[handVal].value);
        $(valEle).trigger('change');
        if($('.ui-slider-handle .handle-text',radiusSliderPele).length==0){
            $('.ui-slider-handle',radiusSliderPele).html('<span class="handle-circle"></span><span class="handle-text">'+radiusArr[handVal].text+'</span>');
        }else{
            $('.ui-slider-handle .handle-text',radiusSliderPele).html(radiusArr[handVal].text);
        }
        var handleW=$('.ui-slider-handle',radiusSliderPele).outerWidth();
        if(handVal==0){
            $('.ui-slider-handle',radiusSliderPele).css({'margin-left':'0'});
        }else{
          if(handVal==radiusArr.length-1){
            $('.ui-slider-handle',radiusSliderPele).css({'margin-left':'-'+handleW+'px'});
          }else{
            $('.ui-slider-handle',radiusSliderPele).css({'margin-left':'-'+(handleW/2)+'px'});
          }
        }

        if(args.slideCall){
            if (typeof args.slideCall === 'function') {
                var slideCall=args.slideCall;
                slideCall.call(this,{'radiusArr':radiusArr,'radiusSliderPele':radiusSliderPele});
            }
        }
    }
}
//复制map调用
//coordinateMapCall({'pEleId':pEleId,'CallFun':CallFun,'radiusArray':radiusArray})
function coordinateMapCall(args){
    var pEle='#'+args.pEleId;
    $('.js_coordinate_address',pEle).attr('id',args.pEleId+'_coordinate_address');
    $('.js_coordinate',pEle).attr('id',args.pEleId+'_coordinate');
    $('.js_coordinate_map',pEle).attr('id',args.pEleId+'_coordinate_map');
    $('.js_radius_ele',pEle).attr('id',args.pEleId+'_radius_ele');
     //select,radio,checkbox 设置默认选择值
    selvalueFun();
    setLocation({'locationEle':pEle,'radiusArray':args.radiusArray,'CallFun':args.CallFun});
}
function GeocoderFun(pt,CallFun){
    var geoc = new BMap.Geocoder();
    geoc.getLocation(pt, function(rs){
        var addComp = rs.addressComponents;
        var address=addComp.city+addComp.district+addComp.street+addComp.streetNumber;
        CallFun.call(null,address);
    });
}
function browseSitFun(args){
    var locationEle=args.locationEle;
    if($(locationEle).length>0){
        var coordinateEle=$(locationEle);
        //初始化
        var coordinateVal=$('.js_coordinate',coordinateEle).val();
        if(coordinateVal.length==0){
            var geolocation = new BMap.Geolocation();
            geolocation.getCurrentPosition(function(r){
                if(this.getStatus() == BMAP_STATUS_SUCCESS){
                    //设置coordinate
                    var curVal=$('.js_coordinate:eq(0)',coordinateEle).val();
                    if(curVal.length==0){
                        $('.js_coordinate:eq(0)',coordinateEle).val(r.point.lng+','+r.point.lat);
                        GeocoderFun(r.point,function(address){
                            $('.js_coordinate_address:eq(0)',coordinateEle).val(address);
                        });
                    }

                }else {

                    //设置coordinate
                    var curVal=$('.js_coordinate:eq(0)',coordinateEle).val();
                    if(curVal.length==0){
                        $('.js_coordinate:eq(0)',coordinateEle).val(shanghai.lng+','+shanghai.lat);
                        GeocoderFun(shanghai,function(address){
                            $('.js_coordinate_address:eq(0)',coordinateEle).val(address);
                        });
                    }
                }
            },{enableHighAccuracy: true})

        }
    }

}
function browseCookieSitFun(){
    var shanghai=new BMap.Point(121.491, 31.233);
    var geolocation = new BMap.Geolocation();
    geolocation.getCurrentPosition(function(r){
        if(this.getStatus() == BMAP_STATUS_SUCCESS){
            var curLocationPoint=r.point.lng+','+r.point.lat;
            setCookie_g('curLocationPoint',curLocationPoint,1000);
            GeocoderFun(r.point,function(address){
                var curLocationAddress=address;
                setCookie_g('curLocationAddress',curLocationAddress,1000);
            })
        }else {
            var curLocationPoint=shanghai.lng+','+shanghai.lat;
            setCookie_g('curLocationPoint',curLocationPoint,1000);
            GeocoderFun(shanghai,function(address){
                var curLocationAddress=address;
                setCookie_g('curLocationAddress',curLocationAddress,1000);
            })
        }
    },{enableHighAccuracy: true});
}


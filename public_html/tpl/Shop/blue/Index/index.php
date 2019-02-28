<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<if condition="$config['site_favicon']">
		<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
	</if>
    <title>{pigcms{$config.shop_alias_name}_{pigcms{$now_city.area_name}_{pigcms{$config.seo_title}</title>
    <if condition="$now_area">
    	<meta name="keywords" content="{pigcms{$now_area.area_name},{pigcms{$now_circle.area_name},{pigcms{$config.seo_keywords}" />
    <else />
    	<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    </if>
	<meta name="description" content="{pigcms{$config.seo_description}" />
	<meta charset="utf-8">
	<link href="{pigcms{$static_path}css/shop_pc.css" rel="stylesheet"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_path}js/common.js"></script>
    <script src="{pigcms{$static_public}js/jquery.cookie.js"></script>
	<!--[if lte IE 9]>
	<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
	<script src="{pigcms{$static_path}js/html5shiv.min.js"></script>
	<![endif]-->
</head>
	<body>
		<!-- 公用导航 -->
		<div class="header_top">
			<include file="Public:header_top"/>
		</div>
		<!-- 公用导航 -->
		<section class="Fast" style="background: url({pigcms{$static_path}images/indexb_05.jpg) center no-repeat;">
			<div class="logo">
				<div class="w1200">
					<a href="/"><img src="{pigcms{$config.site_logo}"/></a>
				</div> 
			</div>
			<div class="search">
				<h2>{pigcms{$config.shop_alias_name}，让生活更简单</h2>
				<div class="clr">
					<div class="text fl">
						<input type="text" readonly="readonly" value="{pigcms{$now_city['area_name']}" class="elastic fl" id="city_name">
						<input type="text" placeholder="请输入您的收货地址（写字楼，小区或者学校）" class="fr" id="search-con">
                    </div>
					<div class="fr"><button class="search-btn">搜 索</button></div>
                    <div class="search-show s-item search-sug s-hide">
                        <ul class="search-sug">
							<!--li data-name="金隅嘉华大厦" data-lat="40.042268" data-lng="116.314645" class="demo"><i></i>金隅嘉华大厦&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:14px;color:red;">(此演示地点含店铺演示数据)</span></li-->
                        </ul>
                    </div>
				</div>
				<!-- 地址 -->
				<div class="place_list">
					<ul class="place_top clr">
						<li class="on"><span>猜你在</span><i>{pigcms{$now_city['area_name']}</i></li>
						<volist name="all_city" id="first">
						<li class="click" data-cat_id="{pigcms{$key}">{pigcms{$key}</li>
						</volist>
					</ul>
					<div class="place_end">
						<volist name="all_city" id="rowset">
						<dl class="clr place_end-{pigcms{$key}" data-cat_id="{pigcms{$key}">
							<p>{pigcms{$key}</p>
							<volist name="rowset" id="vo">
							<dd>{pigcms{$vo['area_name']}</dd>
							</volist>
						</dl>
						</volist>  
					</div>
				</div>
		<!-- 地址 -->         
			</div>
		</section>

		<section class="Client">
			<div class="Client_top">
				<h2>让生活<i>更简单</i></h2>
				<p>微信扫描二维码关注或下载手机客户端，随时随地查看身边{pigcms{$config.shop_alias_name}</p>
			</div>
			<div class="Client_end">
				<ul class="clr">
					<li>
						<a href="#">
							<img src="{pigcms{$static_path}images/pho_15.png">
							<p>iPhone</p>
						</a>
					</li>
					<li>
						<a href="#">
							<img src="{pigcms{$static_path}images/pho_17.png">
							<p>Android</p>
						</a>
					</li>
					<li style="margin-right:0px;">
						<a href="javascript:void(0)">
							<img src="{pigcms{$config.wechat_qrcode}" class="img">
							<p>微信扫描二维码</p>
						</a>
					</li>
				</ul>
			</div>  
		</section>
        <div id="hidden_map"></div><input id="cityLL" type="hidden" />
        <include file="Public:footer"/>
	</body>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
    <script type="text/javascript">var is_google_map = "{pigcms{$config.google_map_ak}"</script>
    <else />
<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2&s=1"></script>
</if>
<script>
    var currentCity = '{pigcms{$now_city.area_name}';
    var currentCityId = '{pigcms{$now_city.area_id}';
    var allcitiesname = {pigcms{:json_encode($allCityName)};
    var cities = {pigcms{:json_encode($city_list)};
    function locationCity(result){
        var cityName = result.name;
        if ($.inArray(cityName, allcitiesname)<0 && $.inArray(cityName.replace("市", ""), allcitiesname)<0) {
			currentCity = $(".current-city").html();
        } else if ($.inArray(cityName, allcitiesname)>0) {
            $(".current-city").html(cityName);
            $("#guess_city").html(cityName);
            currentCity = cityName;
        } else {
            currentCity = $.trim(cityName.replace("市", ""));
            $(".current-city").html(currentCity);
            $("#guess_city").html(currentCity);
        }
		currentCityId = getCityIdByName(currentCity);
    }
    if(typeof (is_google_map)!="undefined"){
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

            map = new google.maps.Map(document.getElementById('hidden_map'), {
                center:pos,
                zoom: 15
            });

            var request = {
                location: pos,
                radius: '200'
            };

            service = new google.maps.places.PlacesService(map);
            service.nearbySearch(request, callback);

            function callback(results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    var result = {name:results[0].name};
                    locationCity(result);
                }
            }
        }
    }else{
        var myCity = new BMap.LocalCity();
        myCity.get(locationCity);
    }


    var line_tpl = '<li data-name="{name}" data-lat="{lat}" data-lng="{long}"><i></i>{light-name}</li>';
    var searchUrl = "{pigcms{:U('Shop/Index/suggestion')}";
    $('#search-con').keyup(function(){
        search();
    });
    $('.search-btn').click(function(){
        search();
    });
    $('#search-con').click(function(event){
        var e=event || window.event;
        if (e && e.stopPropagation) {
            e.stopPropagation();
        } else {
            e.cancelBubble=true;
        }
        $(".search-sug").removeClass('s-hide');
    });


    function search() {
        var query = $('#search-con').val();
        if (query == "") {
            return false;
        }
       if(typeof(is_google_map)!="undefined"){


           var cityPos = $('#cityLL').val();
           cityPos = cityPos.toString();
           cityPos =  cityPos.split(',');
           pos = {lng:parseFloat(cityPos[0]),lat:parseFloat(cityPos[1])};
           map = new google.maps.Map(document.getElementById('hidden_map'), {
               center:pos,
               zoom: 15
           });

           var request = {
               query: query,
               location:pos,
               radius: '200'
           };

           service = new google.maps.places.PlacesService(map);
           service.textSearch(request, callback);

           function callback(results, status) {
               if (status == google.maps.places.PlacesServiceStatus.OK) {
                   if (results.length>0) {
                       $(".search-sug").empty();
                       var subHtml = '';
                       var html = '';
                       for (var ii= 0; ii<results.length;ii++) {
                           html = '<li data-name='+ results[ii].name+' data-lat='+results[ii].geometry.location.lat()+' data-lng='+results[ii].geometry.location.lng()+'><i></i>'+results[ii].name+'</li>';
                           subHtml += html;
                       }
                   } else {
                       var subHtml = '<li data-name="" data-lat="0" data-lng="0" class="demo">很抱歉，未找到相关地址：<br/><span style="font-size:14px;color:gray;">请检查地址是否正确或尝试只输入写字楼、小区或学校试试</span></li>';
                   }
                   $(".search-sug").html(subHtml).removeClass("s-hide");
               }
           }
        }else{
           goSearchUrl  = searchUrl + '&query=' + encodeURIComponent(query);
           goSearchUrl += '&region=' + encodeURIComponent($('#city_name').val());
           $.get(goSearchUrl, function(json) {
               if (json.error_code == 0) {
                   $(".search-sug").empty();
                   var subHtml = '';//'<li data-name="金隅嘉华大厦" data-lat="40.042268" data-lng="116.314645" class="demo"><i></i>金隅嘉华大厦&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:14px;color:red;">(此演示地点含店铺演示数据)</span></li>';
                   for (var i= 0,item; item=json.data[i++];) {
                       var html = line_tpl;
                       for (var key in item) {
                           html = html.replace(new RegExp('{'+key+'}',"gm"), item[key]);
                       }
                       subHtml += html;
                   }
               } else {
                   var subHtml = '<li data-name="" data-lat="0" data-lng="0" class="demo">很抱歉，未找到相关地址：<br/><span style="font-size:14px;color:gray;">请检查地址是否正确或尝试只输入写字楼、小区或学校试试</span></li>';
               }
               $(".search-sug").html(subHtml).removeClass("s-hide");
           }, 'json');
        }

    }
    //谷歌地图使用
    function citysearch(name) {
        var request = {
            query: name,
            location: {lng: 117.11, lat: 37.55},
            radius: '200'
        };
        service = new google.maps.places.PlacesService(map);
        service.textSearch(request, callback);

        function callback(results, status) {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
              $('#cityLL').val(results[0].geometry.location.lng()+','+results[0].geometry.location.lat());
            }
        }
    }
    function getCityIdByName(name){
        for (var key in cities) {
            if (cities[key]['area_name'] == name) {
                return cities[key]['area_id'];
            }
        }

        return 0;
    }
    $(".selectCity").bind("click",function(){
        var that = $(this);
        currentCity = that.attr('data-city-name');
        currentCityId = that.attr('data-city-id');
        $(".current-city").html(currentCity);
        $("#muti-aois").fadeToggle(1000);
		$('#search-sug').empty();
    });

    var JmmpUrl = '{pigcms{$referer}';
	$(document).on('click', ".search-sug > li", function(){
        var that = $(this);
        if(that.attr('data-lng') == 0){
            return false;
        }
        $.cookie('shop_select_address', that.attr('data-name'),{expires:700,path:"/"});
        $.cookie('shop_select_lng', that.attr('data-lng'),{expires:700,path:"/"});
        $.cookie('shop_select_lat', that.attr('data-lat'),{expires:700,path:"/"});
		$.cookie('shop_select_params', null, {expires:700,path:'/'});
        location.href = JmmpUrl;
    });

    /*收货地址添加*/
    function addLocationHistory(address,lng,lat){
    	var hs = address+'*!*&*'+lng+'*!*&*'+lat;
    	var history = $.cookie('location_history');
    	if(history){
    		var hisArr = history.split("$*!*&*$").slice(0,4);
    		$.cookie("location_history",hs+"$*!*&*$"+hisArr.join('$*!*&*$'),{expires:700,path:"/"});
    	}else{
    		$.cookie("location_history",hs,{expires:700,path:"/"}); 
    	}
    }
	// 清除边框
	$(".Client_end li:last-child").css("margin-right","0px");
	
	// 城市滚动
	$(document).on('click','.place_top .click',function(){
		$('.place_end').animate({scrollTop:$('.place_end-'+$(this).data('cat_id')).offset().top-$('.place_end').offset().top+$('.place_end').scrollTop()},500) ;
	});
	
	//选中城市
	$(".elastic").click(function(){
		if ($(".place_list").is(':hidden')) {
			$(".place_list").slideDown();  
		} else {
			$(".place_list").slideUp();
		}
		$('.search-sug').empty();
	});
	$(".place_end dl dd").click(function(){
        if(typeof(is_google_map)!="undefined"){
            citysearch($(this).text());
        }
		$("input.elastic").val($(this).text());
		$(".place_list").slideUp(); 
	});
</script>
</html>
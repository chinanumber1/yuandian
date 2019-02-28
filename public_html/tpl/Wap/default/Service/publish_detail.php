<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>用户发布需求</title>
		<meta content="yes" name="apple-mobile-web-app-capable">
		<meta content="yes" name="apple-touch-fullscreen">
		<meta content="telephone=no" name="format-detection">
		<meta content="black" name="apple-mobile-web-app-status-bar-style">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0,user-scalable=no">
		<meta name="baidu-site-verification" content="Rp99zZhcYy">
		<meta name="keywords" content="">
		<meta name="description" content="">
		<link href="{pigcms{$static_path}service/css/basic.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
		<link href="{pigcms{$static_path}service/css/demand.css" rel="stylesheet" type="text/css"/>
		<style>
			.mapaddress ul li .mapaddress-body{padding-top:0px!important;}
            .add-imglist-col4 .add-imglist1 .cell-del a {
                display: inline-block;
                position: absolute;
                z-index: 10000;
                left: -19px;
                top: -4px;
            }
            .add-imglist1 .cell-img img {
                /* width: 70.5px!important; */
                /* height: 70.5px!important; */
                display: block;
                width: 94%!important;
                height: 5.5rem!important;
            }   
		</style>
	</head>
    <body class="bg-gray2 ios">
    
    <!-- <include file="Service:right_nav"/> -->
   
<div class="pagewrap" id="mainpage">
    <!-- <include file="Service:header_top"/> -->
    <div class="main bg-gray2" style="margin-top: 0px; margin-bottom: 0px;">

        <div class="show-top-bar-wrap">
            <div class="show-top-bar js_topfixed js_map_topfixed" >
                <div class="show-bar">
                    一键发布需求，服务方便快捷
                    <!-- <i class="ico ico-play2"></i> -->
                </div>
                <div class="demand-form-percent">
                    <div class="percent">
                        <span class="per" style="width: 5%;"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="demand-form " id="demand_form">
            <form action="{pigcms{:U('Service/publish_data')}" id="publish_demand_form" method="post" formdataarr="demandForm_Arr">
                <div class="demand-form-list demand-form-newstyle">
                    <div class="form-list1 js_formInner">
                    <input type="hidden" name="cid" value="{pigcms{$_GET['cid']}">

                    <volist name="now_category['cat_field']" key="k" id="vo" >

                        <if condition="$vo.type eq 1">

                            <div class="li <if condition="$k eq 1">show</if>" data-type="{pigcms{$vo.type}" id="show_{pigcms{$k}" >
                                <label class="lab-title">
                                    <span class="long-title">{pigcms{$vo.name}</span>
                                </label>
                                <div class="ele-wrap">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[alias_name]" value="{pigcms{$vo.alias_name}">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[type]" value="{pigcms{$vo.type}">
                                    <input class="form-control next_value_{pigcms{$k}" name="{pigcms{$vo.key_value}[value]" value="">
                                </div>
                            </div>

                        <elseif condition="$vo.type eq 2"/>

                            <if condition="$vo.is_desc eq 1">
                                <div class="li <if condition="$k eq 1">show</if>" data-type="{pigcms{$vo.type}" id="show_{pigcms{$k}" >
                                    <label class="lab-title">
                                        <span class="long-title">{pigcms{$vo.name}</span>
                                    </label>
                                    <div class="ele-wrap">
                                        <div class="proxyinput_group proxyinput_level">
                                            <input type="hidden" name="{pigcms{$vo.key_value}[alias_name]" value="{pigcms{$vo.alias_name}">
                                            <input type="hidden" name="{pigcms{$vo.key_value}[type]" value="{pigcms{$vo.type}">
                                            <volist name="vo['opt']" id="optvo">
                                                <label class="proxyinput proxy-radio " colors="#7ab6e9">
                                                    <i class="ico-checked"></i>
                                                    <span class="h0hidden">
                                                        <input class="js_validate next_value_{pigcms{$k}" name="{pigcms{$vo.key_value}[value]" type="radio" value="{pigcms{$optvo}">
                                                    </span>
                                                    <div class="level-title s4" style="background:#7ab6e9!important">
                                                        <span>{pigcms{$optvo}</span>
                                                    </div>
                                                    <div class="desc">
                                                        <div class="desc-con">
                                                            <div class="need-desc"><php> echo $vo['desc'][$key];</php></div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </volist>

                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>

                            <else/>
                                <div class="li <if condition="$k eq 1">show</if>" data-type="{pigcms{$vo.type}" id="show_{pigcms{$k}" >
                                    <label class="lab-title">
                                        <span class="long-title">{pigcms{$vo.name}</span>
                                    </label>
                                    <div class="ele-wrap">
                                        <div class="proxyinput_group ">
                                            <input type="hidden" name="{pigcms{$vo.key_value}[alias_name]" value="{pigcms{$vo.alias_name}">
                                            <input type="hidden" name="{pigcms{$vo.key_value}[type]" value="{pigcms{$vo.type}">
                                            <volist name="vo['opt']" id="optvo">
                                                <label class="proxyinput proxy-radio">
                                                    <span class="h0hidden"><input class="js_validate next_value_{pigcms{$k}" onclick="dateShow(this,{pigcms{$k},'none')" name="{pigcms{$vo.key_value}[value]" type="radio" value="{pigcms{$optvo}"></span>{pigcms{$optvo}
                                                </label>
                                            </volist>
                                            <if condition="$vo.isinput eq 1">
                                                <label class="proxyinput proxy-radio other">
                                                    <span class="h0hidden">
                                                        <input class="js_validate next_value_{pigcms{$k}" onclick="dateShow(this,{pigcms{$k},'none')" name="{pigcms{$vo.key_value}[value]" type="radio" value="inputdesc" placeholder="其他">
                                                    </span>
                                                    <input class="other_text radio_other_text next_value_time_desc_{pigcms{$k}" placeholder="请填写具体内容" name="{pigcms{$vo.key_value}[desc]" other="1" type="text" value="">
                                                </label>
                                            </if>
                                            

                                            <if condition="$vo.istime eq '1'">
                                                <label class="proxyinput proxy-radio other">
                                                    <span class="h0hidden">
                                                        <input other="2" class="radio_other js_validate next_value_{pigcms{$k}" name="{pigcms{$vo.key_value}[value]" type="radio" onclick="dateShow(this,{pigcms{$k},'show')" value="time" placeholder="选择具体日期"></span>
                                                    <span class="other-txt">选择具体日期</span>
                                                </label>

                                                <div class="clear"></div>

                                                <div class="js_textTimeP hidden data_show_{pigcms{$k}">
                                                    <div class="bl_calendar_ym">
                                                        <input type="text" class="js_textTime_date bl_calendar_ym_txt form-control js_no_error next_value_time_date_{pigcms{$k}" name="{pigcms{$vo.key_value}[date]" calendarstart="<?php echo date("Y-n-j");?>" valiname="datetime_time_other" placeholder="年/月/日" value="">
                                                        <i></i>
                                                    </div>
                                                    <div class="bl_calendar_ym">
                                                        <input type="text" class="js_textTime_time bl_calendar_ym_txt form-control js_no_error next_value_time_fen_{pigcms{$k}" name="{pigcms{$vo.key_value}[minute]" datekey="th-tm" placeholder="时/分" value="">
                                                        <i></i>
                                                    </div>
                                                </div>

                                            </if>

                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </if>
                            
                        <elseif condition="$vo.type eq 3"/>

                            <div class="li <if condition="$k eq 1">show</if>" data-type="{pigcms{$vo.type}" id="show_{pigcms{$k}" checksuccess="false" welog_check="true">
                                <label class="lab-title">
                                    <span class="long-title">{pigcms{$vo.name}</span>
                                </label>
                                <div class="ele-wrap">
                                    <div class="proxyinput_group">
                                        <input type="hidden" name="{pigcms{$vo.key_value}[alias_name]" value="{pigcms{$vo.alias_name}">
                                        <input type="hidden" name="{pigcms{$vo.key_value}[type]" value="{pigcms{$vo.type}">
                                        <volist name="vo['opt']" id="optvo">
                                            <label class="proxyinput proxy-checkbox">
                                                <span class="h0hidden">
                                                    <input class="js_validate next_value_{pigcms{$k}" name="{pigcms{$vo.key_value}[value][]" type="checkbox" value="{pigcms{$optvo}"></span>{pigcms{$optvo}
                                            </label>
                                        </volist>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>

                        <elseif condition="$vo.type eq 4"/>
                        

                            <div class="li secondcheck <if condition="$k eq 1">show</if>" data-type="{pigcms{$vo.type}" id="show_{pigcms{$k}" >
                                <label class="lab-title">
                                    <span class="long-title">{pigcms{$vo.name}</span>
                                </label>
                                <input type="hidden" name="{pigcms{$vo.key_value}[alias_name]" value="{pigcms{$vo.alias_name}">
                                <input type="hidden" name="{pigcms{$vo.key_value}[type]" value="{pigcms{$vo.type}">
                                <div class="ele-wrap">
                                    <div class="js_textTimeP">
                                        <div class="bl_calendar_ym">
                                            <input type="text" readonly="" class="js_textTime_date bl_calendar_ym_txt form-control next_value_{pigcms{$k}" calendarstart="<?php echo date("Y-n-j");?>" placeholder="年/月/日" name="{pigcms{$vo.key_value}[value][time_start]" value=""> <i></i>
                                        </div>
                                        <div class="bl_calendar_ym">
                                            <input type="text" readonly="" class="js_textTime_time bl_calendar_ym_txt form-control next_value_{pigcms{$k}" other="2" datekey="th-tm" placeholder="时/分" name="{pigcms{$vo.key_value}[value][time_end]" value=""><i></i>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>

                        <elseif condition="$vo.type eq 5"/>

                            <div class="li textAreaTpl <if condition="$k eq 1">show</if>" data-type="{pigcms{$vo.type}" id="show_{pigcms{$k}" style="" welog_check="true">
                                <label class="lab-title"> <i class="ico ico-s-descri"></i>
                                    <span class="long-title">{pigcms{$vo.name}</span>
                                </label>
                                <input type="hidden" name="{pigcms{$vo.key_value}[alias_name]" value="{pigcms{$vo.alias_name}">
                                <input type="hidden" name="{pigcms{$vo.key_value}[type]" value="{pigcms{$vo.type}">
                                <div class="ele-wrap">
                                    <textarea class="form-control next_value_{pigcms{$k}" placeholder="详细说明您的服务期望和特殊要求，商家才能针对性地提供更合适的服务方案和报价。" name="{pigcms{$vo.key_value}[value]"></textarea>
                                </div>
                            </div>

                        <elseif condition="$vo.type eq 6"/>

                            <div class="li <if condition="$k eq 1">show</if>" data-type="{pigcms{$vo.type}" id="show_{pigcms{$k}">
                                <label class="lab-title"> <i class="ico ico-s-coordinate"></i><span class="long-title">{pigcms{$vo.name}</span></label>
                                <input type="hidden" name="{pigcms{$vo.key_value}[alias_name]" value="{pigcms{$vo.alias_name}">
                                <input type="hidden" name="{pigcms{$vo.key_value}[type]" value="{pigcms{$vo.type}">
                                <div class="ele-wrap ">
                                    <style>
                                        .adress_input input:disabled{
                                            background: #fff!important;
                                            color: #404040!important;
                                            opacity: 1;
                                            -webkit-text-fill-color: #404040;
                                        }
                                    </style>

                                    <div class="adress_input" onclick="address_click('address');">
                                        <input class="form-control js_coordinate_address next_value_{pigcms{$k}" placeholder="大概位置（如：街道地址）" id="address" disabled="disabled" type="text" value="">
                                    </div>
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address]" id="address_name" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_lng]" id="address_lng" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_lat]" id="address_lat" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_area_id]" id="address_area_id" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_area_name]" id="address_area_name" value="">
                                </div>
                                <div class="clear"></div>
                            </div>

                        <elseif condition="$vo.type eq 7"/>
                            <style>
                                .adress_input input:disabled{
                                    background: #fff!important;
                                    color: #404040!important;
                                    opacity: 1;
                                    -webkit-text-fill-color: #404040;
                                }
                            </style>

                            <div class="li coordinate-ele js_coordinate_ele coordinate-two <if condition="$k eq 1">show</if>" data-type="{pigcms{$vo.type}" id="show_{pigcms{$k}" style="" checksuccess="false" welog_check="true" mapstate="true">
                                <label class="lab-title"> <i class="ico ico-s-coordinate"></i>
                                    <span class="long-title">{pigcms{$vo.name}</span>
                                </label>
                                <input type="hidden" name="{pigcms{$vo.key_value}[alias_name]" value="{pigcms{$vo.alias_name}">
                                <input type="hidden" name="{pigcms{$vo.key_value}[type]" value="{pigcms{$vo.type}">
                                <div class="ele-wrap ">
                                    <div class="adress_input" onclick="address_start_ckick('address_start');">
                                        <input class="form-control js_coordinate_address" placeholder="出发地" id="address_start" type="text" disabled="disabled" value="">
                                    </div>

                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_start]" id="address_start_name" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_start_lng]" id="address_start_lng" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_start_lat]" id="address_start_lat" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_start_area_id]" id="address_start_area_id" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_start_area_name]" id="address_start_area_name" value="">
                                </div>
                                <div class="ele-wrap  second-ele">
                                    <div class="adress_input" onclick="address_end_click('address_end');">
                                        <input class="form-control js_coordinate_address" placeholder="目的地" id="address_end" type="text" disabled="disabled" value="">  
                                    </div>
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_end]" id="address_end_name" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_end_lng]" id="address_end_lng" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_end_lat]" id="address_end_lat" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_end_area_id]" id="address_end_area_id" value="">
                                    <input type="hidden" name="{pigcms{$vo.key_value}[value][address_end_area_name]" id="address_end_area_name" value="">
                                </div>
                                <div class="clear"></div>
                            </div>
                        </if>
                    </volist>
                    </div>
                </div>
                
                <div class="demand-btn-wrap1">
                    <input class="btn btn-blue control-lg hidden" id="js_publish_demand_prev" onclick="publish_demand_prev()" type="button" value="上一步">
                    <input class="btn btn-orange control-lg " id="js_publish_demand_next" onclick="publish_demand_next()" type="button" value="下一步">
                    <input class="btn btn-orange control-lg hidden" id="js_publish_demand_submit" onclick="publish_demand_next()" type="button" value="提  交">
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{pigcms{$static_path}service/js/jquery-2.1.4.js"></script>
<script src="{pigcms{$static_path}service/js/basic.js"></script>
<script src="{pigcms{$static_path}service/js/json2.js"></script>
<script src="{pigcms{$static_path}service/js/popsel.js"></script>
<script src="{pigcms{$static_path}service/js/calendar_year_mon.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/common.js?002"></script>
<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/address_9d295cd.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css">
    <style>
                .mask {
                    position: absolute;
                    position: fixed;
                    z-index: 1000;
                    top: 0;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    display: none;
                    background: rgba(0,0,0,0.5);
                }
                .mapaddress {
                    height: 91%;
                    overflow-y: scroll;
                    z-index: 99999;
					width:100%;
                }
            </style>            
<div id="searchAddress" style="z-index:10001; background:#ffffff; display: none;  position:fixed; left:0; top:0%; width:100%; height:100%; margin-top: -0px;">
    <div class="" style=" width: 100%; background color: red; height: 50px; text-align: center;">
        <input type="text" placeholder="请输入小区、大厦或学校" onkeyup="bb(this.value)" value="" style="width:75%;height:35px;line-height:35px;font-size:14px;margin-top:12px;border:1px solid #ccc;padding-left:5px;" id="searchAddressTxt"/>
        <button class="btn show_btn" style="height: 35px; font-size: 15px; width: 60px; margin-top: -3px;" onclick="$('#searchAddress').hide();$('#searchAddressTxt').val('');">关闭</button>
    </div>
    
    <div class="mapaddress">
        <ul id="addressShow" class="addressShow"></ul>
    </div>
</div>

<script>
    function dateShow(obj,k,type){
        if(type == 'show'){
            $(".data_show_"+k).removeClass('hidden');
        }else{
            $(".data_show_"+k).addClass('hidden');
        } 
    }
	
    var address = '{pigcms{$address}';
    var timeout = 0;
    var addressType = '';
    function address_start_ckick(type){
        $('.mask').show();
       $("#searchAddress").css('display','block');
       addressType = type;
    }

    function address_end_click(type){
        $('.mask').show();
        $("#searchAddress").css('display','block');
        addressType = type;
    }
    function address_click(type){
        $('.mask').show();
        $("#searchAddress").css('display','block');
        addressType = type;
    }

    function bb(a){
        var address = a;
        if(address.length>0 && address !== '请输入小区、大厦或学校'){
            $('.addressShow').empty();
            clearTimeout(timeout);
            timeout = setTimeout("search('"+address+"')", 500);
        }
    }

    $('.addressShow').delegate("li","click",function(){ 
        var sname = $(this).attr("sname");
        var lng = $(this).attr("lng");
        var lat = $(this).attr("lat");

        
        var cityMatchingUrl = "{pigcms{:U('Service/cityMatching')}";
        $.get(cityMatchingUrl, {'lng':lng,'lat':lat}, function(data){
            if(data.error == 1){
                $("#"+addressType).val(sname);
                $("#"+addressType+"_name").val(sname);
                $("#"+addressType+"_lng").val(lng);
                $("#"+addressType+"_lat").val(lat);
                $("#"+addressType+"_area_id").val(data.area_id);
                $("#"+addressType+"_area_name").val(data.area_name);

            }else{
                layer.open({
                    content: data.msg
                    ,btn: ['确定']
                });
            }
        },'json');

        // console.log(addressType+"_lng");
         $('.mask').hide();
        $("#searchAddress").css('display','none');
    }); 


    $(document).ready(function(){
		// $('#searchAddressTxt').width($(window).width()-20-80);
		$('.show_btn').show();
        $('.show_btn').css('opacity',1).css('background','#716D6D').css('color','#fff');

        // $('.js_coordinate_address').blur();
        // var geolocation = new BMap.Geolocation();
        // geolocation.getCurrentPosition(function(r){
			// if(this.getStatus() == BMAP_STATUS_SUCCESS){
			// 	getPositionInfo(r.point.lat,r.point.lng);
			// } else {
			// 	alert('failed'+this.getStatus());
			// }
        // },{enableHighAccuracy: true});
        getUserLocation({useHistory:false,okFunction:'getIframe'});
    });

    function getIframe(userLonglat,userLong,userLat){
        geoconv('realResult',userLong,userLat);
    }
    function realResult(result){
        var lng = result.result[0].x;
        var lat = result.result[0].y;
        getPositionInfo(lat, lng);
    }

    function search(address)
    {
        $.get('index.php?g=Index&c=Map&a=suggestion', {query:address}, function(data){
            if(data.status == 1){
                getAdress(data.result);
            }
        });
    }
    function getPositionLocation(result)
    {
        if(result.status == 0){
            result = result.result;
            getPositionInfo(result.location.lat,result.location.lng);
        }else{
            layer.open({
                content: '获取位置失败！'
                ,btn: ['确定']
            });
        }
    }
    function getPositionInfo(lat,lng){
        $.getJSON('https://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&callback=renderReverse&location='+lat+','+lng+'&output=json&pois=1&callback=getPositionAdress&json=?');
    }
    function getPositionAdress(result){
        if(result.status == 0){
            result = result.result;
            var re = [];
            re.push({'name':result.sematic_description,'address':result.formatted_address,'long':result.location.lng,'lat':result.location.lat});
            for(var i in result.pois){
                re.push({'name':result.pois[i].name,'address':result.pois[i].addr,'long':result.pois[i].point.x,'lat':result.pois[i].point.y});
            }
            getAdress(re);
        }else{
            layer.open({
                content: '获取位置失败！'
                ,btn: ['确定']
            });
        }
    }

    function getAdress(re){
        $(".addressShow").html('');
        var addressHtml = '';
        for(var i=0;i<re.length;i++){
            if(re[i]['long'] != '0'){
              addressHtml += '<li lng="'+re[i]['long']+'" lat="'+re[i]['lat']+'" sug_address="'+re[i]['name']+'" address="'+re[i]['address']+'" sname="'+re[i]['name']+'" class="addresslist">';
              addressHtml += '<div class="mapaddress-title"> <span class="icon-location" data-node="icon"></span> <span class="recommend"> '+(i == 0 ? '[推荐位置]' : '')+'   '+re[i]['name']+' </span> </div>';
              addressHtml += '<div class="mapaddress-body"> '+re[i]['address']+' </div>';
              addressHtml += '</li>';
            }
        }
        $('.addressShow').html(addressHtml);
    }


    $('.proxyinput_group.proxyinput_level label').click(function(e){
       
        $(this).addClass('active').siblings('label').removeClass('active');
        
    });
</script>

<script>
    var cat_field_count = "{pigcms{$cat_field_count}";
    var car_field_sum = 1;
    var bfb = 0;
    function publish_demand_next(){
        var uid = "{pigcms{$user_session['uid']}";
        
    

        if(!uid){

            layer.open({
                content: '请先登录'
                ,btn: ['去登录']
                ,yes: function(index){
                    location.href = "{pigcms{:U('Login/index')}";
                }
            });
            return false;
        }

        var type_val = $("#show_"+car_field_sum).data('type');

        if(type_val == 1){
            var val = $(".next_value_"+car_field_sum).val();
            if(!val){
                layer.open({
                    content: '内容不可以为空'
                    ,skin: 'msg'
                    ,time: 2 
                });
                return false;
            }
        }else if(type_val == 2){
            
            

            if($(".next_value_"+car_field_sum+":checked").val() == 'inputdesc'){
               if(!$(".next_value_time_desc_"+car_field_sum).val()){
                    layer.open({
                        content: '输入的内容不可以为空'
                        ,skin: 'msg'
                        ,time: 2 
                    });
                    return false;
               }
              
            }


            if($(".next_value_"+car_field_sum+":checked").val() == 'time'){
               if(!$(".next_value_time_date_"+car_field_sum).val()){
                    layer.open({
                        content: '时间不可以为空'
                        ,skin: 'msg'
                        ,time: 2 
                    });
                    return false;
               }
               if(!$(".next_value_time_fen_"+car_field_sum).val()){
                    layer.open({
                        content: '时间不可以为空'
                        ,skin: 'msg'
                        ,time: 2 
                    });
                    return false;
               }
            }

            if(!$(".next_value_"+car_field_sum).is(':checked')) {
                layer.open({
                    content: '请选择一个值'
                    ,skin: 'msg'
                    ,time: 2 
                });
                return false;
            }

        }else if(type_val == 3){
            if(!$(".next_value_"+car_field_sum).is(':checked')) {
                layer.open({
                    content: '请选择一个值'
                    ,skin: 'msg'
                    ,time: 2 
                });
                return false;
            }
        }else if(type_val == 4){

            if(!$(".next_value_"+car_field_sum).val()){
                layer.open({
                    content: '内容不可以为空'
                    ,skin: 'msg'
                    ,time: 2 
                });
                return false;
            }

            if(!$(".next_value_"+car_field_sum).val()){
                layer.open({
                    content: '内容不可以为空'
                    ,skin: 'msg'
                    ,time: 2 
                });
                return false;
            }

        }else if(type_val == 5){
            if(!$(".next_value_"+car_field_sum).val()) {
                layer.open({
                    content: '请输入您要输入的内容'
                    ,skin: 'msg'
                    ,time: 2 
                });
                return false;
            }
        }else if(type_val == 6){
            var val = $(".next_value_"+car_field_sum).val();
            if(!val){
                layer.open({
                    content: '地址内容不可以为空'
                    ,skin: 'msg'
                    ,time: 2 
                });
                return false;
            }
        }else if(type_val == 7){

            var start = $("#address_start").val();
            var end = $("#address_end").val();
            if(!start){
                layer.open({
                    content: '地址内容不可以为空'
                    ,skin: 'msg'
                    ,time: 2 
                });
                return false;
            }

            if(!end){
                layer.open({
                    content: '地址内容不可以为空'
                    ,skin: 'msg'
                    ,time: 2 
                });
                return false;
            }

        }
        
        if(bfb>=100){
            $("#publish_demand_form").submit();
            return false;
        }

        car_field_sum++;
        if(car_field_sum > cat_field_count){
            $("#js_publish_demand_next").addClass('hidden');
            $("#js_publish_demand_submit").removeClass('hidden');
        }else{
            $(".li").removeClass('show');
            $("#show_"+car_field_sum).addClass('show');
            $("#js_publish_demand_prev").removeClass('hidden');
            if(car_field_sum == cat_field_count){
                $("#js_publish_demand_next").addClass('hidden');
                $("#js_publish_demand_submit").removeClass('hidden');
            }
        }

        bfb = 100/(cat_field_count/car_field_sum);
        $(".per").css("width",bfb+"%");

    }

    function publish_demand_prev(){
        car_field_sum--;
        if(car_field_sum <= 1){
            $("#js_publish_demand_prev").addClass('hidden');
            $(".li").removeClass('show');
            $("#show_1").addClass('show');
        }else{
            $(".li").removeClass('show');
            $("#show_"+car_field_sum).addClass('show');
        }

        $("#js_publish_demand_submit").addClass('hidden');
        $("#js_publish_demand_next").removeClass('hidden');

        bfb = 100/(cat_field_count/car_field_sum);
        $(".per").css("width",bfb+"%");
    }

    bfb = 100/(cat_field_count/car_field_sum);
    $(".per").css("width",bfb+"%");



</script>
</body>
</html>
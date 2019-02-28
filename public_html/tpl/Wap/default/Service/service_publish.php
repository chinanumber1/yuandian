<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="telephone=no" name="format-detection">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0,user-scalable=no">
    <meta name="baidu-site-verification" content="Rp99zZhcYy">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="{pigcms{$static_path}service/css/basic.css" rel="stylesheet" type="text/css">
    <script src="{pigcms{$static_path}service/js/jquery-2.1.4.js"></script>
    <script src="{pigcms{$static_path}service/js/json2.js"></script>
    <script src="{pigcms{$static_path}service/js/basic.js"></script>
    <link href="{pigcms{$static_path}service/css/quote.css" rel="stylesheet" type="text/css">
    <link href="{pigcms{$static_path}service/css/demand.css" rel="stylesheet" type="text/css">
    <style>
        .service-cate-name{
            color: #707070; font-size: 0.876801589rem; position: absolute; left: 0px;  line-height: 3.7rem; text-align: center; width: 6rem;
        }
        .service-cate-value{
            margin-top: 15px;
            width: 220px; 
        }
    </style>
<body class="android">
    <title>填写服务地点</title>
    <!-- <include file="Service:right_nav"/> -->
<div class="pagewrap" id="mainpage">
    <!-- <include file="Service:header_top"/> -->

    <div class="main full" style="margin-top: 0px; margin-bottom: 0px;">
        <form action="{pigcms{:U('Service/service_publish_data')}" id="save-location-form" method="post" onkeydown="if(event.keyCode==13){return false;}">
            <!-- 地址选择器 -->
            <div id="select_address_box">
                <div class="add-new-address-wrap">
                    <div class="show-top-bar-wrap show-top-bar-s2">
                        <div class="show-top-bar js_topfixed js_map_topfixed">
                            <div class="show-bar">
                                完善服务范围，快速准确指派生意
                            </div>
                        </div>
                    </div>
                   
                    <div class="demand-form-list" id="js_add_new_address">
                        <div class="form-list1">
                            <div class="li coordinate-ele js_coordinate_ele coordinate-radius show">
                                <label class="lab-title">您的服务地点：</label>
                                <div class="ele-wrap">
                                    <div id="color-gray">
                                        <div class="ele-wrap">
                                            <input type="text" name="sname" class="form-control js_coordinate_address coordinate_address new_address address" placeholder="请输入您的服务地址" value="{pigcms{$service_info.sname}" id="js_add_new_address_coordinate_address" autocomplete="off">
                                        </div>
                                        <input type="hidden" name="lng" value="{pigcms{$service_info.lng}">
                                        <input type="hidden" name="lat" value="{pigcms{$service_info.lat}">
                                        <input type="hidden" name="area_id" value="{pigcms{$service_info.area_id}">
                                        <input type="hidden" name="area_name" value="{pigcms{$service_info.area_name}">
                                        <input type="hidden" name="paid" value="{pigcms{$service_info.paid}">
                                    </div>
                                    <div class="clear"></div>
                                    <div class="ele-wrap service-cate-radius">
                                        <label class="radius-title">
                                            您的服务分类及服务半径：
                                        </label>

                                        <div class="service-cate-list">    
                                            <volist name="service_info.catList" id="vo">
                                                <div class="lis cate_radius" id="catList{pigcms{$vo.cid}" style="height: 80px;">
                                                    <div class="service-cate">{pigcms{$vo.cat_name}</div>
                                                    
                                                    <div class="service-cate-value">
                                                        <input class="form-control" type="text" name="cat[{pigcms{$vo.cid}][radius]" placeholder="服务半径(km),空或0为全城" value="" style="height: 30px;width: 90%;" />
                                                    </div>
                                                    <!--i class="ico ico-del2 remove_btn" onclick="delCatList({pigcms{$vo.cid})"></i-->
                                                    <input type="hidden" name="cat[{pigcms{$vo.cid}][cid]" value="{pigcms{$vo.cid}">
                                                    <input type="hidden" name="cat[{pigcms{$vo.cid}][cat_name]" value="{pigcms{$vo.cat_name}">
                                                    <input type="hidden" name="cat[{pigcms{$vo.cid}][catgory_type]" value="{pigcms{$vo.type}">
                                                </div>

                                            </volist>
                                        </div>
                                    </div>
                                    <div class="clear" style="margin-top: 65px;"></div>

                                </div>
                        </div>
                    </div>
                    <div class="pop_action pop_action_s2 js_bottomfixed">
                        <!-- <a href="javascript:history.go(-1)" class="btn btn-blue btn-s2">
                            <i class="ico ico-goback hidden"></i>
                            返回
                        </a> -->
                        <a href="javascript:;" onclick="publish_form_sub()" class="btn btn-orange btn-s2">
                            <i class="ico ico-sure"></i>
                            确定
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
<script>

    $("#color-gray").click(function(){
        location.href = "{pigcms{:U('Service/adres_map')}";
    });

    function delCatList(cid){
        $("#catList"+cid).remove();
    }

    function publish_form_sub(){
        $("#save-location-form").submit();
    }
</script>
</body>
</html>
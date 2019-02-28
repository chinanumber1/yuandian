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
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
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
<div class="pagewrap" id="mainpage">

    <div class="main full" style=" margin-bottom: 0px;">
        <form action="{pigcms{:U('Service/edit_service_publish_data')}" id="save-location-form" method="post" onkeydown="if(event.keyCode==13){return false;}">
            <!-- 地址选择器 -->
            <div id="select_address_box">
                <div class="add-new-address-wrap">
                    <div class="show-top-bar-wrap show-top-bar-s2">
                        <div class="show-top-bar js_topfixed js_map_topfixed">
                            <div class="show-bar">
                                完善服务地点和半径，为您精准匹配生意机会
                                <i class="ico ico-play2"></i>
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
                                            <input type="text" name="sname" class="form-control js_coordinate_address coordinate_address new_address address" placeholder="请输入您的服务地址" value="{pigcms{$addresInfo.sname}" id="js_add_new_address_coordinate_address" autocomplete="off">
                                        </div>
                                        <input type="hidden" name="lng" value="{pigcms{$addresInfo.lng}">
                                        <input type="hidden" name="lat" value="{pigcms{$addresInfo.lat}">
                                        <input type="hidden" name="area_id" value="{pigcms{$addresInfo.area_id}">
                                        <input type="hidden" name="area_name" value="{pigcms{$addresInfo.area_name}">
                                        <input type="hidden" name="paid" value="{pigcms{$addresInfo.paid}">
                                    </div>
                                    <div class="clear"></div>
                                    <div class="ele-wrap service-cate-radius">
                                        <label class="radius-title">
                                            您的服务分类及服务半径：
                                        </label>

                                        <div class="service-cate-list">    
                                            <volist name="catList" id="vo">
                                                <div class="lis cate_radius" id="catList{pigcms{$vo.cid}" style="height: 80px;">
                                                    <div class="service-cate">{pigcms{$vo.cat_name}</div>
                                                    <div class="service-cate-value">
                                                        <input class="form-control" type="text" name="cat[{pigcms{$vo.cid}][radius]" placeholder="服务半径(km),空或0为全城" value="{pigcms{$vo.radius}" style="height: 30px; width: 90%;" />
                                                        <i class="ico ico-del2 remove_btn" onclick="delCatList({pigcms{$vo.cid},{pigcms{$vo.paid})"></i>
                                                        <span style="color: red; ">服务半径(km),空或0为全城</span>
                                                    </div>
                                                    <input type="hidden" name="cat[{pigcms{$vo.cid}][sp_cid]" value="{pigcms{$vo.sp_cid}">
                                                    <input type="hidden" name="cat[{pigcms{$vo.cid}][cid]" value="{pigcms{$vo.cid}">
                                                    <input type="hidden" name="cat[{pigcms{$vo.cid}][cat_name]" value="{pigcms{$vo.cat_name}">
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
        location.href = "{pigcms{:U('Service/edit_adres_map',array('paid'=>$_GET['paid']))}";
    });

    function delCatList(cid,paid){
        //底部对话框
        var service_cat_del_url = "{pigcms{:U('Service/service_cat_del')}";
        layer.open({
            content: '您确定要删除这条服务？删除后将不可接收到该服务的订单。'
            ,btn: ['删除', '取消']
            ,skin: 'footer'
            ,yes: function(index){
                $.post(service_cat_del_url,{'cid':cid,'paid':paid},function(data){
                    if(data.error == 1){
                        // $("#catList"+cid).remove();
                        // layer.open({content: data.msg});
                        layer.open({
                            content: data.msg
                            ,btn: ['确定']
                            ,yes: function(index){
                                location.href = "{pigcms{:U('Service/editdesc')}";
                                layer.close(index);
                            }
                        });
                    }
                },'json')
            }
        });
        // alert(cid);
        // $("#catList"+cid).remove();
    }

    function publish_form_sub(){
        $("#save-location-form").submit();
    }
</script>
</body>
</html>
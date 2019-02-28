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
    <link href="{pigcms{$static_path}service/css/basic.css" rel="stylesheet" type="text/css"></head>
    <title>选择您的需求分类</title>
    <style type="text/css">
        .pagewrap,.side_popUp{  height: 100%; }
    </style>
<body class="android">
    
    <div class="pagewrap" id="mainpage" >
        <div class="side_popUp " id="select_pop_id" >
            <div class="side_pop">
                <span class="close"></span>
                <div class="spop_body">
                    <div class="popTitle">
                        <span class="title">您要发布下面哪种类型的需求？</span>
                        <span class="sub"></span>
                    </div>
                    <div class="spop-wrap" style="height: 750px;">
                        <div class="spop-lv1-box" style="width: 80px;">
                            <ul class="spop-lv1 spop_hot"></ul>
                            <ul class="spop-lv1 spop_normal">
                                <volist name="catList" key="k" id="vo">
                                    <li class="parent_node <if condition="($vo.cid eq '') || ($k eq 1)">cur</if> <if condition="$vo.cid eq $_GET['cid']">cur</if>" id="parent_selectCateArr_{pigcms{$vo.cid}">
                                        <a href="javascript:;" rel="{pigcms{$vo.cid}">
                                            {pigcms{$vo.cat_name}
                                            <span class="num"></span>
                                        </a>
                                    </li>
                                </volist>
                            </ul>
                        </div>

                        <div class="spop-lv2-box proxyinput_group" maxselectcount="1">
                            <volist name="catList" key="kk" id="volist">
                                <ul class="spop-lv2 childlist_node <if condition="($kk neq 1)">hidden</if>" id="childlist_selectCateArr_{pigcms{$volist.cid}" curmaxselect="undefined" style="width: 247px;">
                                    <volist name="volist['catList']" id="vvv">
                                        <li>
                                            <a href="{pigcms{:U('Service/publish_detail',array('cid'=>$vvv['cid'],'type'=>$vvv['type']))}">
                                                <label for="child_value_new_city_arr_{pigcms{$vvv.cid}">
                                                    <span class="checkbox-hidden"></span>
                                                    {pigcms{$vvv.cat_name}
                                                </label>
                                            </a>
                                        </li>
                                    </volist>
                                </ul>
                            </volist>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{pigcms{$static_path}/js/jquery-1.7.2.js"></script>
<script type="text/javascript">
    $(".pagewrap").height($(window).height());
    $(".spop-wrap").height($(window).height()-$(".popTitle").height()-2);
    
    $(".spop_normal li.parent_node").click(function(){
        var index = $(this).index();
        $(this).addClass("cur").siblings().removeClass("cur");
        $(".spop-lv2-box .spop-lv2").eq(index).removeClass("hidden").siblings(".spop-lv2").addClass("hidden");
    });

    var cid = "{pigcms{$_GET['cid']}";
    var off=$('.spop-lv1.spop_normal').position().top;
    $(function(){
        if(cid){
            $(".parent_node").removeClass("cur");
            $("#parent_selectCateArr_"+cid).addClass("cur");
            var top=$('.spop-lv1.spop_normal .cur').offset().top;
            $('.cur').parents('.spop-lv1-box').animate({scrollTop:top-off},500);
            $('#childlist_selectCateArr_'+cid).removeClass('hidden').siblings('.spop-lv2').addClass('hidden');
        }
    });


    $('.spop-lv1.spop_normal li a').click(function(){
        var offset=$(this).position().top;
        $(this).parents('.spop-lv1-box').animate({scrollTop:offset-off},500);
    });
    
</script>
</html>
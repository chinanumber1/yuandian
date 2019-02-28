<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>同城活动栏目首页-{pigcms{$config.site_name}</title>
    <meta name="keywords" content="同城活动栏目关键词,关键词,关键词,关键词,关键词,关键词,关键词,关键词">
    <meta name="description" content="同城活动栏目介绍">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/member-mb.css">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
    <script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
    <script src="{pigcms{$static_path}portal/js/wap_common_2015.js"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
    <!--必须在现有的script外-->
    <style>
        .span_wid{
            display: inline-block;
            width:80%;
            overflow: hidden;
            text-overflow:ellipsis;
            white-space: nowrap;
        }
        .nav_APP ul li span{
            display: inline-block;
            width: 100%;
            overflow: hidden;
            text-overflow:ellipsis;
            white-space: nowrap;
        }
        .swiper-slide {
            float: left;
        }
        .icon-list.num10 .icon {
            float: left;
            width: 20%;
            text-align: center;
        }
        .icon-list .icon > a {
            padding-top: 12px;
            display: block;
        }
        .icon-list.num10 .icon-circle {
            width: 36px;
            height: 36px;
        }

        .icon-list .icon-circle {
            display: block;
            margin: auto;
            text-align: center;
            color: white;
            margin-bottom: 3px;
        }
        .icon-list .icon-circle img {
            width: 100%;
            height: 100%;
        }
        .icon-list.num10 .icon-desc {
            font-size: 12px;
        }

        .icon-list .icon-desc {
            text-align: center;
            color: #999;
        }
        .swiper-container,.swiper-wrapper,.swiper-slide{
            width: 100%;
            height: 100%;
        }
        .swiper-container {
            margin: 0 auto;
            position: relative;
            overflow: hidden;
            -webkit-backface-visibility: hidden;
            -moz-backface-visibility: hidden;
            -ms-backface-visibility: hidden;
            -o-backface-visibility: hidden;
            backface-visibility: hidden;
            z-index: 1;
        }
        .swiper-slide{
            float: left;
        }
        .swiper-pagination {
            position: absolute;
            z-index: 20;
            left: 0px;
            width: 100%;
            text-align: center;
            bottom:4px;
        }
        .swiper-pagination-switch {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 8px;
            background: black;
            margin-right:5px;
            opacity: 0.14;
            cursor: pointer;
        }
        .swiper-active-switch {
            background: #06c1ae;
            opacity: 1;
        }

    </style>
</head>
<body class="<if condition="$like_app_browser">like_app</if>">
    <div id="pageMain">

    <div class="header">
        <a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
        <a href="{pigcms{:U('Wap/My/index')}" class="my <if condition="$user_session['uid']">ico_ok</if>" id="login_ico">我的</a>
        <div class="type" id="nav_ico">导航</div>
        <span id="ipageTitle" style="">同城活动</span>
        <include file="Portal:top_nav"/>
    </div>
        
        <div class="o_main">
            <if condition="!$slider_list">
            <div class="nav_APP showNavApp clearfix">
                <ul>
                    <volist name="activityCatgoryList" id="cvo">
                        <li>
                            <a href="{pigcms{:U('Portal/activity',array('cid'=>$cvo['cid']))}"><span>{pigcms{$cvo.cat_name} </span> <s class="s" style="background-image:url({pigcms{$config.site_url}{pigcms{$cvo.img});"></s>
                            </a>
                        </li>
                    </volist>
                </ul>
            </div>
            <else/>
            <div class="showNavApp">
                <div class="swiper-container swiper-container2" style="height: 170px; ;">
                    <div class="swiper-wrapper" >
                        <volist name="slider_list" id="vo" key="k">
                            <if condition="$k%11 == 0 || $k == 1">
                                <if condition="$k != 1 ">
                                    </ul>
                                </div>
                                </if>
                                <div class="swiper-slide " >
                                    <ul class="icon-list num10">    
                                        <li class="icon">
                                            <a href="{pigcms{$vo.url}">
                                                <span class="icon-circle">
                                                    <img src="{pigcms{$config.site_url}/upload/slider/{pigcms{$vo.pic}">
                                                </span>
                                                <span class="icon-desc">{pigcms{$vo.name}</span>
                                            </a>
                                        </li>
                            <else/>
                                <li class="icon">
                                    <a href="{pigcms{$vo.url}">
                                        <span class="icon-circle">
                                            <img src="{pigcms{$config.site_url}/upload/slider/{pigcms{$vo.pic}">
                                        </span>
                                        <span class="icon-desc">{pigcms{$vo.name}</span>
                                    </a>
                                </li>
                            </if>

                            <if condition="$k%11 != 0 && !$slider_list[$k]">
                                </ul>
                                </div>
                            </if>
                        </volist>
                    </div>
                    <div class="swiper-pagination swiper-pagination2" style="bottom:-4px"></div>
                </div>
            </div>
            </if>
            <div class="filter2" id="filter2">
                <ul class="tab clearfix">
                    <li class="item">
                        <a href="javascript:void(0);">
                            <span id="cat_name_html">全部分类</span> <em></em>
                        </a>
                    </li>
                </ul>
                <div class="inner" style="display:none;">
                    <ul>
                        <li <if condition="$_GET['cid'] eq ''">class="current"</if>>
                            <a href="{pigcms{:U('Portal/activity')}">全部活动</a>
                        </li>
                        <volist name="activityCatgoryList" id="vo">
                        <li <if condition="$_GET['cid'] eq $vo['cid']">class="current"</if>>
                                <a href="{pigcms{:U('Portal/activity',array('cid'=>$vo['cid']))}">{pigcms{$vo.cat_name}</a>
                            </li>
                        </volist>
                    </ul>
                </div>
    
                <div class="inner_parent" id="parent_container" style="display:none;">
                    <div class="innercontent"></div>
                </div>
                <div class="inner_child" id="inner_container" style="display:none;">
                    <div class="innercontent"></div>
                </div>
            </div>

            <script>
                var aaaa = $(".current a").html();
                $("#cat_name_html").html(aaaa);
            </script>
            
            <div class="fullbg" id="fullbg" style="display:none;"> <i class="pull2"></i>
            </div>
            <!--列表-->
            <div class="pic_3_list">
                <ul>
                    <if condition="is_array($activityList)">
                        <volist name="activityList" id="vo">
                            <li>
                                <a href="{pigcms{:U('Portal/activity_detail',array('a_id'=>$vo['a_id']))}">
                                    <div class="pic"> 
                                        <sup class="bm{pigcms{$vo.state}"></sup>
                                        <img style="height: 122px;" src="{pigcms{$config.site_url}/upload/portal/{pigcms{$vo.pic}" alt="">
                                    </div>
                                    <h3>
                                        <span class="span_wid">{pigcms{$vo.title}</span>
                                        <span class="bao">{pigcms{$vo.already_sign_up}人已报</span>
                                    </h3>
                                    <div class="clearfix">
                                        <p class="time">{pigcms{$vo.time}</p>
                                        <p class="address">{pigcms{$vo.place}</p>
                                    </div>
                                </a>
                            </li>
                        </volist>
                    <else/>
                        <li style="text-align: center; color: red;">暂无数据</li>
                    </if>
                    
                </ul>
                <div class="pageNav">
                    {pigcms{$pagebar}
                </div>
            </div>
        </div>
        <div class="windowIframe" id="windowIframe" data-loaded="0">
            <div class="header">
                <a href="javascript:;" class="back close">返回</a>
                <span id="windowIframeTitle"></span>
            </div>
            <div class="body" id="windowIframeBody"></div>
        </div>
        <div id="l-map" style="display:none;"></div>
        <script src="{pigcms{$static_path}portal/js/wap_common.js"></script>

        <script type="text/javascript" src="{pigcms{$static_path}portal/js/iscroll.js"></script>
        <script>
            if($('.swiper-container2').size() > 0){
                var mySwiper2 = $('.swiper-container2').swiper({
                    pagination:'.swiper-pagination2',
                    loop:true,
                    grabCursor: true,
                    paginationClickable: true,
                    simulateTouch:false
                });
            }
            (function($){
              window['myScroll_parent'] = null;
              window['myScroll_inner'] = null;
              showFilter({ibox:'filter2',content1:'parent_container',content2:'inner_container',fullbg:'fullbg'});
            })(jQuery);
        </script>
</div>
</body>
</html>
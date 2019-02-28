<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>便民黄页栏目首页-{pigcms{$config.site_name}</title>
    <meta name="keywords" content="便民黄页栏目关键词,关键词,关键词,关键词,关键词,关键词,关键词,关键词">
    <meta name="description" content="便民黄页栏目介绍">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/bm-mb.css">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
    <script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
    <script src="{pigcms{$static_path}portal/js/wap_common_2015.js"></script>
    <!--必须在现有的script外-->
    <style>
        .nav_APP ul li {
            display: inline-block;
            width: 33%;
            text-align: center;
            margin: 6px 0;
        }
    </style>
</head>
<body class="<if condition="$like_app_browser">like_app</if>">
    <div id="pageMain">
        <div class="header">
            <a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
            <a href="{pigcms{:U('Wap/My/index')}" class="my <if condition="$user_session['uid']">ico_ok</if>" id="login_ico">我的</a>
            <div class="type" id="nav_ico">导航</div>
            <span id="ipageTitle" style="">黄页</span>
            <include file="Portal:top_nav"/>
        </div>

        <div class="nav_bm_bottom">
            <ul>
                <li>
                    <a href="{pigcms{:U('Wap/Portal/index')}">
                        <span class="home"></span>
                        首页
                    </a>
                </li>
                <li>
                    <a href="{pigcms{:U('Wap/My/index')}">
                        <span class="mine"></span>
                        我的
                    </a>
                </li>
            </ul>
        </div>

        <div class="wrapper o_main mar_b_50">
            <div class="bm_banner">
                <img src="{pigcms{$static_path}portal/images/bianminBanner.jpg" alt="">
                <div class="job_search">
                    <div class="sbox clearfix">
                        <form method="get" action="{pigcms{:U('yellow_list')}">
                            <input type="hidden" name="c" value="Portal">
                            <input type="hidden" name="a" value="yellow_list">
                            <input type="text" name="keyword" id="keyword" placeholder="请输入关键字" value="">
                            <button type="submit">搜索</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="viewport">
                <div  class="nav_APP showNavApp">
                    <div id="scroller" class="clearfix" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
                        <div class="slide" >
                            <ul class="clearfix">
                                <volist name="all_category_list" id="vo">
                                    <li>
                                        <a href="{pigcms{:U('Portal/yellow_list',array('pid'=>$vo['cat_id']))}">
                                        {pigcms{$vo.cat_name}
                                            <s class="s" style="background-color:#fff; background-image:url({pigcms{$vo.cat_pic});"></s>
                                        </a>
                                    </li>
                                </volist>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="new_active n_mb" style="margin-top:10px;" id="tab_01">
                <div class="select_01 tab-hd">
                    <ul>
                        <li class="item">
                            <a href="javascript:void(0);">最新加入</a>
                        </li>
                        <li class="item">
                            <a href="javascript:void(0);">热门排行</a>
                        </li>
                    </ul>
                </div>
                <div class="info_list tab-cont">
                    <ul>
                        <volist name="yellowAddList" id="vo">
                            <li>
                                <a href="{pigcms{:U('Portal/yellow_detail',array('id'=>$vo['id']))}">
                                    <div class="pic">
                                        <img src="{pigcms{$vo.logo}"></div>
                                    <div class="con">
                                        <h3>{pigcms{$vo.title}</h3>
                                        <p>{pigcms{$vo.address}</p>
                                    </div>
                                </a>
                            </li>
                        </volist>
                    </ul>
                </div>
                <div class="info_list tab-cont">
                    <ul>
                        <volist name="yellowPvList" id="vo">
                            <li>
                                <a href="{pigcms{:U('Portal/yellow_detail',array('id'=>$vo['id']))}">
                                    <div class="pic">
                                        <img src="{pigcms{$vo.logo}"></div>
                                    <div class="con">
                                        <h3>{pigcms{$vo.title}</h3>
                                        <p>{pigcms{$vo.address}</p>
                                    </div>
                                </a>
                            </li>
                        </volist>
                    </ul>
                </div>
            </div>
            <div class="more_list">
                <a href="{pigcms{:U('Portal/yellow_list')}">查看更多</a>
            </div>

        </div>

    </div>


    <script>
        (function($){
            IDC2.tabADS($('#tab_01'));
        })(jQuery);
    </script>
</body>
</html>
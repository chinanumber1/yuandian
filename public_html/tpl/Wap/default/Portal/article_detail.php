<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>{pigcms{$article.title}-{pigcms{$config.site_name}</title> 
    <meta name="keywords" content="{pigcms{$article.title}">
    <meta name="description" content="{pigcms{$article.title},文章资讯栏目首页-本地资讯">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/news2016-mb.css">
    <link href="{pigcms{$static_path}portal/css/pageScroll.css" rel="stylesheet">
    <link href="{pigcms{$static_path}portal/css/comment-mb.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
    <link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
    <script src="{pigcms{$static_path}portal/js/wap_common_2015.js"></script>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
    <!--必须在现有的script外-->
    <script src="{pigcms{$static_path}portal/js/share.js"></script>
    <link rel="stylesheet" href="{pigcms{$static_path}portal/css/share_style0_32.css"/>
    <style>
        .article_content img {
            max-width: 100%;
        }

        .line_tip {
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        .title_info {
            text-align: center;
            font-weight: bolder;
            font-size: 18px;
            padding: 20px 0 0;
        }

        .title_msg {
            text-align: center;
            font-size: 18px;
            padding: 20px 0;
        }

        .layermcont {
            padding: 0;
        }

        .layermchild {
            width: 66%;
        }

        .tip_info_list {
            border-top: 1px solid #f0f0f0;
            padding: 10px 20px;
        }

        .money_info {
            float: right;
        }
    </style>
</head>
<body class="<if condition="$like_app_browser">like_app</if>">
<div id="pageMain">
    <input type="hidden" id="article_id" value="{pigcms{$article.aid}"/>
    <div class="header">
        <a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
        <div class="search" id="search_ico" onclick="showNewPage(&#39;搜索&#39;,searchHtml,newPageSearch);"
             style="display:none;">搜索
        </div>
        <a href="{pigcms{:U('Wap/My/index')}" class="my <if condition=" $user_session['uid']">ico_ok</if>"
        id="login_ico">我的</a>
        <div class="type" id="nav_ico">导航</div>
        <span id="ipageTitle" style="">本地资讯</span>
        <include file="Portal:top_nav"/>
    </div>

    <div class="p_main" style="bottom:40px;">
        <div class="zx_detail">
            <h1>{pigcms{$article.title}</h1>
            <div class="detail_info clearfix">
                <div class="left">
                    <span class="r_time">{pigcms{$article.dateline|date="Y-m-d H:i:s",###}</span>
                </div>
                <div class="right">
                    <span class="num2">{pigcms{$article.PV}</span>
                    阅读
                    <span class="line"></span>
                    <span class="num2" id="show_total_revert2">{pigcms{$recomment_list|count}</span>
                    评论
                </div>
            </div>
            <div class="info">
                <div class="article_content" style="background-color: #fafafa;">
                    {pigcms{$article.desc}
                </div>
            </div>
            <if condition="$article['is_reward'] eq 2">
                <div class="info">
                    <div class="article_content">
                        <div class="line_tip"> ————剩余信息需要打赏后观看————</div>
                        <div class="btn-wrapper">
                            <button onclick='article_reward_pay("{pigcms{$article.aid}")' type="submit"
                                    class="btn btn-larger btn-block" style="background-color: #06c1ae;font-size: 16px;">
                                立即打赏￥{pigcms{$article.reward_money}
                            </button>
                        </div>
                    </div>
                </div>
                <else/>
                <div class="info">
                    <div class="article_content" id="resizeIMG">
                        {pigcms{$article.msg|htmlspecialchars_decode}
                        <div class="pageNav2 pageNav4"></div>
                    </div>
                </div>
            </if>
        </div>
        <!--列表-->
        <div class="zx_list mb_10">
            <div class="title">
                <span>精彩图文</span>
            </div>
            <ul>
                <volist name="hot_img_news" id="vo">
                    <li>
                        <a href="{pigcms{:U('Portal/article_detail',array('aid'=>$vo['aid']))}">
                            <img src="{pigcms{$vo.thumb}" class="pic" alt="">
                            <div class="con">
                                <h3>{pigcms{$vo.title}</h3>
                                <p class="txt clearfix">
                                    <span class="left">{pigcms{$vo.dateline|date="m-d H:i",###}</span>
                                    <span class="right">人气：{pigcms{$vo.PV}</span>
                                </p>
                            </div>
                        </a>
                    </li>
                </volist>
            </ul>
        </div>
        <div class="user_reviews">
            <div class="title">
                <span>网友评论</span>
                <div class="ComentNum" id="show_total_revert">{pigcms{$recomment_list|count}</div>
            </div>
            <div id="total_revert" data-num="2">
                <volist name="recomment_list" key="k" id="vo">
                    <div class="comment_item">
                        <div class="comment_face">
                            <if condition="$vo['avatar'] neq ''">
                                <img src="{pigcms{$vo.avatar}">
                                <else/>
                                <img src="{pigcms{$static_path}portal/images/user_small.gif"/>
                            </if>
                        </div>
                        <div class="comment_box">
                            <div class="comment_user clearfix">
                                <span class="userName">{pigcms{$vo.nickname}</span>
                                <p class="date">{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}</p>
                            </div>
                            <p class="comment_content">{pigcms{$vo.msg}</p>
                        </div>
                    </div>
                </volist>
            </div>
        </div>
    </div>
</div>
<script src="{pigcms{$static_path}portal/js/wap_common.js"></script>


<!-- 分享 -->
<include file="Portal:fenxiang"/>

<div class="reply_box page_srcoll" id="pageOther">
    <div class="inner">
        <span class="title"> <span id="replyName">发表评论</span> </span>
        <div class="return_close" id="closeReply">返回</div>
        <div class="cmt_txt2" id="cmt_txt" placeholder="想说点什么~" contenteditable="true"></div>
        <input type="submit" onclick="save_recomment()" class="rsubmit" value="发表">
    </div>
</div>
<div class="footFixed">
    <div class="reply_hd clearfix display{$isrevert}" id="reply_hd">
        <ul>
            <li>
                <span class="share" id="share2015">分享</span>
            </li>
            <li>
                <span class="num" id="show_total_revert1">{pigcms{$recomment_list|count}</span>
            </li>
            <li onclick="setarticle({pigcms{$article.aid});">
                <span class="zan" id="dingnews">{pigcms{$article.zan}</span>
            </li>
            <li>
                <a href="#" id="openReply" class="btn" style="height: 40px; line-height: 40px; width: 104px;">写评论</a>
            </li>
        </ul>
    </div>
</div>
<script src="{pigcms{$static_path}portal/js/jquery.form.js"></script>
<script src="{pigcms{$static_path}portal/js/jquery.scrollTo.js"></script>
<script src="{pigcms{$static_path}portal/js/scrollHe.js"></script>
<script src="{pigcms{$static_path}portal/js/bootstrap.min.js"></script>
<script src="{pigcms{$static_path}portal/js/cropper.min.js"></script>
<script src="{pigcms{$static_path}portal/js/wap_upimgOne.js"></script>
<script src="{pigcms{$static_path}portal/js/emotData.js"></script>
<script src="{pigcms{$static_path}portal/js/wap_comments_2017.js"></script>

<script>
    (function ($) {
        $('#share2015').share2015();
    })(jQuery);
    var click = false;

    function save_recomment() {
        var aid = $('#article_id').val();
        var msg = $.trim($('#cmt_txt').text());
        var uid = "{pigcms{$user_session['uid']}";


        if (!uid) {
            layer.open({
                content: '<div  class="title_msg">请先登录</div>'
                , btn: ['去登录']
                , yes: function (index) {
                    location.href = "{pigcms{:U('Login/index')}";
                }
            });
            return false;
        }


        if (!msg) {
            layer.open({
                content: '<div  class="title_msg">请输入评论内容！</div>'
                , skin: 'msg'
                , time: 2
            });
            return false;
        }
        if (!aid) {
            layer.open({
                content: '<div  class="title_msg">数据异常！</div>'
                , skin: 'msg'
                , time: 2
            });
            return false;
        }

        $.post("{pigcms{:U('Portal/ajax_save_recomment')}", {'msg': msg, 'aid': aid}, function (response) {
            if (response.code == 1) {
                layer.open({
                    content: '<div  class="title_msg">' + response.msg + '</div>'
                    , skin: 'msg'
                    , time: 2 //2秒后自动关闭
                });
            } else {
                layer.open({
                    content: '<div  class="title_msg">' + response.msg + '</div>'
                    , btn: ['确定']
                    , yes: function (index) {
                        location.reload();
                    }
                });
            }

        }, 'json');
    }

    function setarticle(aid) {
        $.post("{pigcms{:U('Portal/article_setarticle')}", {'aid': aid}, function (response) {

            if (response.code == 0) {
                layer.open({
                    content: '<div  class="title_msg">' + response.msg + '</div>'
                    , btn: ['确定']
                    , yes: function (index) {
                        location.reload();
                    }
                });
            } else if (response.code == 2) {
                layer.open({
                    content: '<div  class="title_msg">' + response.msg + '</div>'
                    , btn: ['去登录']
                    , yes: function (index) {
                        location.href = "{pigcms{:U('Login/index')}";
                    }
                });
            } else {
                layer.open({
                    content: '<div  class="title_msg">' + response.msg + '</div>'
                    , skin: 'msg'
                    , time: 2 //2秒后自动关闭
                });
            }

        }, 'json');
    }

    function article_reward_pay(aid) {
        if (click) return false;
        click = true;
        setTimeout(function () {
            if (click) {
                console.log('change')
                click = false;
            }
        }, 2000);
        var article_reward_pay_order = "{pigcms{:U('Portal/article_reward_pay_order')}";
        var article_reward_pay = "{pigcms{:U('Portal/article_reward_pay')}";

        $.post(article_reward_pay_order, {'aid': aid}, function (data) {
            console.log('支付信息-》  ', data)
            click = false;
            if (data.error == 3) {
                layer.open({
                    content: '<div  class="title_info">' + data.msg + '</div><br>' +
                    '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                    '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>' +
                    '<div class="tip_info_list" style="color: #FF658E;">还需充值（元）：<div class="money_info">￥' + data.info.difference + '</div></div>'
                    , btn: ['确定', '取消']
                    , yes: function (index) {
                        location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_portal_article_'))}{pigcms{$article['aid']}";
                    }
                });
            } else if (data.error == 1 || data.error == 2) {
                layer.open({
                    content: '<div  class="title_msg">' + data.msg + '</div>'
                    , btn: ['确定']
                    , yes: function (index) {
                        window.location.href = window.location.href;
                    }
                });
            } else if (data.error == 5) {
                layer.open({
                    content: '<div  class="title_info">打赏</div><br>' +
                    '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                    '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>'
                    , btn: ['立即支付', '取消']
                    , yes: function (index) {
                        $.post(article_reward_pay, {'aid': aid}, function (data) {
                            if (data.error == 3) {
                                layer.open({
                                    content: '<div  class="title_info">' + data.msg + '</div><br>' +
                                    '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                                    '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>' +
                                    '<div class="tip_info_list" style="color: #FF658E;">还需充值（元）：<div class="money_info">￥' + data.info.difference + '</div></div>'
                                    , btn: ['确定', '取消']
                                    , yes: function (index) {
                                        location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_portal_article_'))}{pigcms{$article['aid']}";
                                    }
                                });
                            } else if (data.error == 1 || data.error == 2) {
                                layer.open({
                                    content: '<div  class="title_msg">' + data.msg + '</div>'
                                    , btn: ['确定']
                                    , yes: function (index) {
                                        window.location.href = window.location.href;
                                    }
                                });
                            } else {
                                layer.open({
                                    content: '<div  class="title_msg">' + data.msg + '</div>'
                                    , btn: ['确定']
                                });
                            }
                        }, 'json');
                    }
                });
            } else {
                if (data.code == 2) {
                    layer.open({
                        content: '<div  class="title_msg">请先登录</div>'
                        , btn: ['去登录']
                        , yes: function (index) {
                            location.href = "{pigcms{:U('Login/index')}";
                        }
                    });
                } else {
                    layer.open({
                        content: '<div  class="title_msg">' + data.msg + '</div>'
                        , btn: ['确定']
                    });
                }
            }
        }, 'json');
    }

</script>
{pigcms{$shareScript}
<script type="text/javascript">
    window.shareData = {
        "moduleName": "Home",
        "moduleID": "0",
        "imgUrl": "<if condition="
        $config['wechat_share_img']
        ">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
        "sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Portal/article_detail',array('aid'=>$_GET['aid']))}",
        "tTitle": "本地资讯 - {pigcms{$article.title}",
        "tContent": "{pigcms{$article.title}"
    };
</script>
</body>
</html>
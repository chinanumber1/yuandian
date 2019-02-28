<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>约会去哪儿</title>
    <meta http-equiv="Content-Type">
    <meta content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">
    <link rel="stylesheet" href="{pigcms{$static_path}css/invitation.css" type="text/css">
    <link href="{pigcms{$static_path}tpl/1116/css/cate.css" rel="stylesheet" type="text/css" />
</head>
<style>
    .searchHeader {
        border-bottom: 1px solid #eee;
    }
    .searchHeader {
        top: 0;
        height: 50px;
        background: white;
        position: fixed;
        width: 100%;
        z-index: 21;
    }
    .searhBackBtn {
        position: absolute;
        width: 50px;
        height: 100%;
        top: 0;
        left: 0;
    }
    .searhBackBtn:after {
        display: block;
        content: "";
        border-top: 2px solid #666;
        border-left: 2px solid #666;
        width: 8px;
        height: 8px;
        -webkit-transform: rotate(315deg);
        background-color: transparent;
        position: absolute;
        top: 19px;
        left: 19px;
    }
    .searchBox {
        background-color: #f4f4f4;
        height: 38px;
        margin-left: 50px;
        margin-right: 74px;
        margin-top: 6px;
        position: relative;
    }
    .searchBox .searchIco {
        position: absolute;
        left: 10px;
        top: 12px;
    }
    .searchBox .searchIco:before {
        width: 10px;
        border: 1px #A6A6A6 solid;
        border-radius: 100%;
        -webkit-border-radius: 100%;
        -moz-border-radius: 100%;
    }
    .searchBox .searchIco:before, .searchBox .searchIco:after {
        content: '';
        height: 10px;
        display: block;
        position: absolute;
        top: 0;
        left: 0;
    }
    .searchBox .searchIco:after {
        width: 1px;
        background: #A6A6A6;
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
        top: 10px;
        left: 11px;
        height: 4px;
    }
    .searchBox .delIco {
        position: absolute;
        right: 0;
        top: 0;
        width: 38px;
        height: 38px;
        display: none;
    }
    .searchBox .delIco div:before, .searchBox .delIco div:after {
        content: '';
        height: 2px;
        width: 14px;
        display: block;
        background: white;
        border-radius: 10px;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        position: absolute;
        top: 18px;
        left: 12px;
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
    }
    .searchBox .delIco div:after {
        transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -o-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
    }
    .searchBox .delIco div {
        background-color: #CDCDCD;
        border-radius: 100%;
        width: 20px;
        height: 20px;
        margin-left: 9px;
        margin-top: 9px;
    }
    .searchBtn.so {
        background-color: #06c1ae;
    }
    .searchBtn {
        position: absolute;
        width: 50px;
        height: 38px;
        line-height: 38px;
        top: 6px;
        right: 6px;
        text-align: center;
        background-color: #B5B5B5;
        color: white;
        padding: 0 6px;
    }
    .searchTxt {
        padding: 12px 0;
        border: none;
        margin-left: 32px;
        background: transparent;
        outline: none;
        font-size: 14px;
        width: 87%;
    }
    .motify {
        display: none;
        position: fixed;
        top: 35%;
        left: 50%;
        width: 260px;
        padding: 0;
        margin: 0 0 0 -130px;
        z-index: 999;
        background: rgba(0, 0, 0, 0.8);
        color: #fff;
        font-size: 14px;
        line-height: 1.5em;
        border-radius: 6px;
        -webkit-box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
    }
    .motify .motify-inner {
        padding: 10px 10px;
        text-align: center;
        word-wrap: break-word;
    }
</style>
<body class="scene-body">
<section class="head-ad">
    <ul>
        <li>
            <a href="{pigcms{:U('Invitation/datelist', array('activity_type' => 0))}">
                <div class="image">
                    <img src="{pigcms{$static_path}images/invitation_eat.png">
                </div>
            </a>
        </li>
        <li>
            <a href="{pigcms{:U('Invitation/datelist', array('activity_type' => 1))}">
                <div class="image">
                    <img src="{pigcms{$static_path}images/invitation_ploy.png">
                </div>
            </a>
        </li>
        <li>
            <a class="invitation-link" onclick="invitationSearch()" data-url="invitationSearch">
                <div class="image">
                    <img src="{pigcms{$static_path}images/invitation_search.png">
                </div>
            </a>
        </li>
    </ul>
</section>

<div id="pageShopSearchHeader" class="searchHeader" style="display: none;">
    <div id="pageShopSearchBackBtn" class="searhBackBtn"></div>
    <div id="pageShopSearchBox" class="searchBox">
        <div class="searchIco"></div>
        <input type="text" id="pageShopSearchTxt" class="searchTxt" placeholder="请输入昵称" autocomplete="off" style="width: 256px;">
        <div class="delIco" id="pageShopSearchDel" style="display: block;"><div></div></div>
    </div>
    <div id="pageShopSearchBtn" class="searchBtn so">搜索</div>
</div>

<header id="women_info" style="display: block;">
    <h1>女神</h1>

    <div id="list_uls" class="list_uls box_swipe">
        <ul>
            <li>
                <dl class="content-slide" id="show_sex_2">
                    <volist name="women" id="w">
                        <dd>
                            <a href="<if condition="$w['uid']">{pigcms{:U('Invitation/userinfo', array('uid' => $w['uid']))}<else/> javascript:void(0)</if>">
                            <figure>
                                <div><img class="lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{$w['avatar']}" style="height:100px;"/></div>
                                <figcaption>
                                    <label style="cursor:pointer;width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{pigcms{$w['nickname']}</label>
                                </figcaption>
                            </figure>
                            </a>
                        </dd>
                    </volist>
                </dl>
            </li>
        </ul>
    </div>

</header>
<div style="text-align: right;height: 30px;margin-top: 15px;display:block;"><a href="javascript:;" style="color: #ED0B8C;"  id="sex_2" data-page="2">更多女神等你约...</a></div>

<header id="men_info" style="display: block;">
    <h1>高富帅</h1>

    <div id="list_uls" class="list_uls box_swipe">
        <ul>
            <li>
                <dl class="content-slide" id="show_sex_1">
                    <volist name="men" id="m">
                        <dd>
                            <a href="<if condition="$m['uid']">{pigcms{:U('Invitation/userinfo', array('uid' => $m['uid']))}<else/> javascript:void(0)</if>">
                            <figure>
                                <div><img  class="lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{$m['avatar']}" style="height:100px;"/></div>
                                <figcaption>
                                    <label style="cursor:pointer;width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{pigcms{$m['nickname']}</label>
                                </figcaption>
                            </figure>
                            </a>
                        </dd>
                    </volist>
                </dl>
            </li>
        </ul>
    </div>
</header>
<div style="text-align: right;height: 30px;margin-top: 15px;display:block;"><a href="javascript:;" style="color: #ED0B8C;" id="sex_1" data-page="2">查看更多高富帅...</a></div>

<div id="search_info" style="display: none;">
    <div id="search_women" style="display: none;">
        <header >
            <h1>女神</h1>
            <div id="list_uls" class="list_uls box_swipe">
                <ul>
                    <li>
                        <dl class="content-slide" id="show_sex_4">
                        </dl>
                    </li>
                </ul>
            </div>
        </header>
    </div>

    <div id="search_men" style="display: none;">
        <header style="display: block;">
            <h1>高富帅</h1>
            <div id="list_uls" class="list_uls box_swipe">
                <ul>
                    <li>
                        <dl class="content-slide" id="show_sex_3">
                        </dl>
                    </li>
                </ul>
            </div>
        </header>
    </div>
</div>

<i class="scroll-top clear"></i>
<div class="overlay" style="top: 0px; left: 0px; width: 100%; height: 100%; z-index: 200; position: fixed; display: none; background: rgba(0, 0, 0, 0.6);"></div>
<div style="display:none;">{pigcms{$config.wap_site_footer}</div>
</body>
<script src="{pigcms{$static_path}js/jquery.min1.8.js" type="text/javascript"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
<!--<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>-->
<!--<script type="text/javascript" src="{pigcms{$static_path}js/common_pure.js?t=001" charset="utf-8"></script>-->
<script src="{pigcms{$static_path}js/invitation.js?t=0007"></script>
<script type="text/javascript">


    var total1 = {pigcms{$count1};
    var total2 = {pigcms{$count2};
    var pagesize = 6;
    var pages1 = Math.ceil(total1 / pagesize);
    var pages2 = Math.ceil(total2 / pagesize);

    $(document).ready(function(){
        $("img.lazy_img").lazyload();

        if (pages1 > 1) {
            $('#sex_1').click(function(){

                var _page = $('#sex_1').attr('data-page');

                if (_page > pages1) {
                    $('#sex_1').html('没有更多了');
                    return;
                }
                $('#sex_1').attr('data-page',parseInt(_page)+1);
                $.ajax({
                    type : "GET",
                    data : {'page' : _page, 'pagesize' : pagesize, 'sex':1},
                    url :  '/wap.php?c=Invitation&a=ajaxmore',
                    dataType : "json",
                    success : function(RES) {
                        data = RES.data;
                        var _tmp_html = '';
                        $.each(data, function(x, y) {
                            _tmp_html += '<dd><a href="/wap.php?c=Invitation&a=userinfo&uid='+ y.uid +'">';
                            _tmp_html += '<figure><div><img src="'+ y.avatar +'"  style="height:100px;"/></div>';
                            _tmp_html += '<figcaption><label style="cursor:pointer;width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'+ y.nickname +'</label></figcaption>';
                            _tmp_html += '</figure>';
                            _tmp_html += '</a></dd>';
                        });
                        $('#show_sex_1').append(_tmp_html);
                    }
                });
            });
        }
        if (pages2 > 1) {
            $('#sex_2').click(function(){
                var _page = $('#sex_2').attr('data-page');
                if (_page > pages2) {
                    $('#sex_2').html('没有更多了');
                    return;
                }
                $('#sex_2').attr('data-page',parseInt(_page)+1);
                $.ajax({
                    type : "GET",
                    data : {'page' : _page, 'pagesize' : pagesize, 'sex':2},
                    url :  '/wap.php?c=Invitation&a=ajaxmore',
                    dataType : "json",
                    success : function(RES) {
                        data = RES.data;
                        var _tmp_html = '';
                        $.each(data, function(x, y) {
                            _tmp_html += '<dd><a href="/wap.php?c=Invitation&a=userinfo&uid='+ y.uid +'">';
                            _tmp_html += '<figure><div><img src="'+ y.avatar +'"  style="height:100px;"/></div>';
                            _tmp_html += '<figcaption><label style="cursor:pointer;width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'+ y.nickname +'</label></figcaption>';
                            _tmp_html += '</figure>';
                            _tmp_html += '</a></dd>';
                        });
                        $('#show_sex_2').append(_tmp_html);
                    }
                });
            });
        }

    });

    window.shareData = {
        "moduleName":"Invitation",
        "moduleID":"0",
        "imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
        "sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Invitation/index')}",
        "tTitle": "约会去哪儿",
        "tContent": "{pigcms{$config.site_name}-约会去哪儿"
    };
</script>
{pigcms{$shareScript}
</html>
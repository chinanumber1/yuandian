<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{pigcms{$info.type_name}</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport"
          content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name='apple-touch-fullscreen' content='yes'/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="address=no"/>

    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css"/>
    <script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>


    <link rel="stylesheet" href="{pigcms{$static_path}discover/css/swiper.min.css">
    <!-- <link rel="stylesheet" href="{pigcms{$static_path}discover/css/flStyle.css"> -->
    <script src="{pigcms{$static_path}discover/js/zepto.min.js"></script>
    <script src="{pigcms{$static_path}discover/js/swiper.min.js"></script>
    <link rel="stylesheet" href="{pigcms{$static_path}css/discover.css?007"/>

</head>
<style>
    #slide {
        position: relative;
        z-index: 2;
        margin: 0 auto;
        /*width: 100%;*/
        overflow: hidden;
        /*-webkit-user-select: none;*/
        /*-moz-user-select: none;*/
        /*-ms-user-select: none;*/
        /*-o-user-select: none;*/
        user-select: none;
    }

    .operation-btn {
        text-align: right;
        padding: 1rem 0 0.5rem;
        background-color: white;
    }

    .operation-btn a.reload {
        color: #06c1ae;
        border-color: #06c1ae;
    }

    .operation-btn a {
        display: inline-block;
        width: 3.95rem;
        height: 1.5rem;
        text-align: center;
        line-height: 1.5rem;
        border: 1px solid #eee;
        border-radius: 0.25rem;
        font-size: 0.65rem;
        margin-right: .75rem;
    }

    #cover {
        display: none;
        position: absolute;
        left: 0;
        top: 0;
        z-index: 18888;
        background-color: #000000;
        opacity: 0.7;
    }

    #guide {
        display: none;
        position: absolute;
        right: 18px;
        top: 5px;
        z-index: 19999;
    }
</style>

<body>

<div class="row bbs-my-top">
    <div class="col-20 bbs-my-avather"
         onclick="location.href='{pigcms{:U('look_discover_msg',array('discover_id'=>$info['discover_id']))}'">

        <if condition='$info["avatar"] neq ""'>
            <img src="{pigcms{$info['avatar']}" width="100px" height="100px"/>
            <else/>
            <img src="{pigcms{$default_avatar}" width="100px" height="100px"/>
        </if>
    </div>
    <div class="col-80 bbs-top-info">
        <p onclick="location.href='{pigcms{:U('look_discover_msg',array('discover_id'=>$info['discover_id']))}'">
            {pigcms{$info['nickname']}
            <span>{pigcms{$info['add_time']|date='Y-m-d',###}</span>
        </p>
        <!--  <p onclick="location.href='{pigcms{:U('look_discover_msg',array('discover_id'=>$dml['discover_id']))}'"><span>{pigcms{$dml['add_time']|date='Y-m-d',###}</span></p> -->
    </div>
    <div class="col-100"
         onclick="location.href='{pigcms{:U('look_discover_msg',array('discover_id'=>$info['discover_id']))}'">
        <div class="bbs-info">
            {pigcms{$info['discover_content']|htmlspecialchars_decode=ENT_QUOTES}
        </div>
    </div>

    <if condition='!empty($img_list)'>
        <div class="col-100">
            <div class="bbs-image">
                <ul>
                    <volist name="img_list" id="vo">
                        <li class="image_item"
                            style="background-image: url('{pigcms{$vo}');background-position: center center;background-size: cover;"></li>
                    </volist>
                </ul>
            </div>
        </div>
    </if>
    <if condition='!empty($info["url_info"])'>
        <div class="relate-topic" onclick="location.href='{pigcms{$info.url_info.url}'">
            <img src="{pigcms{$info['url_info']['title_pic']}">
            <p>{pigcms{$info['url_info']['title']}</p>
        </div>
    </if>
    <div class="discover-collect-box">
        <p class="user_likes discover-collect float-left-info <if condition="$is_wexin_browser || $is_app_browser"> col-width-3 </if>"
        <if condition="$info['likes']">style="color:#06c1ae"</if>
        data-likes="{pigcms{$info.likes}" data-discover_id="{pigcms{$info.discover_id}">
        <if condition="$info['likes']">
            <img class="likes_img" src="{pigcms{$static_path}discover/images/icon/likes_a.png?t=001"/>
            <else/>
            <img class="likes_img" src="{pigcms{$static_path}discover/images/icon/likes.png?t=001"/>
        </if>
        &nbsp;<span class="likes_num_val">{pigcms{$info['likes_num']}</span>
        </p>


        <p class="line float-left-info"></p>


        <p class="user_collect discover-collect <if condition="$is_wexin_browser || $is_app_browser"> float-left-info col-width-3 </if>"
        <if condition="$info['collection']">style="color:#06c1ae"</if>
        data-collection="{pigcms{$info.collection}" data-discover_id="{pigcms{$info.discover_id}">
        <if condition="$info['collection']">
            <img class="collect_img" src="{pigcms{$static_path}discover/images/icon/ft4_a.png?t=001"/>
            <else/>
            <img class="collect_img" src="{pigcms{$static_path}discover/images/icon/ft4.png?t=001"/>
        </if>
        &nbsp;<span class="collection_num_val">{pigcms{$info['collection_num']}</span>
        </p>

        <if condition="$is_wexin_browser || $is_app_browser">
            <p class="line float-left-info"></p>

            <p class="discover-collect col-width-3" style="color:#06c1ae" onclick="_system._guide(true)">
                <img class="" src="{pigcms{$static_path}discover/images/icon/share.png?t=001"/>
            </p>
        </if>

    </div>

    <div class="col-100 discover-bottom clear-both">
        <div class="bbs-foot">
            <p>来自：<span>{pigcms{$info['type_name']}</span></p>
        </div>
    </div>


    <if condition="$info.author eq 1">
        <div class="operation-btn">
            <a href="javascript:void(0)" data-discover_id="{pigcms{$info.discover_id}" class="reload">
                编辑
            </a>
            <a data-discover_id="{pigcms{$info.discover_id}" href="javascript:void(0)" class="delete">
                删除
            </a>
        </div>
    </if>
</div>

<div id="cover"></div>
<div id="guide"><img src="{pigcms{$static_path}images/guide1.png"></div>


<script type="text/javascript" src="{pigcms{$static_path}/layer/layer.m.js" charset="utf-8"></script>
<script src="{pigcms{$static_path}discover/js/md5.js"></script>
<script src="{pigcms{$static_path}discover/js/previewImage.js"></script>
<script type="text/javascript">
    var _system = {
        $: function (id) {
            return document.getElementById(id);
        },
        _client: function () {
            return {
                w: document.documentElement.scrollWidth,
                h: document.documentElement.scrollHeight,
                bw: document.documentElement.clientWidth,
                bh: document.documentElement.clientHeight
            };
        },
        _scroll: function () {
            return {
                x: document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft,
                y: document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop
            };
        },
        _cover: function (show) {
            if (show) {
                this.$("cover").style.display = "block";
                this.$("cover").style.width = (this._client().bw > this._client().w ? this._client().bw : this._client().w) + "px";
                this.$("cover").style.height = (this._client().bh > this._client().h ? this._client().bh : this._client().h) + "px";
            } else {
                this.$("cover").style.display = "none";
            }
        },
        _guide: function (click) {
            this._cover(true);
            this.$("guide").style.display = "block";
            this.$("guide").style.top = (_system._scroll().y + 5) + "px";
            window.onresize = function () {
                _system._cover(true);
                _system.$("guide").style.top = (_system._scroll().y + 5) + "px";
            };
            if (click) {
                _system.$("cover").onclick = function () {
                    _system._cover();
                    _system.$("guide").style.display = "none";
                    _system.$("cover").onclick = null;
                    window.onresize = null;
                };
            }
            //is_share_group();
        },
        _zero: function (n) {
            return n < 0 ? 0 : n;
        }
    }


    $('#scroller').height($(window).height() - 60);

    $('.reload').click(function (e) {
        var discover_id = $(this).data('discover_id');
        var site_url = "{pigcms{$config.site_url}";
        window.location.href = site_url + '/wap.php?g=Wap&c=Discover&a=discover_msg_edit&discover_id=' + discover_id;
    });

    // 删除
    $('.delete').click(function (e) {
        if (aa == 1) {
            aa = 2;
            var discover_id = $(this).data('discover_id');
            console.log('删除的信息id', discover_id);
            layer.open({
                content: '确认删除？',
                btn: ['确定', '取消'],
                yes: function () {
                    var del_discover_msg = "{pigcms{:U('del_discover_msg')}";
                    $.post(del_discover_msg, {'discover_id': discover_id}, function (data) {
                        console.log('删除返回信息：', data);
                        aa = 1;
                        if (data.status == 0) {
                            layer.open({
                                content: data.msg,
                                btn: ['确定'],
                                shadeClose: false
                            });
                            var static_path = "{pigcms{$static_path}";
                            window.location.href = static_path + '/wap.php?g=Wap&c=Discover&a=my_discover_list';
                        } else {
                            layer.open({
                                content: '删除成功！',
                                btn: ['确定'],
                                shadeClose: false
                            });
                            var site_url = "{pigcms{$config.site_url}";
                            window.location.href = site_url + '/wap.php?g=Wap&c=Discover&a=my_discover_list';
                        }
                    }, 'json')
                },
                no: function () {
                    aa = 1;
                }
            });
        }
    });


    var swiper = new Swiper('.swiper-container-banner', {
        loop: true,
        autoplay: false,//可选选项，自动滑动
        // 如果需要分页器
        pagination: '.swiper-pagination-banner',
        // onInit: function(swiper){
        //     console.log('信息1', $(".swiper-slide").eq(swiper.activeIndex).children(0).height())
        //     var H = $(".swiper-slide").eq(swiper.activeIndex).children(0).height();
        //     console.log('信息2', H)
        //     $(".swiper-slide").css('height', H + 'px');
        //     $(".swiper-wrapper").css('height', H + 'px');
        // },
        // onSlideChangeStart : function(swiper) {
        //     console.log('信息3', $(".swiper-slide"))
        //     var H = $(".swiper-slide").eq(swiper.activeIndex).children(0).height();
        //     console.log('信息4', H)
        //     $(".swiper-slide").css('height', H + 'px');
        //     $(".swiper-wrapper").css('height', H + 'px');
        //     $(".tabs .active").removeClass('active');
        //     $(".tabs span").eq(swiper.activeIndex).addClass('active');
        // }
    });

    var swiper = new Swiper('.swiper-container2', {
        loop: true,
        grabCursor: true,
        paginationClickable: true,
        simulateTouch: false,
        pagination: '.swiper-pagination2'
    });


    var aa = 1;
    $('.user_collect').click(function (e) {
        var discover_id = $(this).data('discover_id');
        var collection = $(this).data('collection');
        console.log('collection--------', collection);
        if (aa == 1) {
            if (!collection) {
                collection_msg(discover_id, this);
            } else {
                cancel_collection_msg(discover_id, this);
            }
        }
    });

    // 收藏
    function collection_msg(discover_id, this_node) {
        aa = 2;
        var collection_msg_url = "{pigcms{:U('collection_msg')}";
        $.post(collection_msg_url, {'discover_id': discover_id}, function (data) {
            console.log('收藏返回信息：', data);
            aa = 1;
            if (data.status == 0) {
                alert(data.msg)
            } else {
                alert('收藏成功！');
                $(this_node).data('collection', true);

                var collection_num_val = parseInt($(this_node).find('.collection_num_val')[0].innerText) + 1;
                $(this_node).find('.collection_num_val')[0].innerText = collection_num_val;
                $(this_node).find('.collect_img')[0].src = "{pigcms{$static_path}" + 'discover/images/icon/ft4_a.png?t=001';
                $(this_node).css('color', '#06c1ae')
                console.log('收藏返回信息1：', $(this_node).find(".collect_img")[0]);
            }
        }, 'json')
    }

    // 取消收藏
    function cancel_collection_msg(discover_id, this_node) {
        aa = 2;
        var cancel_collection_msg_url = "{pigcms{:U('cancel_collection_msg')}";
        $.post(cancel_collection_msg_url, {'discover_id': discover_id}, function (data) {
            console.log('取消收藏返回信息：', data);
            aa = 1;
            if (data.status == 0) {
                alert(data.msg)
            } else {
                alert('取消收藏成功！');
                $(this_node).data('collection', false);

                var collection_num_val = parseInt($(this_node).find('.collection_num_val')[0].innerText) - 1;
                if (collection_num_val < 0) collection_num_val = 0;
                $(this_node).find('.collection_num_val')[0].innerText = collection_num_val;
                $(this_node).find('.collect_img')[0].src = "{pigcms{$static_path}" + 'discover/images/icon/ft4.png?t=001';
                $(this_node).css('color', '#333')
            }
        }, 'json')
    }

    var bb = 1;
    $('.user_likes').click(function (e) {
        var discover_id = $(this).data('discover_id');
        var likes = $(this).data('likes');
        console.log('likes--------', likes)
        if (bb == 1) {
            if (!likes) {
                likes_msg(discover_id, this);
            } else {
                cancel_likes_msg(discover_id, this);
            }
        }
    });

    // 点赞
    function likes_msg(discover_id, this_node) {
        bb = 2;
        var likes_msg_url = "{pigcms{:U('likes_msg')}";
        $.post(likes_msg_url, {'discover_id': discover_id}, function (data) {
            console.log('点赞返回信息：', data);
            bb = 1;
            if (data.status == 0) {
                alert(data.msg)
            } else {
                alert('点赞成功！');
                $(this_node).data('likes', true)
                var likes_num_val = parseInt($(this_node).find('.likes_num_val')[0].innerText) + 1;
                $(this_node).find('.likes_num_val')[0].innerText = likes_num_val;
                $(this_node).find('.likes_img')[0].src = "{pigcms{$static_path}" + 'discover/images/icon/likes_a.png?t=001';
                $(this_node).css('color', '#06c1ae');
                $(this_node).data('likes', true)
            }
        }, 'json')
    }

    // 取消点赞
    function cancel_likes_msg(discover_id, this_node) {
        aa = 2;
        var cancel_likes_msg_url = "{pigcms{:U('cancel_likes_msg')}";
        $.post(cancel_likes_msg_url, {'discover_id': discover_id}, function (data) {
            console.log('取消点赞返回信息：', data);
            aa = 1;
            if (data.status == 0) {
                alert(data.msg)
            } else {
                alert('取消点赞成功！');
                $(this_node).data('likes', false)
                var likes_num_val = parseInt($(this_node).find('.likes_num_val')[0].innerText) - 1;
                if (likes_num_val < 0) likes_num_val = 0;
                $(this_node).find('.likes_num_val')[0].innerText = likes_num_val;
                $(this_node).find('.likes_img')[0].src = "{pigcms{$static_path}" + 'discover/images/icon/likes.png?t=001';
                $(this_node).css('color', '#333');
            }
        }, 'json')
    }


    // 点击查看大图
    $('.image_item').click(function () {
        var str = $(this).css('background-image')
        var current = str.substring(5, str.length - 2)
        var urls = []
        var img_url = "{pigcms{:U('discover_detail')}";
        var discover_id = "{pigcms{$_GET['discover_id']}"
        $.post(img_url, {'discover_id': discover_id}, function (data) {
            console.log('收藏返回信息：', data);
            var obj = {
                urls: data.img_list,
                current: current
            };
            previewImage.start(obj);
        }, 'json')
    })


</script>

<script type="text/javascript">
    window.shareData = {
        "moduleName":"Discover",
        "moduleID":"0",
        "imgUrl": "{pigcms{$img_list.0}",
        "sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Discover/look_discover_msg')}&discover_id={pigcms{$info.discover_id}",
        "tTitle": "【{pigcms{$info['type_name']}】",
        "tContent": "{pigcms{:msubstr($info['discover_content'],0,50)}"
    };
</script>
<if condition="$is_app_browser">
    <script type="text/javascript">
        window.lifepasslogin.shareLifePass("【{pigcms{$info['type_name']}】","{pigcms{:msubstr($info['discover_content'],0,50)}","{pigcms{$img_list.0}","{pigcms{$config.site_url}{pigcms{:U('Discover/look_discover_msg')}&discover_id={pigcms{$info.discover_id}");
    </script>
</if>
</body>

</html>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>发现</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name='apple-touch-fullscreen' content='yes' />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />

    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css" />
    <script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
    <link rel="stylesheet" href="{pigcms{$static_path}css/discover.css?004" />
    <style>
        .plus {
            position: absolute;
            top: 50%;
            width: 50px;
            height: 50px;
            background-color: #333;
            right: 5%;
            z-index: 9999;
            border-radius: 50%;
        }
        .plus img {
            width: 100%;
        }

        #div1{
            height: 100%;
            width: 50px;
        }
        .operation-btn {
            text-align: right;
            border-top: 1px solid #eee;
            padding: 0.5rem 0 0;
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
    </style>
</head>

<body onload="loaded()">
    <div id="div1">
        <div id="plus" class="plus"><img src="{pigcms{$static_path}images/new_my/recharge.png"></div>
    </div>
    <div id="container">
        <div id="wrapper">
            <div id="scroller" class="village_my">
                <div id="scroller-pullDown">
                    <span id="down-icon" class="icon-double-angle-down pull-down-icon"></span>
                    <span id="pullDown-msg" class="pull-down-msg">下拉刷新</span>
                </div>

                <ul class="bbs-list">
                    <if condition='$discover_msg_list["list"]'>
                        <volist name='discover_msg_list["list"]' id='dml'>
                            <li >
                                <div class="row bbs-my-top">
                                    <div class="col-20 bbs-my-avather" onclick="location.href='{pigcms{:U('look_discover_msg',array('discover_id'=>$dml['discover_id']))}'">

                                        <if condition='$dml["avatar"] neq ""'>
                                            <img src="{pigcms{$dml['avatar']}" width="100px" height="100px" />
                                            <else/>
                                            <img src="{pigcms{$default_avatar}" width="100px" height="100px" />
                                        </if>
                                    </div>
                                    <div class="col-80 bbs-top-info" >
                                        <p onclick="location.href='{pigcms{:U('look_discover_msg',array('discover_id'=>$dml['discover_id']))}'">
                                            {pigcms{$dml['nickname']}
                                            <span>{pigcms{$dml['add_time']|date='Y-m-d',###}</span>
                                        </p>
                                        <!--  <p onclick="location.href='{pigcms{:U('look_discover_msg',array('discover_id'=>$dml['discover_id']))}'"><span>{pigcms{$dml['add_time']|date='Y-m-d',###}</span></p> -->

                                    </div>
                                    <div class="col-100" onclick="location.href='{pigcms{:U('look_discover_msg',array('discover_id'=>$dml['discover_id']))}'">
                                        <div class="bbs-desc">
                                            {pigcms{:msubstr($dml['discover_content'],0,50)}
                                        </div>
                                    </div>

                                    <if condition='$dml["discover_img"]'>
                                        <div class="col-100 " onclick="location.href='{pigcms{:U('look_discover_msg',array('discover_id'=>$dml['discover_id']))}'">
                                            <div class="bbs-image">
                                                <ul>
                                                    <volist name='dml["discover_img"]' id='img'>
                                                        <li><img src="{pigcms{$img}" /></li>
                                                    </volist>
                                                </ul>
                                            </div>
                                        </div>
                                    </if>
<!--                                    <div class="relate-topic">-->
<!--                                        <img src="111.png">-->
<!--                                        <p>中国甜·中国田】 【8斤装】河南助农大蒜干蒜农家自种</p>-->
<!--                                    </div>-->
                                    <div class="discover-collect-box">
                                        <p class="user_likes discover-collect float-left-info" <if condition="$dml['likes']">style="color:#06c1ae"</if>  data-likes="{pigcms{$dml.likes}"  data-discover_id="{pigcms{$dml.discover_id}">
                                        <if condition="$dml['likes']">
                                            <img class="likes_img" src="{pigcms{$static_path}discover/images/icon/likes_a.png?t=001" />
                                            <else/>
                                            <img class="likes_img" src="{pigcms{$static_path}discover/images/icon/likes.png?t=001" />
                                        </if>
                                        &nbsp;<span class="likes_num_val">{pigcms{$dml['likes_num']}</span>
                                        </p>


                                        <p class="line float-left-info"></p>


                                        <p class="user_collect discover-collect" <if condition="$dml['collection']">style="color:#06c1ae"</if>  data-collection="{pigcms{$dml.collection}" data-discover_id="{pigcms{$dml.discover_id}">
                                        <if condition="$dml['collection']">
                                            <img class="collect_img" src="{pigcms{$static_path}discover/images/icon/ft4_a.png?t=001" />
                                            <else/>
                                            <img class="collect_img" src="{pigcms{$static_path}discover/images/icon/ft4.png?t=001" />
                                        </if>
                                        &nbsp;<span class="collection_num_val">{pigcms{$dml['collection_num']}</span>
                                        </p>
                                    </div>

                                    <div class="col-100 discover-bottom clear-both" onclick="location.href='{pigcms{:U('look_discover_msg',array('discover_id'=>$dml['discover_id']))}'">
                                        <div class="bbs-foot">
                                            <p>来自：<span>{pigcms{$dml['type_name']}</span></p>
                                        </div>
                                    </div>
                                    <div class="operation-btn">
                                        <a href="/wap.php?g=Wap&c=Discover&a=discover_msg_edit&discover_id={pigcms{$dml.discover_id}" class="reload">
                                            编辑
                                        </a>
                                        <a data-discover_id="{pigcms{$dml.discover_id}" href="javascript:void(0)" class="delete">
                                            删除
                                        </a>
                                    </div>
                                </div>
                             </li>
                        </volist>
                        <else />
                        <li>
                            <div class="row bbs-my-top">
                                <div class="col-100">
                                    <div class="bbs-foot" style="margin: 0;text-align: center;">
                                        <p style="text-align:center">暂无信息</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                </if>
            </ul>

            <if condition='count($discover_msg_list["list"]) egt 6'>
                <div id="scroller-pullUp">
                    <span id="up-icon" class="icon-double-angle-up pull-up-icon"></span>
                    <span id="pullUp-msg" class="pull-up-msg">上拉刷新</span>
                </div>
            </if>

            <li class="no_more_info" style="display: none;">
                <div class="row bbs-my-top">
                    <div class="col-100">
                        <div class="bbs-foot" style="margin: 0;text-align: center;">
                            <p style="text-align:center">暂无更多信息</p>
                        </div>
                    </div>
                </div>
            </li>
        </div>

    </div>
</div>
{pigcms{$shareScript}
<script type="text/javascript">
    var flag = false;
    var cur = {
        x:0,
        y:0
    }
    var nx,ny,dx,dy,x,y,height ;
    function down(){
        flag = true;
        var touch ;
        if(event.touches){
            touch = event.touches[0];
        }else {
            touch = event;
        }
        // cur.x = touch.clientX;
        cur.y = touch.clientY;
        // dx = div2.offsetLeft;
        dy = div2.offsetTop;
        console.log('高度1： ', dy)
    }
    function move(){
        if(flag){
            var touch ;
            if(event.touches){
                touch = event.touches[0];
            }else {
                touch = event;
            }
            // nx = touch.clientX - cur.x;
            ny = touch.clientY - cur.y;
            // x = dx+nx;
            y = dy+ny;
            // div2.style.left = x+"px";
            height = $(window).height() - 100;
            if (y >= 50 && y <= height) {
                div2.style.top = y +"px";
            }
            //阻止页面的滑动默认事件
            document.addEventListener("touchmove",function(){
                event.preventDefault();
            },false);
        }
    }
    //鼠标释放时候的函数
    function end(){
        flag = false;
    }
    var div2 = document.getElementById("plus");
    div2.addEventListener("mousedown",function(){
        console.log('mousedown')
        down();
    },false);
    div2.addEventListener("touchstart",function(){
        console.log('touchstart')
        down();
    },false)
    div2.addEventListener("mousemove",function(){
        console.log('mousemove')
        move();
    },false);
    div2.addEventListener("touchmove",function(){
        console.log('touchmove')
        move();
    },false)
    document.body.addEventListener("mouseup",function(){
        console.log('mouseup')
        end();
    },false);
    div2.addEventListener("touchend",function(){
        console.log('touchend')
        end();
    },false);

</script>


<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll2.js?444" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
<script type="text/javascript">

    var aa = 1;
    $('.user_collect').click(function(e){
        var discover_id = $(this).data('discover_id');
        var collection = $(this).data('collection');
        console.log('collection--------', collection);
        if(aa == 1){
            if (!collection) {
                collection_msg(discover_id, this);
            } else {
                cancel_collection_msg(discover_id, this);
            }
        }
    });
    // 收藏
    function collection_msg(discover_id, this_node){
        aa =2;
        var collection_msg_url = "{pigcms{:U('collection_msg')}";
        $.post(collection_msg_url,{'discover_id':discover_id},function(data){
            console.log('收藏返回信息：', data);
            aa = 1;
            if(data.status==0){
                layer.open({
                    content: data.msg,
                    btn: ['确定'],
                    shadeClose: false
                });
            }else{
                layer.open({
                    content: '收藏成功！',
                    btn: ['确定'],
                    shadeClose: false
                });
                $(this_node).data('collection',true);
                var collection_num_val = parseInt($(this_node).find('.collection_num_val')[0].innerText) + 1;
                $(this_node).find('.collection_num_val')[0].innerText = collection_num_val;
                $(this_node).find('.collect_img')[0].src = "{pigcms{$static_path}" + 'discover/images/icon/ft4_a.png?t=001';
                $(this_node).css('color', '#06c1ae')
                console.log('收藏返回信息1：', $(this_node).find(".collect_img")[0]);
            }
        },'json')
    }
    // 取消收藏
    function cancel_collection_msg(discover_id, this_node){
        aa =2;
        var cancel_collection_msg_url = "{pigcms{:U('cancel_collection_msg')}";
        $.post(cancel_collection_msg_url,{'discover_id':discover_id},function(data){
            console.log('取消收藏返回信息：', data);
            aa = 1;
            if(data.status==0){
                layer.open({
                    content: data.msg,
                    btn: ['确定'],
                    shadeClose: false
                });
            }else{
                layer.open({
                    content: '取消收藏成功！',
                    btn: ['确定'],
                    shadeClose: false
                });
                $(this_node).data('collection',false);
                var collection_num_val = parseInt($(this_node).find('.collection_num_val')[0].innerText) - 1;
                if (collection_num_val < 0) collection_num_val = 0;
                $(this_node).find('.collection_num_val')[0].innerText = collection_num_val;
                $(this_node).find('.collect_img')[0].src = "{pigcms{$static_path}" + 'discover/images/icon/ft4.png?t=001';
                $(this_node).css('color', '#333')
            }
        },'json')
    }

    var bb=1;
    $('.user_likes').click(function(e){
        var discover_id = $(this).data('discover_id');
        var likes = $(this).data('likes');
        console.log('likes--------', likes)
        if(bb == 1){
            if (!likes) {
                likes_msg(discover_id, this);
            } else {
                cancel_likes_msg(discover_id, this);
            }
        }
    });
    // 点赞
    function likes_msg(discover_id, this_node){
        bb =2;
        var likes_msg_url = "{pigcms{:U('likes_msg')}";
        $.post(likes_msg_url,{'discover_id':discover_id},function(data){
            console.log('点赞返回信息：', data);
            bb = 1;
            if(data.status==0){
                layer.open({
                    content: data.msg,
                    btn: ['确定'],
                    shadeClose: false
                });
            }else{
                layer.open({
                    content: '点赞成功！',
                    btn: ['确定'],
                    shadeClose: false
                });
                $(this_node).data('likes',true)
                var likes_num_val = parseInt($(this_node).find('.likes_num_val')[0].innerText) + 1;
                $(this_node).find('.likes_num_val')[0].innerText = likes_num_val;
                $(this_node).find('.likes_img')[0].src = "{pigcms{$static_path}" + 'discover/images/icon/likes_a.png?t=001';
                $(this_node).css('color', '#06c1ae');
                $(this_node).data('likes',true)
            }
        },'json')
    }
    // 取消点赞
    function cancel_likes_msg(discover_id, this_node){
        aa =2;
        var cancel_likes_msg_url = "{pigcms{:U('cancel_likes_msg')}";
        $.post(cancel_likes_msg_url,{'discover_id':discover_id},function(data){
            console.log('取消点赞返回信息：', data);
            aa = 1;
            if(data.status==0){
                layer.open({
                    content: data.msg,
                    btn: ['确定'],
                    shadeClose: false
                });
            }else{
                layer.open({
                    content: '取消点赞成功！',
                    btn: ['确定'],
                    shadeClose: false
                });
                $(this_node).data('likes',false)
                var likes_num_val = parseInt($(this_node).find('.likes_num_val')[0].innerText) - 1;
                if (likes_num_val < 0) likes_num_val = 0;
                $(this_node).find('.likes_num_val')[0].innerText = likes_num_val;
                $(this_node).find('.likes_img')[0].src = "{pigcms{$static_path}" + 'discover/images/icon/likes.png?t=001';
                $(this_node).css('color', '#333');
            }
        },'json')
    }


    // 删除
    $('.delete').click(function(e){
        if(aa == 1){
            aa =2;
            var discover_id = $(this).data('discover_id');
            console.log('删除的信息id', discover_id);
            layer.open({
                content:'确认删除？',
                btn: ['确定','取消'],
                yes:function(){
                    var del_discover_msg = "{pigcms{:U('del_discover_msg')}";
                    $.post(del_discover_msg,{'discover_id':discover_id},function(data){
                        console.log('删除返回信息：', data);
                        aa = 1;
                        if(data.status==0){
                            layer.open({
                                content: data.msg,
                                btn: ['确定'],
                                shadeClose: false
                            });
                        }else{
                            layer.open({
                                content: '删除成功！',
                                btn: ['确定'],
                                shadeClose: false
                            });
                            window.location.reload();
                        }
                    },'json')
                },
                no: function(){
                    aa = 1;
                }
            });
        }
    });


    function loaded() {
        var myScroll,
            upIcon = $("#up-icon"),
            downIcon = $("#down-icon");
        myScroll = new IScroll('#wrapper', {
            probeType: 3,
            disableMouse: true,
            disablePointer: true,
            mouseWheel: false,
            scrollX: false,
            scrollY: true,
            click: iScrollClick(),
            scrollbars: false,
            useTransform: false,
            useTransition: false
        });

        myScroll.on("scroll", function() {
            var y = this.y,
                maxY = this.maxScrollY - y,
                downHasClass = downIcon.hasClass("reverse_icon"),
                upHasClass = upIcon.hasClass("reverse_icon");

            if(y >= 40) {
                //!downHasClass && downIcon.addClass("reverse_icon");
                //return "";
                location.reload();
            } else if(y < 40 && y > 0) {
                downHasClass && downIcon.removeClass("reverse_icon");
                return "";
            }

            if(maxY >= 40) {
                !upHasClass && upIcon.addClass("reverse_icon");
                return "";
            } else if(maxY < 40 && maxY >= 0) {
                //upHasClass && upIcon.removeClass("reverse_icon");
                return "";
            }
        });




        myScroll.on("slideDown", function() {
            if(this.y > 40) {
                upIcon.removeClass("reverse_icon")
            }
        });

        var discover_index_json_url = "{pigcms{:U('my_discover_list_json')}";
        var static_path = "{pigcms{$static_path}";
        var page=1;
        var more = true;
        myScroll.on("slideUp", function() {
            if(this.maxScrollY - this.y > 40) {
                if (!more) {
                    return false;
                }
                page++;
                $.post(discover_index_json_url,{'page':page},function(data){
                    console.log('返回的数据', data);
                    if(!data.errorCode){
                        var shtml = '';
                        var discover_msg = data['result'];
                        var discover_msg_list = data['result']['list'];
                        if (!discover_msg_list) {
                            myScroll.refresh();
                            more = false;
                            $('.no_more_info')[0].style.display = 'block';
                            return false;
                        }
                        $('.no_more_info')[0].style.display = 'none';
                        more = true;
                        for(var i in discover_msg_list){
                            shtml += '<li>';
                            shtml += '<div class="row bbs-my-top">';
                            shtml += '<div class="col-20 bbs-my-avather" onclick="location.href=\'/wap.php?g=Wap&c=Discover&a=look_discover_msg&discover_id='+discover_msg_list[i]['discover_id']+'\'">';
                            if (discover_msg_list[i]['avatar']) {
                                shtml += '<img src="'+discover_msg_list[i]['avatar']+'" width="100px" height="100px">';
                            } else {
                                shtml += '<img src="'+discover_msg['default_avatar']+'" width="100px" height="100px">';
                            }
                            shtml += '</div>';

                            shtml += '<div class="col-80 bbs-top-info" onclick="location.href=\'/wap.php?g=Wap&c=Discover&a=look_discover_msg&discover_id='+discover_msg_list[i]['discover_id']+'\'">';
                            shtml += '<p>'+discover_msg_list[i]['nickname']+'<span>'+discover_msg_list[i]['add_time']+'</span>'+'</p>';



                            shtml += '</div>';
                            shtml += '<div class="col-100">';
                            shtml += '<div class="bbs-desc">';
                            shtml += '<p>'+ discover_msg_list[i]['discover_content'].substr(0,50)+'</p>';
                            shtml += '</div>';
                            shtml += '</div>';

                            if(discover_msg_list[i]['discover_img']){
                                var discover_img_arr = discover_msg_list[i]['discover_img'];
                                shtml += '<div class="col-100" onclick="location.href=\'/wap.php?g=Wap&c=Discover&a=look_discover_msg&discover_id='+discover_msg_list[i]['discover_id']+'\'">';
                                shtml += '<div class="bbs-image">';
                                shtml += '<ul>';
                                for(var j in discover_img_arr){
                                    shtml += '<li><img src="'+discover_img_arr[j]+'"></li>';
                                }
                                shtml += '</ul></div>';
                                shtml += '</div>';
                            }

                            // shtml += '<div class="relate-topic"><img src="111.png"><p>中国甜·中国田】 【8斤装】河南助农大蒜干蒜农家自种</p></div>'

                            shtml += '<div class="discover-collect-box">'

                            // 点赞区域
                            shtml += '<p class="user_likes discover-collect float-left-info" ';
                            if (discover_msg_list[i]['likes']) {
                                shtml += ' style="color:#06c1ae"  data-likes="'+discover_msg_list[i]['likes']+'" data-discover_id="'+discover_msg_list[i]['discover_id']+'">';
                                shtml += '<img class="likes_img" src="'+static_path+'discover/images/icon/likes_a.png?t=001" />';
                                shtml += '&nbsp;<span class="likes_num_val">'+discover_msg_list[i]['likes_num']+'</span>'
                            } else {
                                shtml += ' data-likes="'+discover_msg_list[i]['likes']+'" data-discover_id="'+discover_msg_list[i]['discover_id']+'">';
                                shtml += '<img class="likes_img" src="'+static_path+'discover/images/icon/likes.png?t=001" />';
                                shtml += '&nbsp;<span class="likes_num_val">'+discover_msg_list[i]['likes_num']+'</span>'
                            }
                            shtml += '</p>';

                            shtml += '<p class="line float-left-info"></p>';

                            // 收藏区域

                            shtml += '<p class="user_collect discover-collect" ';
                            if (discover_msg_list[i]['collection']) {
                                shtml += ' style="color:#06c1ae"  data-collection="'+discover_msg_list[i]['collection']+'" data-discover_id="'+discover_msg_list[i]['discover_id']+'">';
                                shtml += '<img class="collect_img" src="'+static_path+'discover/images/icon/ft4_a.png?t=001" />';
                                shtml += '&nbsp;<span class="collection_num_val">'+discover_msg_list[i]['collection_num']+'</span>'
                            } else {
                                shtml += ' data-likes="'+discover_msg_list[i]['likes']+'" data-discover_id="'+discover_msg_list[i]['discover_id']+'">';
                                shtml += '<img class="collect_img" src="'+static_path+'discover/images/icon/ft4.png?t=001" />';
                                shtml += '&nbsp;<span class="collection_num_val">'+discover_msg_list[i]['collection_num']+'</span>'
                            }
                            shtml += '</p>';

                            shtml += '</div>'


                            shtml += '<div class="col-100 discover-bottom clear-both" onclick="location.href=\'/wap.php?g=Wap&c=Discover&a=look_discover_msg&discover_id='+discover_msg_list[i]['discover_id']+'\'">';
                            shtml += '<div class="bbs-foot">';

                            if(discover_msg_list[i]['type_name']){
                                shtml += '<p>来自：<span>'+discover_msg_list[i]['type_name']+'</span></p>';
                            }

                            shtml += '</div>';
                            shtml += '</div>';

                            shtml += '<div class="operation-btn">';
                            shtml += '<a  onclick="location.href=\'/wap.php?g=Wap&c=Discover&a=discover_msg_edit&discover_id='+discover_msg_list[i]['discover_id']+'\'" class="reload">编辑</a>';
                            shtml += '<a data-discover_id="'+discover_msg_list[i]['discover_id']+'" href="javascript:void(0)" class="delete">删除</a>';
                            shtml += '</div>';

                            shtml += '</div></li>';
                        }
                        $('.bbs-list').append(shtml);
                        myScroll.refresh();

                        $('.user_collect').click(function(e){
                            var discover_id = $(this).data('discover_id');
                            var collection = $(this).data('collection');
                            console.log('collection--------', collection);
                            if(aa == 1){
                                if (!collection) {
                                    collection_msg(discover_id, this);
                                } else {
                                    cancel_collection_msg(discover_id, this);
                                }
                            }
                        });

                        $('.user_likes').click(function(e){
                            var discover_id = $(this).data('discover_id');
                            var likes = $(this).data('likes');
                            console.log('likes--------', likes)
                            if(bb == 1){
                                if (!likes) {
                                    likes_msg(discover_id, this);
                                } else {
                                    cancel_likes_msg(discover_id, this);
                                }
                            }
                        });


                        // 删除
                        $('.delete').click(function(e){
                            if(aa == 1){
                                aa =2;
                                var discover_id = $(this).data('discover_id');
                                console.log('删除的信息id', discover_id);
                                layer.open({
                                    content:'确认删除？',
                                    btn: ['确定','取消'],
                                    yes:function(){
                                        var del_discover_msg = "{pigcms{:U('del_discover_msg')}";
                                        $.post(del_discover_msg,{'discover_id':discover_id},function(data){
                                            console.log('删除返回信息：', data);
                                            aa = 1;
                                            if(data.status==0){
                                                layer.open({
                                                    content: data.msg,
                                                    btn: ['确定'],
                                                    shadeClose: false
                                                });
                                            }else{
                                                layer.open({
                                                    content: '删除成功！',
                                                    btn: ['确定'],
                                                    shadeClose: false
                                                });
                                                window.location.reload();
                                            }
                                        },'json')
                                    },
                                    no: function(){
                                        aa = 1;
                                    }
                                });
                            }
                        });
                    }


                },'json')
            }
        });
    }


    $('#plus').click(function(){
        location.href = "{pigcms{:U('add_discover_msg')}"
    });
</script>

</html>
</body>

</html>
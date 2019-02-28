<!DOCTYPE html>
<html>
    <link href="{pigcms{$static_path}css/tieba.css" type="text/css" rel="stylesheet" />
    <!-- <link href="{pigcms{$static_path}css/dragCode.css" type="text/css" rel="stylesheet" /> -->
    <include file="Public/header" />
    <script type="text/javascript" src="{pigcms{$static_public}layer/layer.js"></script>
    <link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
    <script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>

    <div class="content w-1200 clearfix">
        <div class="site_crumb clearfix">
            <span>当前位置：</span>
            <a href="{pigcms{:U('Portal/Index/index')}">首页</a>
            <span class="cur_tit">贴吧</span>
        </div>
        <div class="tieba clearfix">
            <!--侧栏-->
            <div class="tb_side right">
                <div class="title">我的贴吧</div>

                <div class="user_info">
                    <if condition="$user_session['uid'] gt 0">
                        <div class="head_img"> <img src="{pigcms{$user_session['avatar']}"/> </div>
                        <div style="text-align:center;"> {pigcms{$user_session['nickname']}  </div>
                    <else/>
                        <div class="head_img"> <img src="{pigcms{$static_path}images/defalut_face.gif"/> </div>
                        <div style="text-align:center;"> 您还没有 <a href="{pigcms{$config['site_url']}/index.php?g=Index&c=Login&a=index">登录</a> </div>
                    </if>
                    
                </div>

                <div class="tb_nav">
                    <ul>
                        <li id="s_a_0" class="all">
                            <a href="">全部版块</a>
                        </li>
                        <volist name="tiebaPlateList" id="vo">
                            <li class="<if condition="$_GET['plate_id'] eq $vo['plate_id']">cur</if>">
                                <a href="{pigcms{:U('Tieba/index',array('plate_id'=>$vo['plate_id']))}">{pigcms{$vo.plate_name}</a>
                            </li>
                        </volist>
                    </ul>
                </div>
            </div>
            <!--侧栏 ernd-->
            <!--列表-->
            <div class="col_tb_main left">
                <div class="tongji clearfix">
                    <div class="left">
                        <span class="tit" id="tiebaCatChr">全部帖子</span>
                        帖子： <em>{pigcms{$count}</em>
                    </div>
                    <div class="right">
                        <input type="text"  id="keyword" value="{pigcms{$_GET['search']}" onkeypress="if(event.keyCode==13){return setsearchword();}" class="kw" placeholder="请输入关键字" />
                        <input type="submit" value="搜索" class="sbtn" onclick="setsearchword()"/>
                    </div>
                </div>
                <div class="blank10"></div>
                <div class="filter_title clearfix" id="filter_title">
                    <ul class="clearfix">
                        <li class="item  <if condition="$_GET['essence'] neq 1">cur</if>">
                            <a href="{pigcms{:U('Tieba/index')}" class="all p">全部帖子</a>
                        </li>
                        <li class="item <if condition="$_GET['essence'] eq 1">cur</if>">
                            <a href="javascript:void(0);" onclick="essence()" class="j p">精华帖</a>
                        </li>
                    </ul>
                    <div class="fabu_btn">
                        <a href="#fafafati" class="link" id="myFabu_1">发帖</a>
                    </div>
                    <div class="s" id="bazhuNode"></div>
                </div>
                <div class="blank10"></div>
                <ul class="paixu clearfix">
                    <li class="tit">排序方式：</li>
                    <li <if condition="$_GET['order'] eq 'last_time' OR $_GET['order'] eq ''">class="cur"</if>>
                        <a href="javascript:void(0);" onclick="listSort('last_time','desc')">最后回复(默认)</a>
                    </li>
                    <li <if condition="$_GET['order'] eq 'add_time'">class="cur"</if>>
                        <a href="javascript:void(0);" onclick="listSort('add_time','desc')">最新发贴</a>
                    </li>
                    <li <if condition="$_GET['order'] eq 'reply_sum'">class="cur"</if>>
                        <a href="javascript:void(0);" onclick="listSort('reply_sum','desc')">最多回复</a>
                    </li>
                    <li <if condition="$_GET['order'] eq 'pageviews'">class="cur"</if>>
                        <a href="javascript:void(0);" onclick="listSort('pageviews','desc')">最多浏览</a>
                    </li>
                </ul>
                <div class="blank10"></div>
                <div class="tb_main">

                    <div class="list">
                        <ul id="pagingList">
                            <if condition="is_array($tiebaList)">
                                <volist name="tiebaList" id="vo">
                                        <li>
                                            <div class="con">
                                                <if condition="$vo['plate_name'] neq ''"><span class="category">[{pigcms{$vo.plate_name}]</span></if>
                                                <a href="{pigcms{:U('Tieba/detail',array('tie_id'=>$vo['tie_id']))}" target="_blank" style="color:" class="title bold1">{pigcms{$vo.title}</a>
                                                <if condition="$vo['is_essence'] eq 1"><span class="ico">精华</span></if>
                                                <if condition="$vo['is_top'] eq 1"><span class="ico ico_zhiding">置顶</span></if>
                                                <span class="rcount"> {pigcms{$vo.pageviews} <s class="s"></s> </span>
                                                <p style="">{pigcms{$vo.content}...</p>
                                                <div class="n_img" id="pic_list_{pigcms{$vo['tie_id']}">
                                                    <volist name="vo['pic']" id="vovo">
                                                        <a href="javascript:void(0);" onclick="pic_show('{pigcms{$vovo}',{pigcms{$vo.tie_id})" target="_blank" class="itemAlbum" >
                                                            <img src="{pigcms{$vovo}" style="width: 130px; height: 90px;" />
                                                            <div class="feed_highlight"></div>
                                                        </a>
                                                    </volist>
                                                </div>
                                                <div class="media_box" id="media_box_{pigcms{$vo['tie_id']}" style="display: none;">
                                                    <div class="p_tools">
                                                        <a class="p_putup" href="javascript:void(0);" onclick="pic_hide({pigcms{$vo.tie_id})">收起</a><span class="line">|</span>
                                                        <a class="tb_icon_ypic" id="tb_icon_ypic_{pigcms{$vo['tie_id']}" href="" target="_blank">查看大图</a>
                                                    </div>
                                                    <div class="media_bigpic_wrap">
                                                        <img class="j_large_pic" id="j_large_pic_{pigcms{$vo['tie_id']}" onclick="pic_hide({pigcms{$vo.tie_id})" src="" width="600" height="450" step="0" style="visibility: visible; position: static; width: 600px; height: 450px;"></div>
                                                    <div class="bigpic_display_pre bigpic_turn" style="display: none;"></div>
                                                    <div class="bigpic_display_next bigpic_turn" style="display: none;"></div>
                                                </div>
                                            </div>
                                            <div class="clearfix">
                                                <div class="users">
                                                    <span class="rele">{pigcms{$vo.nickname}</span>
                                                    <span class="timer">{pigcms{$vo.add_time|date="m月d日 H:i",###}</span>
                                                    <span class="reply display0kkkkk">{pigcms{$vo.last_nickname}</span>
                                                    <span class="timer display0kkkkk">{pigcms{$vo.last_time|date="m月d日 H:i",###}</span>
                                                </div>
                                            </div>
                                        </li>
                                    </volist>
                            <else/>
                                <tr><td colspan="50"><div style=" font-size:18px; text-align:center; padding:100px 0;" id="listEmpty">没有找到符合条件的信息</div></td></tr>
                            </if>
               
                        </ul>

                        <div class="pageNavigation" id="pageNavigation"> 
                            {pigcms{$pagebar}
                        </div>


                        <div class="send_new clearfix" id="fafafati">
                            <h4 id="fabuForm">发表新帖</h4>
                            <form action="{pigcms{:U('Tieba/add')}" method="post" id="myformFabu"  enctype="multipart/form-data">
                                <div class="title"><input type="text" id="title" maxlength="50" name="title" placeholder="请填写标题"></div>
                                <div class="editor"><textarea name="content" id="content" style="width: 965px; height: 350px;"></textarea></div>
                                <div style="margin-top: 15px;">
                                    <label style="float: left;">验证码：</label>
                                    <span style="float: left; width: 80px;">
                                        <input class="text-input" type="text" id="verify" style=" border: 1px solid #d4d4d4;     font-size: 14px; border-radius:4px;width:70px; height:25px;" maxlength="4" name="verify"/>
                                    </span>
                                    <span id="verify_box">
                                        <img src="{pigcms{:U('Tieba/verify')}" id="verifyImg" onclick="fleshVerify('{pigcms{:U('Tieba/verify')}')" title="刷新验证码" alt="刷新验证码"/>
                                        <a href="javascript:fleshVerify('{pigcms{:U('Tieba/verify')}')" id="fleshVerify">刷新验证码</a>
                                    </span>
                                </div>
                            </form>
                            <div class="send_btn clearfix">
                                <a href="{pigcms{:U('Tieba/add')}" target="_blank" class="right blue">[高级发帖模式]</a>
                                <input type="submit" value="发表" onclick="fabiao()" class="send">承诺遵守文明发帖，国家相关法律法规
                            </div>
                        </div>

                        

                    </div>
                </div>
            </div>
        </div>
    </div>

<input type="hidden" name="order" id="order" value="{pigcms{$_GET['order']}"/>
<input type="hidden" name="sort" id="sort" value="{pigcms{$_GET['order']}"/>
<input type="hidden" name="plate_id" id="plate_id" value="{pigcms{$_GET['plate_id']}"/>
<input type="hidden" name="essence" id="essence" value="{pigcms{$_GET['essence']}"/>
<input type="hidden" name="search" id="search" value="{pigcms{$_GET['search']}"/>
<script>
                            function fleshVerify(url){
                                var time = new Date().getTime();
                                $('#verifyImg').attr('src',url+"&time="+time);
                            }
                        </script>
    <script type="text/javascript">
  
        KindEditor.ready(function(K){
            var editor = K.editor({
                allowFileManager : true
            });
            // 初始化信息编辑器
            kind_editor_msg = K.create("#content",{
                uploadJson: "{pigcms{:U('Tieba/ajax_upload_pic')}",
                width:'965px',
                height:'350px',
                resizeType : 0,
                allowPreviewEmoticons:false,
                allowImageUpload : true,
                filterMode: true,
                items : [
                     'fullscreen','fontsize','bold','justifyleft', 'justifycenter', 'justifyright', '|', 'emoticons', 'image'
                ]
            });
        });


        function fabiao(){
            kind_editor_msg.sync();
            
            var uid = "{pigcms{$user_session['uid']}";
            if(!uid){ 
                layer.msg('请先登录，然后再进行发帖',{time:1500},function(){
                    window.location.href = "{pigcms{$config.site_url}"+'/index.php?g=Index&c=Login&a=index';
                }); 
                return false;
            }
            
            var title = $("#title").val();
            if(!title){
                layer.msg('请填写标题');
                return false;
            }

            var verify = $("#verify").val();
            if(!verify){
                layer.msg('请填验证码');
                return false;
            }
            
            $("#myformFabu").submit();
        }

        function pic_show(url,tie_id){
            $("#j_large_pic_"+tie_id).attr("src",url);
            $("#tb_icon_ypic_"+tie_id).attr("href",url)
            $("#media_box_"+tie_id).show();
            $("#pic_list_"+tie_id).hide();
        }

        function pic_hide(tie_id){
            $("#media_box_"+tie_id).hide();
            $("#pic_list_"+tie_id).show();
        }


        function listSort(order,sort){
            $("#order").val(order);
            $("#sort").val(sort);
            getUrl();
        }

        function essence(){
            $("#essence").val(1);
            getUrl();
        }

        function setsearchword(type){
            var search = $("#keyword").val();
            $("#search").val(search)
            getUrl();
        }


        function getUrl(){
            var data = '';
            var plate_id = $("#plate_id").val();
            var order = $("#order").val();
            var sort = $("#sort").val();
            var essence = $("#essence").val();
            var search = $("#search").val();

            if(essence){
                var data = data + "&essence="+essence;
            }
            if(plate_id){
                var data = data + "&plate_id="+plate_id;
            }
            if(order){
                var data = data + "&order="+order;
            }
            if(sort){
                var data = data + "&sort="+sort;
            }
            if(search){
                var data = data + "&search="+search;
            }
            location.href = "{pigcms{:U('Tieba/index')}"+data;
        }
        
    </script>
<include file="Public/footer" />
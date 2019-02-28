<include file="Public/header" />
<link href="{pigcms{$static_path}css/tieba.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="{pigcms{$static_public}layer/layer.js"></script>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script>
	window['Bigcategory'] = '2265';
	window['istiebaNav'] = true;
</script>
<style type="text/css">
	.wrap_kalendar_node { left:auto; right:-3px;}
	.sign_tip_bd_arr { left:auto; right:70px;}
</style>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/codemirror.css">

		<div class="content w-1200 clearfix">
			<div class="site_crumb clearfix">
				<span>当前位置：</span>
				<a href="{pigcms{:U('Portal/Index/index')}">首页</a>
				<a href="{pigcms{:U('Tieba/index')}">贴吧</a>
				<a href="{pigcms{:U('Portal/Tieba/index',array('plate_id'=>$tieInfo['plate_id']))}" class="display2265">{pigcms{$tieInfo.plate_name}</a>
				<span class="cur_tit">{pigcms{$tieInfo.title}</span>
			</div>

			<div class="tieba clearfix">
				<!--侧栏-->
				<div class="tb_side right">
					<div class="reg" id="qiandaoWrap">
						<div class="qdSuccess" id="qdSuccess"></div>
					</div>
					<div class="title">我的贴吧</div>

					<div class="user_info">
	                    <if condition="$user_session['uid'] gt 0">
	                        <div class="head_img"><a href="" target="_blank"> <img src="{pigcms{$user_session['avatar']}"></a></div>
							<div class="user_name"> <a href="" target="_blank">{pigcms{$user_session['nickname']}</a> </div>
							<div class="member_type"> 初级</div> 
							<div class="bazhu display0">版主</div>
							<ul>
								<li class="s_tiezi"> 帖子： <em>{pigcms{$tieCount}</em> </li>
								<li> 精华： <em>{pigcms{$tieEssenceCount}</em> </li>
							</ul>
	                    <else/>
	                        <div class="head_img"> <img src="{pigcms{$static_path}images/defalut_face.gif"/> </div>
	                        <div style="text-align:center;"> 您还没有 <a href="" target="_blank">登录</a> </div>
	                    </if>
	                </div>

					<div class="tb_nav">
	                    <ul>
	                        <li id="s_a_0" class="all">
	                            <a href="">全部版块</a>
	                        </li>
	                        <volist name="tiebaPlateList" id="vo">
	                            <li>
	                                <a href="{pigcms{:U('Tieba/index',array('plate_id'=>$vo['plate_id']))}">{pigcms{$vo.plate_name}</a>
	                            </li>
	                        </volist>
	                    </ul>
	                </div>


					<!-- <div class="ewm">
						<p>手机访问贴吧</p>
						<img src="{pigcms{$static_path}images/201507200931263214230.jpg" ></div> -->
				</div>
				<!--侧栏 ernd-->
				<!--列表-->

				<div class="tb_main col_tb_main left" id="resizeIMG">
					<div class="post_title">
						<h2>
							<if condition="$tieInfo.plate_id gt 0"><span class="bigcategoryid display2265">[{pigcms{$tieInfo.plate_name}]</span></if>
							{pigcms{$tieInfo.title}
							<if condition="$tieInfo.is_top eq 1"><span class="ico ico_zhiding">置顶</span></if>
							<if condition="$tieInfo.is_essence eq 1"><span class="ico">精华</span></if>
						</h2>

						<div class="info"> 人气：<em>{pigcms{$tieInfo.pageviews}</em> 回复：<em>{pigcms{$tieInfo.reply_sum}</em> </div>
						<div class="po">

							<if condition="$_GET['single'] eq 1">
								
							<a href="{pigcms{:U('Tieba/detail',array('tie_id'=>$_GET['tie_id']))}" class="lz cur">只看楼主</a>
							<else/>
								<a href="{pigcms{:U('Tieba/detail',array('tie_id'=>$_GET['tie_id'],'single'=>1))}" class="lz">只看楼主</a>
							</if>
							
							<a href="javascript:void(0);" onclick="collection({pigcms{$col_status})" class="fav"><if condition="$col_status eq 1">已收藏<else/>收藏</if></a>
							<a href="#replyTie" class="quick_reply">回复</a>
						</div>
					</div>



					<div class="post_item user_51 clearfix" id="louzhuNode" data-userid="51">

						<div class="t_author">
							<i class="icon"></i>
							<i class="icon_online" title="在线"></i>
							<div class="author_face">
								<img src="{pigcms{$tieInfo.avatar}"></div>
							<div class="username">{pigcms{$tieInfo.nickname}</div>
							<div class="badge"> 初级 <em>1</em> </div>
							<if condition="$tieInfo['plate_admin_status']"><div class="bazhu">版主</div></if>
							<!-- <ul>
								<li> 帖子： <em>4</em> </li>
								<li> 精华： <em>1</em> </li> 
							</ul> -->
						</div>

						<script type="text/javascript" src="{pigcms{$static_public}ckplayer/ckplayer.js"></script>
						<div class="post_con">
							<div class="con">
								<if condition="$tieInfo['type'] eq 1">
									<div class="video" style="width: 100%;height: 600px;"></div>
									<script type="text/javascript">
										var videoObject = {
											container: '.video',//“#”代表容器的ID，“.”或“”代表容器的class
											variable: 'player',//该属性必需设置，值等于下面的new chplayer()的对象
											poster:'pic/wdm.jpg',//封面图片
											video:"{pigcms{$tieInfo.videoUrl}"
										};
										var player=new ckplayer(videoObject);
									</script>
								<else/>
									{pigcms{$tieInfo.content|htmlspecialchars_decode}
								</if>
							</div>
							<div class="clearfix">
								<div class="bot_reply" id="louzhuCon">
									<span class="r_time">{pigcms{$tieInfo.add_time|date="Y/m/d H:i:s",###}</span>
									
									<if condition="$plate_admin_status eq 1">
										<span>
											<if condition="$tieInfo['is_essence'] eq 1">
												<a href="javascript:void(0);" onclick="set_essence_top({pigcms{$tieInfo.tie_id},'is_essence',0)" class="blue">取消精华</a>
											<else/>
												<a href="javascript:void(0);" onclick="set_essence_top({pigcms{$tieInfo.tie_id},'is_essence',1)" class="blue">精华</a>
											</if>

											<if condition="$tieInfo['is_top'] eq 1">
												<a href="javascript:void(0);" onclick="set_essence_top({pigcms{$tieInfo.tie_id},'is_top',0)" class="blue">取消置顶</a>
											<else/>
												<a href="javascript:void(0);" onclick="set_essence_top({pigcms{$tieInfo.tie_id},'is_top',1)" class="blue">置顶</a>
											</if>

											<a href="javascript:void(0);" onclick="tie_del({pigcms{$tieInfo.tie_id},1)" class="blue">删除</a>
										</span>

									<elseif condition="$user_session['uid'] eq $tieInfo['uid']"/>
										<span class="">
											<a href="javascript:void(0);" onclick="tie_del({pigcms{$tieInfo.tie_id},1)" class="blue">删除</a>
										</span>
									</if>
								</div>
							</div>
							<div class="reply_con"></div>
						</div>

					</div>
			<script>
				function set_essence_top(tie_id,type,status){
					var setEssenceTopUrl = "{pigcms{:U('Tieba/set_essence_top')}";
					$.post(setEssenceTopUrl,{'tie_id':tie_id,'type':type,'status':status},function(data){
						if(data.error == 1){
							layer.msg(data.msg,{time:500},function(){
								window.location.href = location.href;
							});
						}else{
							layer.msg(data.msg);
						}
					},'json');
				}
			</script>
				<div id="pagingList" class="isrevert1">
				 
					<volist name="tieList" id="vo">
						<div class="post_item user_48 clearfix" data-userid="48">
							<div class="t_author">
								<i class="icon_online" title="在线"></i>
								<div class="author_face"><img src="{pigcms{$vo.avatar}"></div>
								<div class="username">{pigcms{$vo.nickname}</div>
								<div class="badge"> 初级 </div>
								<if condition="$vo['plate_admin_status']"><div class="bazhu">版主</div></if>
								<!-- <ul>
									<li> 帖子： <em>8</em> </li>
									<li> 精华： <em>0</em> </li>
								</ul> -->
							</div>
							<div class="post_con">
								<div class="con">
									<div class="replaycontent1">
										{pigcms{$vo.content|htmlspecialchars_decode}
									</div>
								</div>
								<div class="comment_vote">
								<span class="r_time">{pigcms{$vo.sort} 楼&nbsp;&nbsp;&nbsp;&nbsp;{pigcms{$vo.add_time|date="Y/m/d H:i",###}</span>
									<a href="#replyTie" onclick="replya('{pigcms{$vo.tie_id}','{pigcms{$vo.nickname}','{pigcms{$vo.sort}')" class="replay_btn replay_life">回复</a>
									
									<if condition="$user_session['uid'] eq $tieInfo['uid']">
										<span> <a href="javascript:void(0);" onclick="tie_del({pigcms{$vo.tie_id},2)" class="blue">| 删除</a> </span>
									<else/>
										<volist name="plateUser" id="puvo">
											<if condition="$user_session['uid'] eq $puvo['uid']">
												<span> <a href="javascript:void(0);" onclick="tie_del({pigcms{$vo.tie_id},2)" class="blue">| 删除</a> </span>
											</if>
										</volist>
									</if>
									

								</div>
							</div>
						</div>
					</volist>
				</div>

				<div class="pageNavigation" id="pageNavigation">
					{pigcms{$pagebar}
				</div>

				<!--精华帖-->
				<if condition="is_array($essenceList)">
					<div class="reco_arc">
						<h4>精华帖推荐</h4>
						<ul class="clearfix">
							<volist name="essenceList" id="vo">
								<li> <a href="{pigcms{:U('Tieba/detail',array('tie_id'=>$vo['tie_id']))}" target="_blank">{pigcms{$vo.title}</a> {pigcms{$vo.nickname} </li>
							</volist>
						</ul>
					</div>
				</if>
				

				<!--发表新帖-->

				<div class="send_new clearfix" id="replyTie">
				    <h4>我要回复</h4>
				    	<!-- <div><h3>回复100楼</h3></div> -->
				    
				        <div class="editor">
				            <textarea name="content" id="content" style="width: 966px; height: 200px;"></textarea>
				        </div>
				        <div class="send_btn"><input type="submit" onclick="reply_submit()" class="send">承诺遵守文明发帖，国家相关法律法规</div>


				</div>

			</div>
		</div>
	</div>
</div>
<input type="hidden" name="reply_tie_id" id="reply_tie_id" value=""/>
<script type="text/javascript">

    KindEditor.ready(function(K){
        var editor = K.editor({
            allowFileManager : true
        });
        // 初始化信息编辑器
        kind_editor = K.create("#content",{
            uploadJson: "{pigcms{:U('Tieba/ajax_upload_pic')}",
            width:'966px',
            height:'350px',
            resizeType : 0,
            allowPreviewEmoticons:false,
            allowImageUpload : true,
            filterMode: true,
            items : [
                'fullscreen','emoticons', 'image'
            ]
        });
    });

    function reply_submit(){
    	kind_editor.sync();
    	var uid = "{pigcms{$user_session['uid']}";
    	if(!uid){
    		layer.msg('请先登录，再进行回复');
    		return false;
    	}
    	var replyUrl = "{pigcms{:U('Tieba/reply')}";
    	var target_id = "{pigcms{$tieInfo.tie_id}";
    	var reply_tie_id = $("#reply_tie_id").val();
    	var sort = Number("{pigcms{$tieInfo.sort}")+1;
    	var content = $("#content").val();
    	if(!content){
    		layer.msg('回复内容不可以为空！');
    		return false;
    	}
    	$.post(replyUrl,{'target_id':target_id,'reply_tie_id':reply_tie_id,'sort':sort,'content':content},function(data){
    		if(data.error == 1){
    			layer.msg(data.msg, {time: 500},function(){
    				window.location.href  = location.href;
    			})
    		}else{
    			layer.msg(data.msg)
    		}
    	},'json');
    }

    function replya(tie_id,nickname,sort){
    	kind_editor.sync();
    	$("#reply_tie_id").val(tie_id);
    	if(nickname){
			kind_editor.html('回复 '+nickname+'&nbsp; '+sort+'楼:');
    	}else{
    		kind_editor.html('回复 '+sort+' 楼 :');
    	}
    }

    function collection(col_status){
    	var cal_name = $(".fav").html();
    	if(cal_name == '收藏'){
    		cal_name = '已收藏';
    	}else{
    		cal_name = '收藏';
    	}
    	var tie_id = "{pigcms{$tieInfo.tie_id}";
    	var uid = "{pigcms{$user_session['uid']}";
    	var collectionUrl = "{pigcms{:U('Tieba/tieba_collection')}";

    	if(!uid){ 
	        layer.msg('请先登录，然后再进行收藏',{time:1500},function(){
	        	window.location.href = "{pigcms{$config.site_url}"+'/index.php?g=Index&c=Login&a=index';
	        }); 
	        return false;
	    }

    	$.post(collectionUrl,{'tie_id':tie_id},function(data){
    		if(data.error == 1){
    			$(".fav").html(cal_name);
    			layer.msg(data.msg);
    		}else{
    			layer.msg(data.msg);
    		}
    	},'json');
    }

    function tie_del(tie_id,type){
		//询问框
		layer.confirm('您确定要删除这条信息吗?', {
			btn: ['确定','取消'] //按钮
		}, function(){
			var delUrl = "{pigcms{:U('Tieba/tie_del')}";
	    	$.post(delUrl,{'tie_id':tie_id,'type':type},function(data){
				if(data.error == 1){
					layer.msg(data.msg,{time:1000},function(){
						if(data.type == 1){
							window.location.href = "{pigcms{:U('Tieba/index')}";
						}else{
							window.location.href = location.href;
						}
						
					})
				}else{
					layer.msg(data.msg);
				}	
	    	},'json');
		});
    }
</script>
<div class="rmenu" id="rmenu" style="right: 281px;">
	<ul>
		<li class="huifu">
			<a href="#replyTie" class="link" id="myFabu_1" title="回复">回复</a>
		</li>
		<li class="fabu">
			<a href="{pigcms{:U('Tieba/add')}" target="_blank" class="link" id="seniorSend" title="发帖">发帖</a>
		</li>
		<li id="top" style="display: list-item;">
			<a href="javascript:void(0);" class="link" id="iGo2Top"  title="返回顶端">返回顶端</a>
		</li>
	</ul>
</div>
<include file="Public/footer"/>
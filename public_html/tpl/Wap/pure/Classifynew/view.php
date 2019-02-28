<!doctype html>
<html>
<head>
	<include file="header"/>
	<style>
		.b-color13:after{
			content:none;
		}
		.b-color13{
			border:1px solid #D5D5D6;
		}
		.b-color13 .needsclick{
			width:calc(100% - 4px);
		}
	</style>
</head>
<body>
	<div class="weui-pull-to-refresh__layer">
		<div class='weui-pull-to-refresh__arrow'></div>
		<div class='weui-pull-to-refresh__preloader'></div>
		<div class="down">下拉刷新</div>
		<div class="up">释放刷新</div>
		<div class="refresh">正在刷新</div>
	</div>
	<div class="page__bd">
		<if condition="!$is_wexin_browser && !$is_app_browser">
			<header class="x_header bgcolor_11 cl f15">
				<a class="z f14" href="javascript:window.history.go(-1);"><i class="iconfont icon-fanhuijiantou w15"></i>返回</a>
				<a class="y sidectrl view_ctrl"><i class="iconfont icon-gengduo1 f22"></i></a>
				<div class="navtitle">{pigcms{$page_title}</div>
			</header>
			<div class="x_header_fix" ></div>
		</if>
		<div class="none view_tools_mask"></div>
		<div class="none view_tools animated">
			<ul>
				<li class="border_bottom"><a href="javascript:;" style="left:-3px" class="we_share pr" data-id="{pigcms{$detail.id}"><i class="vm iconfont icon-fenxiang1 f20"></i> 分享</a></li>
				<li class="border_bottom"><a href="{pigcms{:U('category')}"><i class="vm iconfont icon-fenlei "></i> 分类</a></li>
				<li><a href="{pigcms{:U('my')}"><i class="vm iconfont icon-xiaolian2 "></i> 我的</a></li>
			</ul>
		</div>
		<div class="weui-cells mt0 before_none after_15">
			<div class="weui-cell">
				<div class="weui-cell__hd" style="position: relative;margin-right: 10px;">
					<a href="{pigcms{:U('member',array('uid'=>$user['uid']))}">
						<img src="{pigcms{$user.avatar|default='./static/images/user_avatar.jpg'}" class="v_head"/>
					</a>
				</div>
				<div class="weui-cell__bd">
					<a class="a da f14" href="{pigcms{:U('member',array('uid'=>$user['uid']))}">{pigcms{$user.nickname}</a>
					<div class="secp">
						<div class="mod-lv is-green ml0">{pigcms{$detail.cat_name}</div>
						<if condition="$detail['topsort']">
							<div class="mod-lv is-star ml0">置顶</div>
						</if>
						<if condition="$detail['redpack_count']">
							<div class="mod-lv is-hot ml0">红包</div>
						</if>
						<span class="c9 f12 inblock">{pigcms{$detail.addtime|date='m-d H:i',###} 发布</span>
					</div>
				</div>
				<if condition="$detail['redpack_count']">
					<div class="weui-cell_ft" style="text-align:right">
						<span class="color-red">
							<i class="iconfont icon-hongbao3 f18"></i>
							<span class="f12">&yen;</span>
							<span class="f20">{pigcms{$detail.redpack_money}</span>
						</span>
					</div>
				</if>
			</div>
		</div>
        <article class="weui-article mt0">
			<section>
				<p>{pigcms{$detail.description}</p>
				<if condition="$content">
					<div class="cl mb8">
						<volist name="content" id="vo">
							<if condition="!empty($vo['vv']) && is_string($vo['vv'])">
								<span class="block"><span class="main_color">{pigcms{$vo['tn']}：</span>{pigcms{$vo.vv}</span>
							</if>
						</volist>
					</div>
				</if>
				<if condition="$content_label">
					<div class="cl mb8">
						<volist name="content_label" id="vo">
							<span class="mod-feed-tag b-color{pigcms{$key}">{pigcms{$vo}</span>
						</volist>
					</div>
				</if>
				<p class="cl feed-preview-pic feed_inview">
					<volist name="imglist" id="vo">
						<span class="imgloading">
							<img src="{pigcms{$vo}" alt=""/>
						</span>
					</volist>
				</p>
            </section>
			<div class="secp">
				<span href="javascript:void(0);" class="v_tool opt-item item-praise praise" data-id="{pigcms{$detail.id}" data-href="{pigcms{:U('collectOpt',array('vid'=>$detail['id']))}">
					<i id="praise_{pigcms{$detail.id}" class="hide pr-1 vm iconfont icon-jinlingyingcaiwangtubiao24"></i>
					<span class="heart <if condition="$classify_usercollect_info">heartAnimation</if>" id="heart"></span>
					<span class="x-praise-num" id="praises_{pigcms{$detail.id}">{pigcms{:count($usercollect_info_list)}</span>
				</span>
				<if condition="$detail['shares']">
					<span class="v_tool we_share y" data-id="{pigcms{$detail.id}"><i class="vm iconfont icon-fenxiang1 f18"></i> 分享 {pigcms{$detail.shares}</span>
				</if>
				<span class="v_tool y"><i class="vm iconfont icon-faxian"></i> 浏览量 {pigcms{$detail.views}</span>
			</div>
			<div class="r" id="r_{pigcms{$detail.id}" <if condition="!$usercollect_info_list">style="display:none"</if>></div>
			<div class="cmt-wrap" id="cmt_wrap_{pigcms{$detail.id}" <if condition="!$usercollect_info_list">style="display:none"</if>>
				<div class="like cl">
					<span class="likeuser z likeinview" id="praise_list_{pigcms{$detail.id}">
						<a class="likeinview_prev"><em id="praises_{pigcms{$detail.id}">{pigcms{:count($usercollect_info_list)}</em><br>藏</a>
						<volist name="usercollect_info_list" id="vo">
							<a><img class="uavatar" src="{pigcms{$vo.avatar|default='./static/images/user_avatar.jpg'}" /></a>
						</volist>
					</span>
				</div>
			</div>
		</article>
		<div class="weui-cells border_none">
			<div class="weui-cell weui-cell_access">
				<div class="weui-cell__bd">
					<p class="f15 mb5">联系人：{pigcms{$detail.lxname}</p>
					<p class="f13 color-red">联系我时，请说是在【{pigcms{$config.classify_title}】看到的</p>
				</div>
				<div class="weui-cell__ft weui-grids-nob p0">
					<a class=" color-red" href="tel:{pigcms{$detail.lxtel}" style="display:block;height:40px;line-height:42px;"><i class="iconfont icon-unie607" style="font-size:32px;line-height:42px;"></i></a>
				</div>
			</div>
        </div>
		<div class="weui-cells border_none">
			<a class="weui-cell weui-cell_access" href="{pigcms{:U('member',array('uid'=>$user['uid']))}">
				<div class="weui-cell__hd" style="position: relative;margin-right: 10px;">
					<img src="{pigcms{$user.avatar|default='./static/images/user_avatar.jpg'}" class="v_head"/>
				</div>
				<div class="weui-cell__bd">
					<p class="a da f14 mb5">{pigcms{$user.nickname}</p>
					<p style="font-size:13px;color:#888888;">已发布{pigcms{$user_input_count}条</p>
				</div>
				<div class="weui-cell__ft"></div>
			</a>
        </div>
		<if condition="$detail['redpack_count']">
			<div class="weui-cells__title weui_title border_none">
				<span>已抢<em class="color-red">{pigcms{$detail.redpack_count_get}</em>/{pigcms{$detail.redpack_count}份</span>
				<if condition="$detail['redpack_count_get'] gt 0">
					<a class="y c9" href="{pigcms{:U('hongbao_list',array('id'=>$detail['id']))}">看看大家的手气<i class="f13 iconfont icon-jinrujiantou"></i></a>
				</if>
			</div>
			<div class="weui-cells after_none" id="hong_preview_list">
				<volist name="redpack_list" id="vo">
					<div class="weui-cell cell_hong_list">
						<div class="weui-cell__hd">
							<img src="{pigcms{$vo.avatar|default='./static/images/user_avatar.jpg'}"/>
						</div>
						<div class="weui-cell__bd">
							<p>{pigcms{$vo.nickname}</p>
						</div>
						<div class="weui-cell__ft color-red">{pigcms{$vo.money}元</div>
					</div>
				</volist>
			</div>
		</if>
		<div class="weui-cells__title weui_title border_none"><span>评论</span> <a class="comment y c9" id="comment_{pigcms{$detail.id}" data-id="{pigcms{$detail.id}" data-multi="1">我要评论<i class="f13 iconfont icon-jinrujiantou"></i></a></div>
		<div class="weui-panel weui-panel_access mt0 after_none">
			<div class="weui-panel__bd comment_ul" id="comment_ul_{pigcms{$detail.id}" data-id="{pigcms{$detail.id}">
			</div>
		</div>
		<if condition="$detail['redpack_count'] && ($detail['redpack_count'] - $detail['redpack_count_get'] gt 0) && !$get_redpack">
			<div class="bottom_fix"></div>
			<div class="fix-bottom" style="z-index:1000">
				<a href="javascript:;"  class="weui-btn is-red" onclick="focusHongBao();">抢红包</a>
			</div>
			<div class="hong_res animated zoomIn" id="hong_res">
				<a class="hong_close"><i class="iconfont icon-guanbijiantou f22"></i></a>
				<div class="hong_res_wrap">
					<div class="hong_res_head">
						<div class="hong_res_head_in">
							<img src="{pigcms{$user.avatar|default='./static/images/user_avatar.jpg'}"/>
						</div>
					</div>
					<div class="hong_res_cnt">
						<div class="hong_res_box">
							<p>{pigcms{$user.nickname}</p>
							<p>埋了一个红包</p>
						</div>
						<div class="hong_res_list">
							<div class="send_title"></div>
							<div class="hong_tip">恭喜您获得</div>
							<div class="money_bg">
								<p class="hong_money">
									<i>&yen;</i>
									<span id="hong_size"></span>
									<em>元</em>
								</p>
							</div>
							<a href="{pigcms{:U('My/my_money')}" class="sub_title">红包已自动放入您的余额内</a>
						</div>
					</div>
					<div class="view_oth">
						<a href="{pigcms{:U('hongbao_list',array('id'=>$detail['id']))}">去看看大家的手气</a>
					</div>
					<div class="sub_bg"></div>
				</div>
			</div>
			<div class="hong_res hong_box" id="hong_box">
				<div class="hong_box_main zoomIn animated ">
					<div class="hong_box_title">
						<div class="send_title"></div>
						<div class="hong_star"></div>
						<div class="hong_box_showname">
							<p>总计{pigcms{$detail.redpack_money}元</p>
						</div>
						<div class="hong_btn animated" id="hong_btn" onclick="showHongBox(this);">
							<div class="hong_btn_mask"></div>
							<a href="javascript:;"> </a>
						</div>
					</div>
					<div class="hong_from">
						<p>{pigcms{$user.nickname}</p>
						<p>埋了一个红包</p>
					</div>
					<div class="view_oth">
						<p>领取的红包将存入您的余额</p>
					</div>
					<div class="sub_bg"></div>
				</div>
			</div>
			<!--div class="none">
				<audio id="media" preload="preload"><source src="source/plugin/xigua_hb/static/img/haozi.mp3" type="audio/mpeg" /></audio>
			</div-->
			<script>
				var HAS_QIANG = {pigcms{:intval($get_redpack)}, Qlock =0, hbareaallow=1;
				function showHongBox(obj) {
					$('#wechat-mask').hide();
					if(Qlock || HAS_QIANG){ console.log('false'); return false;}
					Qlock = 1;
					$.ajax({
						//success|0.24||
						type: 'post',
						url: '{pigcms{:U('get_hongbao',array('id'=>$detail['id']))}',
						success: function (data) {
							if(null==data){ tip_common('error|'+ERROR_TIP); return false;}
							if(data.indexOf('success')!==-1){
								HAS_QIANG = 1;
								$('#hong_box').removeClass('show').hide();
								$('#hong_res').show();
								$('#hong_size').html(data.split('|')[1]);
								$('.fix-bottom').hide();
							}else{
								$('#hong_box').removeClass('show').hide();
								tip_common(data);
							}
						},
						error: function () {
						}
					});
				}

				function focusHongBao(){
					if(!HAS_QIANG) {
						$.ajax({
							type: 'post',
							url: '{pigcms{:U('focus_hongbao',array('id'=>$detail['id']))}',
							success: function (data) {
								if(null==data){ tip_common('error|'+ERROR_TIP); return false;}
								if(data.indexOf('success')!==-1){
									$('#hong_box').addClass('show').show();
								}else{
									$('#hong_box').removeClass('show').hide();
									tip_common(data);
								}
							}
						});
					}else{
						$.toast('您已经抢过了', 'error');
					}
				}
			</script>
		</if>

		<div class="footer_fix"></div>
		<div class="view_bottom weui-flex border_top">
			<div class="view_bottom_z">
				<a href="{pigcms{:U('index')}" class="weui-tabbar__item ">
					<span style="display: inline-block;position: relative;">
						<i class="iconfont icon-index weui-tabbar__icon"></i>
					</span>
					<p class="weui-tabbar__label">首页</p>
				</a>
			</div>
			<div class="view_bottom_z">
				<a href="{pigcms{:U('fabu')}" class="weui-tabbar__item">
					<span style="display: inline-block;position: relative;">
						<i class="iconfont icon-fabu weui-tabbar__icon"></i>
					</span>
					<p class="weui-tabbar__label">发布</p>
				</a>
			</div>
            <div class="view_bottom_z">
				<a href="javascript:;" class="weui-tabbar__item we_share" data-id="{pigcms{$detail.id}">
					<span style="display:inline-block;position:relative;">
						<i class="iconfont icon-fenxiang1 weui-tabbar__icon" style="font-size:27px"></i>
					</span>
					<p class="weui-tabbar__label">分享</p>
				</a>
			</div>
			<div class="weui-flex__item view_bottom_y">
				<if condition="$config['is_im']">
					<a href="javascript:;" id="contactBtn" data-self="<if condition="$user['uid'] eq $user_session['uid']">1<else/>0</if>">联系 TA</a>
				<else/>
					<a href="tel:{pigcms{$detail.lxtel}"><i class="iconfont icon-unie607"></i> 拨打电话</a>
				</if>
			</div>
        </div>
	</div>
	<div class="cl footer_fix"></div>
	<if condition="$is_wexin_browser">
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Classifynew/view',array('id'=>$detail['id']))}",
				"tTitle": "{pigcms{$page_title|str_replace=PHP_EOL,'',###}",
				"tContent": "{pigcms{$detail.description|str_replace='<br/>','',###}"
			};
		</script>
		{pigcms{$shareScript}
	</if>
	<if condition="$is_app_browser">
		<script type="text/javascript">
			if("{pigcms{$app_browser_type}" == 'android'){
				window.lifepasslogin.shareLifePass("{pigcms{$page_title}", "{pigcms{$detail.description}", "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", "{pigcms{$config.site_url}{pigcms{:U('Classifynew/view',array('id'=>$detail['id']))}");
			}
		</script>
	</if>
	<include file="footer"/>
	<if condition="$config['is_demo_domain']">
		<script>
			var isShow = hb_getcookie('classifyViewDemoTip');
			if(!isShow){
				demoDomain_tip("内容发布者可以选择置顶信息、刷新信息(<span style='font-size:12px;'>系统后台可单独设置刷新信息是否收费</span>)，发布者还可以设置查看信息抢红包(<span style='font-size:12px;'>抢到的红包会存入平台余额，平台可设置提现最低金额</span>)", "内容页面使用提醒");
				hb_setcookie('classifyViewDemoTip','1',86400*365);
			}
		</script>
	</if>
	<script>
		load_common_list('{pigcms{:U('getReplyList',array('vid'=>$detail['id']))}', 'comment_ul_{pigcms{$detail.id}');
		var cpage = 1;
		$(document).on('click', '#comment_ul_more', function(){
			$('#comment_ul_more').remove();
			cpage ++;
			load_common_list('{pigcms{:U('getReplyList',array('vid'=>$detail['id']))}&page='+cpage, 'comment_ul_{pigcms{$detail.id}');
		});
		$(document).on('click', '#contactBtn', function(){
			if($(this).data('self') == '1'){
				tip_common('error|不能和自己交流');
				return false;
			}
			var act = [];
			act.push({
				text: '电话联系', onClick: function () {
					location.href = 'tel:{pigcms{$detail.lxtel}';
				}
			});
			act.push({
				text: '在线交流', onClick: function () {
					hb_jump("{pigcms{:U('My/go_im',array('hash'=>'group_user'.$user['uid'],'title'=>urlencode($user['nickname'])))}");
				}
			});
			$.actions({
				title: '联系 TA',
				actions: act
			});
		});
		$(function () {
			if(localStorage.getItem('wetip_{pigcms{$detail.id}')){
				$('.we_share').trigger('click');
				localStorage.removeItem('wetip_{pigcms{$detail.id}');
			}
		});
		function jump_download() {
			$.confirm("", function() {
				window.location.href = '';
			}, function() {});
			return false;
		}
	</script>
</body>
</html>

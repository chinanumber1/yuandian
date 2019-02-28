<include file="Public/header" />
<link href="{pigcms{$static_path}index/css/index_8.css" type="text/css" rel="stylesheet" />
<style>
	.tab1_box div ul li .box_text .text_1 .text_1R {
	    line-height: 30px;
	    font-size: 14px;
	    color: #9a9a9a;
	}
</style>
<div class="content w-1200 clearfix">
	<!--广告位-->

	<volist name="portal_index_banner_top" id="vo">
		<div class="mmver w-1200">
			<a href="{pigcms{$vo.url}" target="_blank"><p> <img src="{pigcms{$vo.pic}" alt="{pigcms{$vo.name}" /> </p></a>
		</div>
	</volist>

	<div class="content w-1200 clearfix">
		<!--焦点图切换-->
		<div id="slideBox" class="focus fl">
			<div class="hd">
				<ul><volist name="portal_index_round" id="kvo"><li></li></volist></ul>
			</div>
			<div class="bd">
				<ul>
					<volist name="portal_index_round" id="vo">
						<li>
							<a target="_blank" href="{pigcms{$vo.url}">
								<img src="{pigcms{$vo.pic}" alt="" />
								<div class="bd_title">{pigcms{$vo.name}</div>
							</a>
						</li>
					</volist>
				</ul>
			</div>
			<!-- 下面是前/后按钮代码，如果不需要删除即可 -->
			<a class="prev" href="javascript:void(0)"></a>
			<a class="next" href="javascript:void(0)"></a>
		</div>

		<!--今日热点-->
		<div class="hot fl" id="tab_5">
			<div class="hd">
				<ul class="clearfix tab-hd">
					<li class="selected">
						<a href="javascript:void(0);">最新资讯</a>
					</li>
					<li>
						<a href="javascript:void(0);">新帖速递</a>
					</li>
					<li>
						<a href="javascript:void(0);">精华推荐</a>
					</li>
				</ul>
			</div>
			<div class="hot_bottom tab-cont">
			<if condition="$hot_news">
				<volist name="hot_news" key="k" id="vo">
				<if condition="$k eq 1">
				<h2 style="text-align: center; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
					<a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title}</a>
				</h2>
				<p>
					{pigcms{$vo.desc|mb_substr=0,69}...
					<a href="{pigcms{:U('Article/lists')}" target="_blank">[更多]</a>
				</p>
				<ul>
				<else/>
					<li> <b>[{pigcms{$vo.cat_name}]</b>
						<a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title}</a>
						<span>{pigcms{$vo.dateline|date="m-d",###}</span>
					</li>
				</if>
				</volist>
				</ul>
			</if>
			</div>
			<div class="hot_bottom hot_bottom2 tab-cont" style="display:none;">
				<ul>
					<volist name="newsTieList" id="nvo">
						<li>
							<if condition="$nvo['plate_id'] gt 0"><b>[{pigcms{$nvo.plate_name}]</b></if>
							<a href="{pigcms{:U('Tieba/detail',array('tie_id'=>$nvo['tie_id']))}" target="_blank">{pigcms{$nvo.title}</a>
							<span>{pigcms{$nvo.nickname}</span>
						</li>
					</volist>
				</ul>
			</div>
			<div class="hot_bottom hot_bottom2 tab-cont" style="display:none;">
				<ul>
					<volist name="essenceList" id="evo">
						<li>
							<if condition="$evo['plate_id'] gt 0"><b>[{pigcms{$evo.plate_name}]</b></if>
							<a href="{pigcms{:U('Tieba/detail',array('tie_id'=>$evo['tie_id']))}" target="_blank">{pigcms{$evo.title}</a>
							<span>{pigcms{$evo.nickname}</span>
						</li>
					</volist>
				</ul>
			</div>
		</div>
		<!--精彩活动-->
		<div class="wonde tabs_02 fr">
			<div class="hd">
				<ul class="clearfix tab-hd">
					<li>精彩活动</li>
				</ul>
			</div>
			<div class="ft">
				<a href="{pigcms{:U('Activity/index')}" target="_blank">更多</a>
			</div>

			<div class="wonde_bottom">
				<div class="bd">
					<ul class="picList">
						<volist name="activityList" id="acvo">
							<li class="iskill{pigcms{$acvo.state}">
								<div class="pic">
									<a href="{pigcms{:U('Activity/detail',array('a_id'=>$acvo['a_id']))}" target="_blank">
										<img src="{pigcms{$config.site_url}/upload/portal/{pigcms{$acvo.pic}" />
									</a>
									<s class="s"></s>
								</div>
								<div class="hige">
									<p> <a href="{pigcms{:U('Activity/detail',array('a_id'=>$acvo['a_id']))}" target="_blank">{pigcms{$acvo.title}</a> </p>
									<span>
										<div class="hige_L fl"> 报名 <b>{pigcms{$acvo.already_sign_up}</b> 人 </div>
										<div class="hide_R fr">
											<a href="{pigcms{:U('Activity/detail',array('a_id'=>$acvo['a_id']))}" target="_blank" class="chakan">查看活动</a>
											<a href="{pigcms{:U('Activity/detail',array('a_id'=>$acvo['a_id']))}" class="baoming" target="_blank">我要报名</a>
										</div>
									</span>
								</div>
							</li>
						</volist>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!--tab切换-->
	<div class="tabWrap clearfix">
		<div class="tab w-1200">
			<div class="heTabs clearfix" id="tab1">
				<div class="hd">
					<h3>
					推荐{pigcms{$config.group_alias_name}
						<span class="tit_fu">同城{pigcms{$config.group_alias_name}</span>
					</h3>
					<div class="tabs">
						<ul class="tab-hd clearfix">
							<volist name="index_group_list" id="cvo">
								<li class="item">
									<a href="{pigcms{$cvo.url}" target="_blank">{pigcms{$cvo.cat_name}</a>
								</li>
							</volist>
							<li>
								<a href="{pigcms{$config.site_url}/category/all/all" target="_blank">更多</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="tab1_box">
					<div class="inner">
						<volist name="index_group_list" id="vvo">
							<ul class="tab-cont clearfix">
								<volist name="vvo['group_list']" id="vvolist">
									<li>
										<div class="pic1 scale_img_wrap">
											<a href="{pigcms{$vvolist.url}" target="_blank">
												<img src="{pigcms{$vvolist.list_pic}"></a>
										</div>
										<div class="box_text">
											<a href="{pigcms{$vvolist.url}" target="_blank">{pigcms{$vvolist.group_name}</a>
											<div class="text_1">
												<div class="text_1L fl"> ￥ <b>{pigcms{$vvolist.price}</b> <span>￥{pigcms{$vvolist.old_price}</span> </div>
												<div class="text_1R fr"><span class="tg_chrnum tg_chrnum_561" data-tgid="561" data-num="1">{pigcms{$vvolist['sale_txt']}</span></div>
											</div>
										</div>
									</li>
								</volist>
							</ul>
						</volist>
					</div>
					<div class="po">

						<volist name="portal_index_group_right" id="vo">
							<p class="zt">
								<a href="{pigcms{$vo.url}" target="_blank">
									<img src="{pigcms{$vo.pic}" alt="{pigcms{$vo.name}" />
								</a>
							</p>
						</volist>
					</div>
				</div>

			</div>
		</div>
	</div>


<div class="inforWrap heTabs clearfix" id="tab_ajax_02"></div>


<!--广告位portal_index_banner_one-->
<if condition="is_array($portal_index_banner_one[0])">
	<div class="adverWrap clearfix">
		<div class="mmver w-1200">
			<a href="{pigcms{$portal_index_banner_one[0]['url']}">
				<p>
					<img src="{pigcms{$portal_index_banner_one[0]['pic']}" alt="" width="1200" height="72" title="{pigcms{$portal_index_banner_one[0]['name']}" align="" />
				</p>
			</a>
		</div>
	</div>
</if>


<!--同城快店-->
<div class="store1Wrap clearfix">
	<div class="store1 w-1200">
		<div class="store1_left fl">
			<volist name="portal_index_store" id="vo">
				<a href="{pigcms{$vo.url}" target="_blank"><img src="{pigcms{$vo.pic}" style="vertical-align:top;width: 208px; height: 225px;"></a>
			</volist>
		</div>
		<div class="store1_right fl">
			<ul>
				<volist name="near_shop_list" id="vo">
					<li> 
						<i class="scale_img_wrap"><a href="{pigcms{$vo.url}" target="_blank"><img src="{pigcms{$vo.image}"></a></i>
						<p>{pigcms{$vo.name}</p>
						<p>  <a href="{pigcms{$vo.url}" target="_blank">立即进店</a> </p>
					</li>
				</volist>
				
			</ul>
		</div>
	</div>
</div>

	<if condition="is_array($portal_index_banner_one[1])">
		<div class="mainWrap clearfix">
			<!--广告 S-->
			<div class="mmver clearfix" style="margin:0;">
				<p>
					<a target="_blank" href="{pigcms{$portal_index_banner_one[1]['url']}">
						<img src="{pigcms{$portal_index_banner_one[1]['pic']}" alt="" width="1200" height="72" title="{pigcms{$portal_index_banner_one[1]['name']}" align="" />
					</a>

				</p>
			</div>
			<!--广告 E-->
		</div>
	</if>


<div class="mainWrap clearfix">
	<!--便民电话 S-->
	<div class="BMTellWrap clearfix">
		<div class="BMtellTop clearfix">
			<h3>便民电话</h3>
			<span> <a href="{pigcms{:U('Yellow/index')}" target="_blank"> 更多 <b style="font-size: 16px; margin-left: 3px;font-weight: normal;">››</b> </a> </span>
		</div>


		<style type="text/css"> 
			/*#list1 li:nth-of-type(odd){ background:#00ccff;} 
			#list1 li:nth-of-type(even){ background:#ffcc00;} */
			#list1 li:nth-child(4n+1){ background:#F70909;}
			#list1 li:nth-child(4n+2){background:#88b07e;}
			#list1 li:nth-child(4n+3){background:#4593ff;}
			#list1 li:nth-child(4n+4){background:#b56fae;}
		</style> 

		<ul id="list1" class="bianmin clearfix">
			<if condition="$yellowList">
				<volist name="yellowList" id="voo">
					<li >
						<a style="text-align: center; width: 120px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" href="{pigcms{:U('Yellow/detail',array('yid'=>$voo['id']))}" class="bold0" target="_blank">
							{pigcms{$voo.title}
							<p class="tel">{pigcms{$voo.tel}</p>
						</a>
					</li>
				</volist>
			</if>
		</ul>
		<ul class="fuwu clearfix">
			<if condition="$yellowList">
				<volist name="yellowList" id="vo">
					<li>
						<a href="{pigcms{:U('Yellow/detail',array('yid'=>$vo['id']))}" target="_blank">
							<img src="{pigcms{$vo.logo}" alt="" />
							<span class="tit">{pigcms{$vo.title}</span>
						</a>
					</li>
				</volist>
			</if>
		</ul>
	</div>
	<!--便民电话 E-->
</div>
<div class="mainWrap clearfix">
	<!--link S-->
	<div class="linkWrap clearfix">
		<div class="linkTop clearfix">
			<h3>友情链接</h3>
		</div>
		<div class="friendLink">
			<div class="bd">
				<div class="text">
					<volist name="flink_list" id="vo">
						<a href="{pigcms{$vo.url}" title="{pigcms{$vo.info}" target="_blank">{pigcms{$vo.name}</a>
					</volist>
				</div>

			</div>
		</div>
	</div>
	<!--link E-->
</div>
</div>



<include file="Public/footer" />


<div class="fixed_menu" id="fixed_menu">
	<ul>
		<li class="li_2">
			<a href="{pigcms{:U('Tieba/add')}">发布新帖</a>
		</li>
		<li class="li_3">
			<a href="http://wpa.qq.com/msgrd?v=3&uin={pigcms{$config.site_qq}&site=qq&menu=yes" target="_blank">在线客服</a>
		</li>
		<li class="li_4" id="weixinFixed">
			<a href="javascript:void(0);">微信</a>
			<div class="po" id="weixinFixedInner">
				<img src="{pigcms{$config.wechat_qrcode}" style="width:75px; height:75px; vertical-align:top; display:block;" alt="nihao163k" />
			</div>
		</li>
		<li class="li_6" id="top">
			<a href="#">返回顶部</a>
		</li>
	</ul>
</div>

<script src="{pigcms{$static_path}js/jquery.SuperSlide.2.1.1.js"></script>
<script src="{pigcms{$static_path}js/index8.js"></script>
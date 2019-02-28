<include file="Public/header" />
<link href="{pigcms{$static_path}index/css/index_8.css" type="text/css" rel="stylesheet" />

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
						<a href="javascript:void(0);">今日热点</a>
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
				<h2>
					<a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title|mb_substr=0,24}</a>
				</h2>
				<p>
					{pigcms{$vo.desc|mb_substr=0,69}...
					<a href="{pigcms{:U('Article/lists')}" target="_blank">[更多]</a>
				</p>
				<ul>
				<else/>
					<li> <b>[{pigcms{$vo.cat_name}]</b>
						<a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title}</a>
						<span>{pigcms{$vo.dateline|date="Y-m-d",###}</span>
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
							<a href="{pigcms{:U('Tieba/detail',array('tie_id'=>$evo['tie_id']))}" target="_blank">{pigcms{$evo.plate_name}</a>
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
						团购商城
						<span class="tit_fu">同城一站式电商平台</span>
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
											<a href="{pigcms{$vvolist.url}" target="_blank">{pigcms{$vvolist.s_name}</a>
											<div class="text_1">
												<div class="text_1L fl"> ￥ <b>{pigcms{$vvolist.price}</b> <span>￥{pigcms{$vvolist.old_price}</span> </div>
												<div class="text_1R fr"><span class="tg_chrnum tg_chrnum_561" data-tgid="561" data-num="1">{pigcms{$vvolist['sale_txt']}</span> 件 </div>
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
<div class="adverWrap clearfix">
	<div class="mmver w-1200">
		<a href="{pigcms{$portal_index_banner_one[0]['url']}">
			<p>
				<img src="{pigcms{$portal_index_banner_one[0]['pic']}" alt="" width="1200" height="72" title="{pigcms{$portal_index_banner_one[0]['name']}" align="" />
			</p>
		</a>
	</div>
</div>

<!--同城快店-->
<div class="store1Wrap clearfix">
	<div class="store1 w-1200">
		<div class="store1_left fl">
			<volist name="portal_index_store" id="vo">
				<a href="{pigcms{$vo.url}" target="_blank"><img src="{pigcms{$vo.pic}" style="vertical-align:top;"></a>
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

<div class="mainWrap clearfix">
	<!--口碑店铺推荐E-->
	<!--广告 S-->
	<div class="mmver clearfix">
		<p>
			<a target="_blank" href="{pigcms{$portal_index_banner_one[1]['url']}">
				<img src="{pigcms{$portal_index_banner_one[1]['pic']}" alt="" width="1200" height="72" title="{pigcms{$portal_index_banner_one[1]['name']}" align="" />
			</a>
		</p>
	</div>
	<!--广告 E-->
	<!--最新租售 S-->
	<div class="SalesWrap clearfix">
		<div class="SalesL clearfix">
			<div class="SalesLT">
				<div class="tabs_02" id="tab_4">
					<div class="hd">
						<ul class="clearfix tab-hd">
							<li class="selected">
								<a href="javascript:void(0);">最新出售</a>
							</li>
						</ul>
					</div>
					<div class="ft">
						<a href="{pigcms{$config.site_url}/fang.php?g=Fang&c=Used&a=index" target="_blank">更多</a>
					</div>
					<div class="bd fangchan" id="fc_zushou">
						<ul class="clearfix tab-cont" style="">
							<volist name="houseSellList" id="vo">
								<li class="item shou">
									<div class="sale_ico">售</div>
									<span class="title">
										<a href="{pigcms{$config.site_url}/fang.php?g=Fang&c=Used&a=detail&id={pigcms{$vo.id}" target="_blank">{pigcms{$vo.title}</a>
									</span>
									<span class="price_line">
										<em>{pigcms{:D('Area')->name($vo['area_id'])}</em>
										<em>{pigcms{$vo.area}㎡</em>
										<em>{pigcms{$vo.bedroom}室{pigcms{$vo.hall}厅</em>
										<span class="price" data-price="{pigcms{$vo.price|number_format}"></span>
									</span>
								</li>
							</volist>
						</ul>
					</div>
				</div>

			</div>
			<div class="SalesLT SalesLT0">
				<div class="tabs_02" id="tab_3">
					<div class="hd">
						<ul class="clearfix tab-hd">
							<li class="selected">
								<a href="javascript:void(0);">最新出租</a>
							</li>
						</ul>
					</div>
					<div class="ft">
						<a href="{pigcms{$config.site_url}/fang.php?g=Fang&c=Rent&a=index" target="_blank">更多</a>
					</div>
					<div class="bd fangchan" id="fc_zushou2">
						<ul class="clearfix tab-cont" style="">
							<volist name="houseRentList" id="vo">
								<li class="item zu">
									<div class="sale_ico">租</div>
									<span class="title">
										<a href="{pigcms{$config.site_url}/fang.php?g=Fang&c=Rent&a=detail&id={pigcms{$vo.id}" target="_blank">{pigcms{$vo.title}</a>
									</span>
									<span class="price_line">
										<em>{pigcms{:D('Area')->name($vo['area_id'])}</em>
										<em>{pigcms{$vo.area}㎡</em>
										<em>{pigcms{$vo.bedroom}室{pigcms{$vo.hall}厅</em>
										<span class="price" data-price="{pigcms{$vo.price|number_format}"></span>
									</span>
								</li>
							</volist>
						</ul>
					</div>
				</div>

			</div>
		</div>
		<div class="SalesR clearfix">
			<div class="tabs_02" id="tab_5">
				<div class="hd">
					<ul class="clearfix">
						<li class="selected">
							<a href="javascript:void(0);" id="tabadmenu_10" onMouseOver="setTimeout('Show_TabADSMenu(1,0,2)',200);">推荐经纪人</a>
						</li>
						<li>
							<a href="javascript:void(0);" id="tabadmenu_11" onMouseOver="setTimeout('Show_TabADSMenu(1,1,2)',200);">最新经纪人</a>
						</li>
					</ul>
				</div>
				<!-- <div class="ft">
					<a href="#" target="_blank">更多</a>
				</div> -->
				<div class="bd clearfix">
					<div class="tab_bd" id="tabadcontent_10">
						<ul class="picList">
							<volist name="staffList" id="vo">
								<li>
									<div class="jjrDivL">
										<a href="" target="_blank"><img src="{pigcms{$vo.portrait}" width="84" height="110"></a>
									</div>
									<div class="jjrDivR">
										<p><a href="" target="_blank">{pigcms{$vo.name}</a></p>
										<span>{pigcms{:D('Fc_company')->name($vo['company_id'])}</span>
										<div> <lable>电话：</lable> <b>{pigcms{$vo.phone}</b> </div>
										<div> <lable>QQ：</lable> <b>{pigcms{$vo.qq}</b> </div>
										<!-- <div> <lable>电话：</lable> <b>{pigcms{$vo.phone}</b> </div> -->
										<div class="btn"> <a href="#">进入店铺</a> </div>
									</div>
								</li>
							</volist>
						</ul>
					</div>
					<div class="tab_bd tab-cont" id="tabadcontent_11" style="display:none;">
						<ul class="picList">
							<volist name="staffList" id="vo">
								<li>
									<div class="jjrDivL">
										<a href="" target="_blank"><img src="{pigcms{$vo.portrait}" width="84" height="110"></a>
									</div>
									<div class="jjrDivR">
										<p><a href="" target="_blank">{pigcms{$vo.name}</a></p>
										<span>{pigcms{:D('Fc_company')->name($vo['company_id'])}</span>
										<div> <lable>电话：</lable> <b>{pigcms{$vo.phone}</b> </div>
										<div> <lable>QQ：</lable> <b>{pigcms{$vo.qq}</b> </div>
										<!-- <div> <lable>电话：</lable> <b>{pigcms{$vo.phone}</b> </div> -->
										<div class="btn"> <a href="#">进入店铺</a> </div>
									</div>
								</li>
							</volist>
						</ul>
					</div>
				</div>
			</div>

		</div>
	</div>
	<!--最新租售 E-->

</div>
<div class="mainWrap clearfix">
	<!--广告 S-->
	<div class="mmver clearfix" style="margin:0;">
		<p>
			<a target="_blank" href="{pigcms{$portal_index_banner_one[2]['url']}">
				<img src="{pigcms{$portal_index_banner_one[2]['pic']}" alt="" width="1200" height="72" title="{pigcms{$portal_index_banner_one[2]['name']}" align="" />
			</a>

		</p>
	</div>
	<!--广告 E-->
</div>


<div class="mainWrap clearfix">
	<!--便民电话 S-->
	<div class="BMTellWrap clearfix">
		<div class="BMtellTop clearfix">
			<h3>便民电话</h3>
			<span> <a href="{pigcms{:U('Yellow/index')}" target="_blank"> 更多 <b style="font-size: 16px; margin-left: 3px;font-weight: normal;">››</b> </a> </span>
		</div>
		<ul class="bianmin clearfix">
			<if condition="$hot_companys">
				<volist name="hot_companys" id="voo">
					<li style="background-color:#F70909">
						<a href="{pigcms{:U('Yellow/detail',array('yid'=>$voo['id']))}" class="bold0" target="_blank">
							{pigcms{$voo.title}
							<p class="tel">{pigcms{$voo.tel}</p>
						</a>
					</li>
				</volist>
			</if>
		</ul>
		<ul class="fuwu clearfix">
			<if condition="$hot_companys">
				<volist name="hot_companys" id="vo">
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
		<li class="li_5" id="telBaoming">
			<a href="{pigcms{$config.config_site_url}/topic/app.html">客户端</a>
			<div class="po" id="telBaomingInner">
				<img src="{pigcms{$config.site_url}/tpl/Static/blue/app/images/logo_08.png" style="width:75px; height:75px; vertical-align:top; display:block;" alt="" />
			</div>
		</li>
		<li class="li_6" id="top">
			<a href="#">返回顶部</a>
		</li>
	</ul>
</div>

<script src="{pigcms{$static_path}js/jquery.SuperSlide.2.1.1.js"></script>
<script src="{pigcms{$static_path}js/index8.js"></script>
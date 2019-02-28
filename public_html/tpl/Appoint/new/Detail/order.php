<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>{pigcms{$now_group.appoint_name} | {pigcms{$config.site_name}</title>
	<meta name="keywords" content="{pigcms{$now_group.merchant_name},{pigcms{$now_group.appoint_name},{pigcms{$config.site_name}" />
	<meta name="description" content="{pigcms{$now_group.appoint_content}" />
	<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
	<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/buy-process.css" />
	<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script src="{pigcms{$static_path}js/common.js"></script>	
	<!--[if IE 6]>
	<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
	<script type="text/javascript">
	   /* EXAMPLE */
	   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');

	   /* string argument can be any CSS selector */
	   /* .png_bg example is unnecessary */
	   /* change it to what suits you! */
	</script>
	<script type="text/javascript">DD_belatedPNG.fix('*');</script>
	<style type="text/css"> 
			body{behavior:url("{pigcms{$static_path}css/csshover.htc"); 
			}
			.category_list li:hover .bmbox {
	filter:alpha(opacity=50);
		 
				}
	  .gd_box{	display: none;}
	</style>
	<![endif]-->
	<style>
		#deal-buy-delivery{
			padding-top:30px;
		}
		.form-field label{
			font-size:14px;
			position: absolute;
			left: 0;
		}
		.comm-service span {
			font-size: 22px;
			color: #fc4111;
		}
		.comm-service span em {
			font-style:normal;
			font-size: 15px;
		}
		.con-service {
			display: inline-block;
			vertical-align: middle;
			max-width:68%;
		}
		.con-service-inner {
			margin-left:20px;
		}
		.con-service-inner h3 {
		  font-size: 16px;
		  color: #17bfa9;
		  margin-bottom: 5px;
		  background-color:white;
		  padding:0;
		  border-bottom:0px;
		  margin:0;
		}
		.con-service-inner span{
			  color: #999;
			    font-size: 14px;
		}
		.service-type-select{
			margin-left:20px;
			display: inline-block;
			color:red;
			font-size:14px;
			cursor:pointer;
		}
		.dropdown--small {
		  font-size: 12px;
		  height: 21px;
		  padding: 2px 0;
		  border: 1px solid #d4d4d4;
		  border-color: #b4b4b4 #d4d4d4 #d4d4d4 #b4b4b4;
		  color: #666;
		    margin: 3px 10px 0 0;
			width: 300px;
			height: 30px;
		}
		.service-list li {
  background: #fff;
  width: auto;
  height: inherit;
  position: relative;
  border: 1px solid #ddd;
  padding: 10px;
  margin: 10px 13px;
}
.service-list li.active {
  border: 1px solid #32c8a2;
}
.pay-type {
  display: block;
  width: 100%;
  height: 100%;
}
.service-price {
  font-size: 22px;
  color: #fc4111;
}
.yxc-package span {
  right: 11px;
}
.service-price em {
  font-size: 15px;
  font-style:normal;
}
.service-intro {
  color: #999;
  display: inline-block;
  vertical-align: middle;
  width: 66%;
  margin-left: 7px;
  line-height: 18px;
  font-size: 14px;
}
.service-intro h3 {
  font-size: 16px;
  color: #17bfa9;
  margin-bottom: 5px;
}
.service-list .bt-interior {
  right: 11px;
    top: 45%;
  display:block;
  position:absolute;
}
.yxc-time-con dl {
  text-align: center;
  width: 25%;
  float: left;
}
.yxc-time-con dl dt {
  display: block;
  height: 45px;
  padding-top: 5px;
  border-right: 1px solid #32c8a2;
  border-bottom: 2px solid #32c8a2;
  font-size: 15px;
  background: #32c8a2;
  line-height:22px;
   cursor:pointer;
}
.yxc-time-tab li.active, .yxc-time-con dl .active {
  background: #fff;
  color: #333;
}
.yxc-time-con dl.last{
	line-height:45px;
}
.yxc-time-con dl dt span {
  display: block;
  font-size: 12px;
}
.yxc-time-con dl.last dt span{
	line-height:45px;
}
#service-date{
	width: 538px;
	padding-bottom:20px;
	  overflow: hidden;
	 min-height:200px;
}
.yxc-time-con dl dd {
  height: 42px;
  font-size: 12px;
  line-height:20px;
  border-right: 1px solid #d2d1d6;
  border-bottom: 1px solid #d2d1d6;
  padding-top: 3px;
  color: #29b2a6;
  cursor:pointer;
}
.yxc-time-con dl .disable {
  color: #999;
}
.yxc-paymentMoney {
	margin:10px 0;
  padding: 10px 0;
  font-size:16px;
  height: 26px;
  line-height: 26px;
  padding-left:10px;
}
.yxc-paymentMoney img,.yxc-paymentMoney span{
  vertical-align: middle;
}
.ipt-attr {
  width: 248px;
  height: 24px;
  padding: 5px;
  border: 1px solid #aaa;
  line-height: 24px;
  vertical-align: top;
}
.yxc-time-con.number-3 dl {
  width: 33%;
}
.yxc-time-con.number-3 dl.last{
  width: 34%;
}
.yxc-time-con.number-2 dl {
  width: 50%;
}
.form_error{
	border: 1px solid red;
}
.form-field label em{
	font-style:normal;
	color:red;
}
	</style>
	<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/appoint.js"></script>
	<script>var group_price=0;var finalprice=0;var map_url="{pigcms{:U('Group/Map/frame_select')}";<if condition="$user_session">var is_login=true;<else/>var is_login=false;var login_url="{pigcms{:U('Index/Login/frame_login')}";</if><if condition="$user_session['phone']">var has_phone=true;<else/>var has_phone=false;var phone_url="{pigcms{:U('Index/Login/frame_phone')}";</if></script>
</head>
<body>
 <include file="Public:header_top"/>
	<div class="body pg-buy-process"> 
		<article>
			<div class="menu cf">
				<div class="menu_left hide">
					<div class="menu_left_top"><img src="{pigcms{$static_path}images/o2o1_27.png" /></div>
					<div class="list">
						<ul>
							<volist name="all_category_list" id="vo" key="k">
								<li>
									<div class="li_top cf">
										<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
										<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
									</div>
									<if condition="$vo['cat_count'] gt 1">
										<div class="li_bottom">
											<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
												<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
											</volist>
										</div>
									</if>
								</li>
							</volist>
						</ul>
					</div>
				</div>
				<div class="menu_right cf">
					<div class="menu_right_top">
						<ul>
							<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
								<li class="ctur">
									<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
								</li>
							</pigcms:slider>
						</ul>
					</div>
				</div>
			</div>
		</article>
		<article>
			<div class="sysmsgw common-tip" id="sysmsg-error" style="display:none;"></div>
			<div id="bdw" class="bdw" style="min-height:700px;">
				<div id="bd" class="cf">
					<div id="content">
						<div>
							<div class="buy-process-bar-container">
								<ol class="buy-process-desc steps-desc">
									<li class="step step--current">
										1. 提交订单
									</li>
									<if condition="$now_group['payment_status'] eq 1">
										<li class="step">
											2. 选择支付方式
										</li>
										<li class="step">
											3. 预约成功
										</li>
									<else/>
										<li class="step">
											2. 预约成功
										</li>
									</if>
								</ol>
								<div class="progress">
									<div class="progress-bar" style="width:33.33%"></div>
								</div>
							</div>
						</div>
						<form action="{pigcms{$config.site_url}/appoint/order/{pigcms{$now_group.appoint_id}.html" method="post" id="deal-buy-form" class="common-form J-wwwtracker-form">
							<div class="mainbox cf">
								<div id="deal-buy-delivery" class="blk-item delivery J-deal-buy-delivery">
									<if condition="count($appoint_product) gt 0">
										<div class="form-field form-field--error">
											<label for="address-detail"><em>*</em> 选择服务：</label>
											<div class="comm-service">
												<input type="hidden" name="service_type" id="service_type" value="{pigcms{$appoint_product[0]['id']}"/>
												<span><em>¥</em><span>{pigcms{$appoint_product[0]['price']}</span></span>
												<div class="con-service">
													<div class="con-service-inner">
														<h3>{pigcms{$appoint_product[0]['name']}</h3>
														<span>{pigcms{$appoint_product[0]['content']}</span>
													</div>
												</div>
												<a class="service-type-select">[重新选择]</a>
											</div>
										</div>
									</if>
									<div id="service-type-box" style="display:none;">
										<ul class="delivery-type service-list">
											<volist name="appoint_product" id="vo">
												<li <if condition="$i eq 1">class="active"</if> data-id="{pigcms{$vo['id']}">
													<label class="pay-type" for="pay-type-{pigcms{$vo['id']}">
														<span class="service-price"><em>¥</em><span data-role="payAmount">{pigcms{$vo['price']}</span></span>
														<div class="service-intro">
														  <h3 data-role="title">{pigcms{$vo['name']}</h3>
														  <span data-role="content">{pigcms{$vo['content']}</span>
														</div>
														<span class="bt-interior">
															<input name="pay-type" id="pay-type-{pigcms{$vo['id']}" type="radio" <if condition="$i eq 1">checked="checked"</if>/>
														</span>
													</label>
												</li>
											</volist>
										</ul>
									</div>
									<hr/>
									<div class="form-field">
										<label for="address-detail"><em>*</em> 选择店铺：</label>
										<select name="store_id" id="store_id" class="address-province dropdown--small" style="color:black;">
											<volist name="now_group['store_list']" id="vo">
												<option value="{pigcms{$vo.store_id}">{pigcms{$vo.name}&nbsp;&nbsp;&nbsp;&nbsp;[{pigcms{$vo.area_name} {pigcms{$vo.adress}]</option>
											</volist>
										</select>
									</div>
									<hr/>
									<div class="form-field">
										<input type="hidden" name="service_date" id="service_date" value=""/>
										<input type="hidden" name="service_time" id="service_time" value=""/>
										<label for="address-detail"><em>*</em> 预约时间：</label>
										<input id="serviceJobTime" class="f-text" readonly="readonly" style="width:200px;color:#666;cursor:pointer;font-size:12px;" value="请点击选择预约时间"/>
									</div>
									<hr/>
									<div id="service-date" style="display:none;">
										<div class="yxc-pay-main yxc-payment-bg pad-bot-comm">
											<div class="yxc-time-con number-{pigcms{:count($timeOrder)}">
												<volist name="timeOrder" id="timeOrderInfo">
													<dl <if condition="$i eq count($timeOrder)">class="last"</if>>
														<dt <if condition="$i eq 1">class="active"</if> data-role="date" data-text="<if condition="$key eq date('Y-m-d')" > 今天<elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />明天<elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />后天<else />{pigcms{$key}</if>" data-date="{pigcms{$key}">
																<if condition="$key eq date('Y-m-d')" > 今天
																<elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />明天
																<elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />后天
																<else />
																</if>
															<span>{pigcms{$key}</span>
														</dt>
													</dl>
												</volist>
											</div>
											<div class="yxc-time-con" data-role="timeline">
												<volist name="timeOrder" id="timeOrderInfo">
													<div class="date-{pigcms{$key} timeline" <if condition="$i neq 1">style='display:none'</if> >
													   <volist name="timeOrderInfo" id="vo">
														<dl>
															<dd data-role="item" data-peroid="{pigcms{$vo['time']}" <if condition="$vo['order'] eq 'no' || $vo['order'] eq 'all' ">class="disable"</if>>{pigcms{$vo['time']}<br>
															<if condition="$vo['order'] eq 'no' ">不可预约<elseif condition="$vo['order'] eq 'all' " />已约满<else />可预约</if></dd>
														</dl>
														</volist>
													</div>
												</volist>
											</div>
										</div>
									</div>
									<if condition="$now_group['payment_status'] eq 1">
										<if condition="$now_group['payment_status'] eq 1">
											<div class="yxc-paymentMoney"><img src="{pigcms{$static_path}images/icon_deposit.png" style="width:15px;margin-right:5px;"/><span>&nbsp;预约定金</span><img src="{pigcms{$static_path}images/icon_rmb.png" style="width:8px;margin-left:15px;"/>&nbsp;<span style="font-size:20px;color:#ff8a00;margin-bottom:0;">{pigcms{$now_group.payment_money}</span></div>
											<hr/>
										</if>
									</if>
								</div>
								<if condition="$formData || $now_group['appoint_type']">
									<div class="yxc-attr-list">
										<if condition="$now_group['appoint_type']">
											<div class="form-field" data-name="服务位置">
												<label for="address-detail">&nbsp;服务位置：</label>
												<input type="hidden" name="custom_field[0][name]" value="服务位置"/>
												<input type="hidden" name="custom_field[0][type]" value="2"/>
												<input type="hidden" name="custom_field[0][long]" data-type="long"/>
												<input type="hidden" name="custom_field[0][lat]" data-type="lat"/>
												<input type="hidden" name="custom_field[0][address]" data-type="address"/>
												<p class="cover">
													<input data-role="position" class="ipt-attr" type="text" name="custom_field[0][value]" placeholder="请选择服务位置" readonly="readonly" data-required="required"/>
												</p>
											</div>
										</if>
										<volist name="formData" id="vo">
											<div class="form-field" data-name="{pigcms{$vo.name}">
												<label for="address-detail"><if condition="$vo['iswrite']"><em>*</em></if>&nbsp;{pigcms{$vo.name}：</label>
												<switch name="vo['type']">
													<case value="0">
														<input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
														<p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="text" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
													</case>
													<case value="1">
														<input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
														<p class="cover"><textarea class="ipt-attr" name="custom_field[{pigcms{$i}][value]" data-role="textarea" <if condition="$vo['iswrite']">data-required="required"</if>></textarea></p>
													</case>
													<case value="2">
														<input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][long]" data-type="long"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][lat]" data-type="lat"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][address]" data-type="address"/>
														<p class="cover">
															<input data-role="position" class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" readonly="readonly" style="cursor:pointer;color:#666;" value="请点击选择地图" <if condition="$vo['iswrite']">data-required="required"</if>/>
														</p>
														<p class="cover" style="margin-top:10px;"><input data-role="position-desc" class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value-desc]" data-role="text" style="color:#999;" value="请标注地图后填写详细地址"/></p>
													</case>
													<case value="3">
														<input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
														<p class="cover select">
															<select name="custom_field[{pigcms{$i}][value]" class="dropdown--small" data-role="select" <if condition="$vo['iswrite']">data-required="required"</if>>
																<volist name="vo['use_field']" id="voo">
																	<option value="{pigcms{$voo}">{pigcms{$voo}</option>
																</volist>
															</select>
														</p>
													</case>
													<case value="4">
														<input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
														<p class="cover"><input class="ipt-attr" type="tel" name="custom_field[{pigcms{$i}][value]" data-role="number" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
													</case>
													<case value="5">
														<input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
														<p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="email" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
													</case>
													<case value="6">
														<input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
														<p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="date" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日'})" style="cursor:pointer;color:#666;" value="请点击选择日期" <if condition="$vo['iswrite']">data-required="required"</if> /></p>
													</case>
													<case value="7">
														<input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
														<p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="time" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'HH时mm分'})" style="cursor:pointer;color:#666;" value="请点击选择时间" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
													</case>
													<case value="8">
														<input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
														<p class="cover"><input class="ipt-attr" type="tel" name="custom_field[{pigcms{$i}][value]" data-role="phone" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
													</case>
													<case value="9">
														<input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
														<input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
														<p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="datetime" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日 HH时mm分ss秒'})" style="cursor:pointer;color:#666;" value="请点击选择时间" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
													</case>
												</switch>
											</div>
										</volist>
									</div>
								</if>
								<!--if condition="$user_session['phone']">
									<div class="blk-mobile">
										<p>您绑定的手机号码：<span class="mobile" style="color:#EE3968;">{pigcms{$pigcms_phone}</span></p>
									</div>  
								</if-->
								<div class="form-submit shopping-cart">
									<input type="submit" class="clear-cart btn btn-large btn-buy" id="confirmOrder" value="提交订单" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</article>
	</div>
	<include file="Public:footer"/>
</body>
</html>

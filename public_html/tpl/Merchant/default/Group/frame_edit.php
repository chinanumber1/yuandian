<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/styles.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ba-bbq.min.js"></script>
<title>{pigcms{$config.site_name} - 商家中心</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" href="{pigcms{$static_path}css/bootstrap.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/font-awesome.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-fonts.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace.min.css" id="main-ace-style">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-skins.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-rtl.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/global.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui-timepicker-addon.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ba-bbq.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/ace-extra.min.js"></script>


<script type="text/javascript" src="{pigcms{$static_path}js/bootstrap.min.js"></script>

<!-- page specific plugin scripts -->
<script type="text/javascript" src="{pigcms{$static_path}js/bootbox.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.easypiechart.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.sparkline.min.js"></script>

<!-- ace scripts -->
<script type="text/javascript" src="{pigcms{$static_path}js/ace-elements.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/ace.min.js"></script>

<script type="text/javascript" src="{pigcms{$static_path}js/jquery.yiigridview.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui-i18n.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui-timepicker-addon.min.js"></script>
<style type="text/css">
html{
	background:#fff;
}
.jqstooltip {
	position: absolute;
	left: 0px;
	top: 0px;
	visibility: hidden;
	background: rgb(0, 0, 0) transparent;
	background-color: rgba(0, 0, 0, 0.6);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000,endColorstr=#99000000);
	-ms-filter:"progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
	color: white;
	font: 10px arial, san serif;
	text-align: left;
	white-space: nowrap;
	padding: 5px;
	border: 1px solid white;
	z-index: 10000;
}

.jqsfield {
	color: white;
	font: 10px arial, san serif;
	text-align: left;
}

.statusSwitch, .orderValidSwitch, .unitShowSwitch, .authTypeSwitch {
	display: none;
}

#shopList .shopNameInput, #shopList .tagInput, #shopList .orderPrefixInput
	{
	font-size: 12px;
	color: black;
	display: none;
	width: 100%;
}
</style>
<script type="text/javascript">
	try{ace.settings.check('navbar' , 'fixed')}catch(e){}
	try{ace.settings.check('main-container' , 'fixed')}catch(e){}
	try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>

</head>

<body class="no-skin">
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{$_GET.system_file}?c=Group&a=product">商品列表</a>
			</li>
			<li class="active">编辑商品</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
				#levelcoupon select {width:150px;margin-right: 20px;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">基本信息</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtstore">选择店铺</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtintro">{pigcms{$config.group_alias_name}详情</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtimage">商品图片</a>
							</li>
							<php>if(1==$config['group_store_discount_more']){</php>
							<li>
								<a data-toggle="tab" href="#promotion">商品折扣</a>
							</li>
							<php>}</php>
						  <if condition="!empty($levelarr)">
							<li>
								<a data-toggle="tab" href="#levelcoupon">会员优惠</a>
							</li>
							</if>
							<if condition="!empty($mpackagelist)">
							<li>
								<a data-toggle="tab" href="#relpackages">套餐设置</a>
							</li>
							</if>
							<li>
								<a data-toggle="tab" href="#express_template">运费模板</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtorder">状态设置</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtnum">数量设置</a>
							</li>
							<li>
								<a data-toggle="tab" href="#spread">推广佣金</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtscore">{pigcms{$config['score_name']}设置</a>
							</li>
							<li>
								<a data-toggle="tab" href="#pickinstore">到店自提设置</a>
							</li>
							<if condition="$now_group.open_pin eq 0">
							<li>
								<a data-toggle="tab" href="#opengroupnum">开团人数设置</a>
							</li>
							<else />
							<li>
								<a data-toggle="tab" href="#opengroupnum2">拼团人数设置</a>
							</li>
							</if>
							<if condition="!empty($now_group['trade_info']) OR $now_group['is_appoint_bind']">
							<li>
								<a data-toggle="tab" href="#trade">插件绑定</a>
							</li>
							</if>
							
							<if condition="$config.open_group_percent eq 1">
							<li>
								<a data-toggle="tab" href="#group_percent">团购抽成</a>
							</li>
							</if>

						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="add_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1">商品标题：</label>
									<input class="col-sm-3" maxlength="100" name="name" type="text" value="{pigcms{$now_group.name}" /><span class="form_tips">商品的介绍标题，100字段以内,首页和列表页将显示。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">商品名称：</label>
									<input class="col-sm-3" maxlength="30" name="s_name" type="text" value="{pigcms{$now_group.s_name}" /><span class="form_tips">必填。在订单页显示此名称！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">商品简介：</label>
									<textarea class="col-sm-3" rows="5" name="intro">{pigcms{$now_group.intro}</textarea><span class="form_tips">商品的简短介绍，建议为100字以下。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">关键词：</label>
									<input class="col-sm-3" maxlength="30" name="keywords" type="text" value="{pigcms{$keywords}" id="keywords"/><span class="form_tips">选填。<font color="red">（用空格分隔不同的关键词，最多5个）</font>，用户在微信将按此值搜索！</span> <a href="javascript:;" id="get_key_btn">按商品名称获取</a>
								</div>
								<div class="form-group">
									<label  class="col-sm-1">支持退款：</label>
									<label><input type="radio" name="no_refund" value="0" class="ace" <if condition='$now_group["no_refund"] eq 0'>checked="checked"</if>><span class="lbl" style="z-index: 1">支持</span></label>
									<label style=" margin-left:10px;"><input type="radio" name="no_refund" value="1" class="ace" <if condition='$now_group["no_refund"] eq 1'>checked="checked"</if>><span class="lbl" style="z-index: 1">不支持</span></label>
									<span class="form_tips" style=" color:red"> 选择不支持，则用户付款该团购后不能进行退款操作</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">原价：</label>
									<input class="col-sm-1" maxlength="100" name="old_price" type="text" value="{pigcms{$now_group.old_price|floatval=###}" />
									<if condition="$config.open_extra_price eq 1 ">
										元 + <input class="col-sm-1" maxlength="30" name="extra_pay_old_price" type="text" value="{pigcms{$now_group.extra_pay_old_price}" style="float:none"/>{pigcms{$config.extra_price_alias_name}
										<span class="form_tips">如果填写{pigcms{$config.extra_price_alias_name}字段，商品价格将变为：金额+{pigcms{$config.extra_price_alias_name}数</span>
									</if>
									<span class="form_tips">必填。最多支持1位小数</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">{pigcms{$config.group_alias_name}价：</label>
									<input class="col-sm-1" maxlength="30" name="price" type="text" value="{pigcms{$now_group.price|floatval=###}" />
									<if condition="$config.open_extra_price eq 1 AND $now_group.extra_pay_price gt 0">
										元 + <input class="col-sm-1" maxlength="30" name="extra_pay_price" type="text" value="{pigcms{$now_group.extra_pay_price}" style="float:none"/>{pigcms{$config.extra_price_alias_name}
										<span class="form_tips">如果填写{pigcms{$config.extra_price_alias_name}字段，商品价格将变为：金额+{pigcms{$config.extra_price_alias_name}数</span>
									</if>
									<span class="form_tips">必填。最多支持1位小数</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">微信<if condition="isset($config['pay_weixinapp_appid'])">/app</if>优惠：</label>
									<input class="col-sm-1" maxlength="30" name="wx_cheap" type="text" value="{pigcms{$now_group.wx_cheap|floatval=###}" /><span class="form_tips">单位元，最多支持1位小数，不填则不显示微信优惠！实际购买价=（{pigcms{$config.group_alias_name}价-微信优惠）</span>
								</div>

								<div class="form-group"></div>
								<div class="form-group">
									<label class="col-sm-1">{pigcms{$config.group_alias_name}开始时间：</label>
									<input class="col-sm-2 Wdate" type="text" readonly="readonly" style="height:30px;" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日 HH时mm分ss秒',startDate:'{pigcms{:date('Y-m-d H:i:s',$now_group['begin_time'])}',vel:'begin_time'})" value="{pigcms{:date('Y年m月d日 H时i分s秒',$now_group['begin_time'])}"/>
									<input name="begin_time" id="begin_time" type="hidden" value="{pigcms{:date('Y-m-d H:i:s',$now_group['begin_time'])}"/>
									<span class="form_tips">到了{pigcms{$config.group_alias_name}开始时间，商品才会显示！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">{pigcms{$config.group_alias_name}结束时间：</label>
									<input class="col-sm-2 Wdate" type="text" readonly="readonly" style="height:30px;" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日 HH时mm分ss秒',startDate:'{pigcms{:date('Y-m-d H:i:s',$now_group['end_time'])}',vel:'end_time'})" value="{pigcms{:date('Y年m月d日 H时i分s秒',$now_group['end_time'])}"/>
									<input name="end_time" id="end_time" type="hidden" value="{pigcms{:date('Y-m-d H:i:s',$now_group['end_time'])}"/>
									<span class="form_tips">超过{pigcms{$config.group_alias_name}结束时间，会结束{pigcms{$config.group_alias_name}！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">{pigcms{$config.group_alias_name}券有效期：</label>
									<input class="col-sm-2 Wdate" type="text" readonly="readonly" style="height:30px;" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日 HH时mm分ss秒',startDate:'{pigcms{:date('Y-m-d H:i:s',$now_group['deadline_time'])}',vel:'deadline_time'})" value="{pigcms{:date('Y年m月d日 H时i分s秒',$now_group['deadline_time'])}"/>
									<input name="deadline_time" id="deadline_time" type="hidden" value="{pigcms{:date('Y-m-d H:i:s',$now_group['deadline_time'])}"/><span class="required">*</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">使用时间限制：</label>
									<select name="is_general" class="col-sm-2">
										<option value="0" <if condition="$now_group['is_general'] eq 0">selected="selected"</if>>周末、法定节假日通用</option>
										<option value="1" <if condition="$now_group['is_general'] eq 1">selected="selected"</if>>周末不能使用</option>
										<option value="2" <if condition="$now_group['is_general'] eq 2">selected="selected"</if>>法定节假日不能使用</option>
										<option value="3" <if condition="$now_group['is_general'] eq 3">selected="selected"</if>>周末、法定节假日不能通用</option>
									</select>
								</div>
							</div>
							<div id="txtstore" class="tab-pane">
								<div class="form-group">
									<volist name="store_list" id="vo">
										<div class="radio">
											<label>
												<input class="paycheck ace" type="checkbox" name="store[]" value="{pigcms{$vo.store_id}" id="store_{pigcms{$vo.store_id}" <if condition="in_array($vo['store_id'],$store_arr)">checked="checked"</if>/>
												<span class="lbl"><label for="store_{pigcms{$vo.store_id}">{pigcms{$vo.name} - {pigcms{$vo.area_name}-{pigcms{$vo.adress}</label></span>
											</label>
										</div>
									</volist>
								</div>
							</div>
							<div id="txtintro" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">{pigcms{$config.group_alias_name}类型：</label>
									
									<input type="text" name="tuan_type" disabled="disabled" value="<if condition="$now_group['tuan_type'] eq 0">{pigcms{$config.group_alias_name}券</if><if condition="$now_group['tuan_type'] eq 1">代金券</if><if condition="$now_group['tuan_type'] eq 2">实物</if>" />
									<span class="form_tips">如果是{pigcms{$config.group_alias_name}券或代金券，则会生成券密码；如果是实物，则需要填写快递单号</span>
								</div>
								
								<div class="form-group open_express " <if condition="$now_group['tuan_type'] neq 2">style="display:none"</if>>
									<label class="col-sm-1">开启快递：</label>
									<select name="open_express" class="col-sm-1">
										<option value="0" <if condition="$now_group['open_express'] eq 0">selected</if>>否</option>
										<option value="1" <if condition="$now_group['open_express'] eq 1">selected</if>>是</option>
									</select>
									<span class="form_tips">选否用户前台不出现快递的选项</span>
								</div>
								
								<if condition="!($now_group['sale_count'] gt 0) OR true">
								<div class="form-group">
									<label class="col-sm-1">选择分类：</label>
									<select id="choose_catfid" name="cat_fid" class="col-sm-1" style="margin-right:10px;">
										<volist name="f_category_list" id="vo">
											<option value="{pigcms{$vo.cat_id}" <if condition="$now_group['cat_fid'] eq $vo['cat_id']">selected="selected"</if>>{pigcms{$vo.cat_name}</option>
										</volist>
									</select>
									<select id="choose_catid" name="cat_id" class="col-sm-1" style="margin-right:10px;">
										<volist name="s_category_list" id="vo">
											<option value="{pigcms{$vo.cat_id}" <if condition="$now_group['cat_id'] eq $vo['cat_id']">selected="selected"</if>>{pigcms{$vo.cat_name}</option>
										</volist>
									</select>
								</div>

								<div style="border:1px solid #c5d0dc;padding-left:22px;margin-bottom:10px; <if condition='!$custom_html'>display:none;</if>" id="custom_html_tips">
									<div class="form-group" style="margin-top:10px;color:red;">以下为主分类设定的特殊字段，不同分类字段不同，请选择。</div>
									<div id="custom_html"><if condition="$custom_html">{pigcms{$custom_html}</if></div>
								</div>

								<div style="border:1px solid #c5d0dc;padding-left:22px;margin-bottom:10px; <if condition='!$cue_html'>display:none;</if>" id="cue_html_tips">
									<div class="form-group" style="margin-top:30px;color:red;">以下为主分类设定的 购买须知填写项，请填写。</div>
									<div id="cue_html"><if condition="$cue_html">{pigcms{$cue_html}</if></div>
								</div>
								<else />
								  	<div style="border:1px solid #c5d0dc;padding-left:22px;margin-bottom:10px;">
									<div class="form-group" style="margin-top:10px;color:red;">此团购已经有人下单了，不可再更改它的分类及其对应内容！</div>
									<input type="hidden" name="noedittype" value="yes">
								</div>
								</if>
								<div class="form-group" style="margin-bottom:0px;margin-top:20px;"><label class="col-sm-1">&nbsp;</label><a href="javascript:;" id="editor_plan_btn">插入套餐表格</a></div>
								<div class="form-group" >
									<label class="col-sm-1">本单详情：<br/><span style="font-size:12px;color:#999;">必填</span></label>
									<textarea name="content" id="content" style="width:702px;">{pigcms{$now_group.content}</textarea>
								</div>
							</div>
							<div id="txtimage" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">上传图片</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a>
									<span class="form_tips">第一张将作为列表页图片展示！最多上传5个图片！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											<volist name="now_group['pic_arr']" id="vo">
												<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
											</volist>
										</ul>
									</div>
								</div>
							</div>
							<php>if(1==$config['group_store_discount_more']){</php>
							<div id="promotion" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">商品折扣</label>
									<if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">
										<strong style="color:red">只能由商家自己设置</strong>
											<input class="col-sm-1" size="10" maxlength="10" name="discount" id= "Config_mean_full_money" type="text" value="{pigcms{$now_group.discount}" disabled /><strong style="color:red">0~10之间的数字，支持一位小数！8代表8折，8.5代表85折，0与10代表无折扣</strong>
									<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />
									    <strong style="color:red">只能由系统设置</strong>
										<input class="col-sm-1" size="10" maxlength="10" name="discount" id="Config_mean_full_money" type="text" value="{pigcms{$now_group.discount}" disabled/><strong style="color:red">0~10之间的数字，支持一位小数！8代表8折，8.5代表85折，0与10代表无折扣</strong>
									<else />
									<input class="col-sm-1" size="10" maxlength="10" name="discount" id="Config_mean_full_money" type="text" value="{pigcms{$now_group.discount}" /><strong style="color:red">0~10之间的数字，支持一位小数！8代表8折，8.5代表85折，0与10代表无折扣</strong>
									</if>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>优惠方式</label></label>
									<if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">
										<strong style="color:red">只能由商家自己设置</strong>
									<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />
									    <strong style="color:red">只能由系统设置</strong>
									</if>
									<span><label><input id='discount_type0' name="vip_discount_type" <if condition="$now_group['vip_discount_type'] eq 2 ">checked="checked"</if> value="2" type="radio" <if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if>></label>&nbsp;<span>折上折</span>&nbsp;</span>
									<span><label><input id='discount_type1' name="vip_discount_type" <if condition="$now_group['vip_discount_type'] eq 1 ">checked="checked"</if> value="1" type="radio" <if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if>></label>&nbsp;<span>折扣最优</span></span>
									<strong style="color:red">折上折的意思是如果这个用户是有平台VIP等级，平台VIP等级有折扣优惠。那么这个用户的优惠计算方式是先用店铺的优惠进行打折后，再用VIP折扣进去打折；<br/>
									折扣最优是指：购买产品的总价用店铺优惠打折后的价格与总价跟VIP优惠打折后的价格进行比较，取最小值的优惠方式。
									</strong>
								</div>
								<div style="clear:both;"></div>
							</div>
									<php>}</php>
							<if condition="!empty($levelarr)">
							<div id="levelcoupon" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1" style="color:red;width:95%;">说明：必须设置一个会员等级优惠类型和优惠类型对应的数值，我们将结合优惠类型和所填的数值来计算该商品会员等级的优惠的幅度！</label>
								</div>
							    <volist name="levelarr" id="vv">
								  <div class="form-group" >
								    <input  name="leveloff[{pigcms{$vv['level']}][lid]" type="hidden" value="{pigcms{$vv['id']}"/>
								    <input  name="leveloff[{pigcms{$vv['level']}][lname]" type="hidden" value="{pigcms{$vv['lname']}"/>
									<label class="col-sm-1">{pigcms{$vv['lname']}：</label>
									优惠类型：&nbsp;
									<select name="leveloff[{pigcms{$vv['level']}][type]" <php>if(1==$config['group_store_discount_more']){</php><if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if>	<php>}</php>>
										<option value="0">无优惠</option>
										<option value="1" <if condition="$vv['type'] eq 1">selected="selected"</if>>百分比（%）</option>
										<option value="2" <if condition="$vv['type'] eq 2">selected="selected"</if>>立减</option>
									</select>
									<input name="leveloff[{pigcms{$vv['level']}][vv]" type="text" value="{pigcms{$vv['vv']}" placeholder="请填写一个优惠值数字" onkeyup="value=value.replace(/[^1234567890]+/g,'')" <php>if(1==$config['group_store_discount_more']){</php><if condition="!empty($_SESSION['system']) AND $config['discount_controler'] eq 0">disabled<elseif condition="empty($_SESSION['system']) AND $config['discount_controler'] eq 1" />disabled</if><php>}</php>>
								</div>
								</volist>
							</div>
							</if>
							<div id="relpackages" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1" style="color:red;width:95%;">说明：一个团购商品只能参与一个套餐！</label>
								</div>
							  <div class="form-group">
							    <if condition="!empty($mpackagelist)">
									<label class="col-sm-1">本{pigcms{$config.group_alias_name}套餐标签：</label>
									<input class="col-sm-2" maxlength="30" name="tagname" type="text" value="{pigcms{$now_group['tagname']}" />
									<label class="col-sm-1" style="margin-left:20px;">选择加入套餐：</label>
									<select name="packageid" style="width:300px;">
									<option value="0">不加入任何套餐</option>
									<volist name="mpackagelist" id="vo">
									  <option value="{pigcms{$vo['id']}" <if condition="$vo['id'] eq $now_group['packageid']">selected="selected"</if> >{pigcms{$vo['title']}</option>
									</volist>
									</select>
									<else />
									<label class="col-sm-1" style="color:red;width:95%;">商家还没有套餐可选！</label>
									</if>
									<span class="form_tips"></span>
							   </div>
							</div>
							
							<div id="express_template" class="tab-pane">
							
									<if condition="!empty($express_list)">
							  <div class="form-group">
									
									<label class="col-sm-1" >选择运费模板：</label>
									<select name="express_template_id" style="width:300px;">
									<option value="0">不选择运费模板</option>
									<volist name="express_list" id="vo">
									  <option value="{pigcms{$vo['id']}" <if condition="$vo['id'] eq $now_group['express_template_id']">selected="selected"</if> >{pigcms{$vo['name']}</option>
									</volist>
									</select>
								</div >	
								  <div class="form-group">
									<label class="col-sm-1">其他区域运费：</label>
									<input class="col-sm-2" maxlength="30" name="express_fee" type="text" value="{pigcms{$now_group['express_fee']}" />
									</div>
									<else />
									<div class="form-group">
										<label class="col-sm-1">其他区域运费：</label>
										<input class="col-sm-2" maxlength="30" name="express_fee" type="text" value="{pigcms{$now_group['express_fee']}" />
										<input name="express_template_id" type="hidden" value="0" />
									</div>
									</if>
									<span class="form_tips"></span>
							 
							</div>
							<div id="txtnum" class="tab-pane">
								<div class="form-group" style="display:none;">
									<label class="col-sm-1">成功{pigcms{$config.group_alias_name}人数要求：</label>
									<input class="col-sm-1" maxlength="20" name="success_num" type="text" value="{pigcms{$now_group.success_num}" /><span class="form_tips">最少需要多少人购买才算{pigcms{$config.group_alias_name}成功。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">虚拟已购买人数：</label>
									<input class="col-sm-1" maxlength="20" name="virtual_num" type="text" value="{pigcms{$now_group.virtual_num}" /><span class="form_tips">前台购买人数会显示[ 虚拟购买人数+真实购买人数 ]</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">商品总数量：</label>
									<input class="col-sm-1" maxlength="20" name="count_num" type="num" value="{pigcms{$now_group.count_num}" /><span class="form_tips">0表示不限制，否则产品会出现“已卖光”状态</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">ID最多购买数量：</label>
									<input class="col-sm-1" maxlength="20" name="once_max" type="num" value="{pigcms{$now_group.once_max}" /><span class="form_tips">一个ID最多购买数量，0表示不限制</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">ID每天最多购买数量：</label>
									<input class="col-sm-1" maxlength="20" name="once_max_day" type="number" value="{pigcms{$now_group.once_max_day}" /><span class="form_tips">一个ID每天最多购买数量，0表示不限制。也会受“ID最多购买数量”配置项限制</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">一次最少购买数量：</label>
									<input class="col-sm-1" maxlength="20" name="once_min" type="num" value="{pigcms{$now_group.once_min}" /><span class="form_tips">购买数量低于此设定的不允许参团</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">库存减少的方式：</label>
									<label><input type="radio" name="stock_reduce_method" value="0" <if condition="$now_group['stock_reduce_method'] eq 0">checked="checked"</if>>&nbsp;&nbsp;支付成功后减库存</label>&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="stock_reduce_method" value="1" <if condition="$now_group['stock_reduce_method'] eq 1">checked="checked"</if>>&nbsp;&nbsp;下单成功后减库存</label>&nbsp;&nbsp;&nbsp;
								</div>
								<div class="alert alert-block alert-success">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>
									<p>
										1.支付成功后减库存：<strong>可能会出现售出的数量大于商品总数</strong><br/>
										2.下单成功后减库存：<strong>可能会出现大量下单但是没有支付是库存已经没有了，但是如果20分钟后还是没有买单的话系统自动回滚库存</strong>
									</p>
								</div>
							</div>

							<div id="spread" class="tab-pane"  >
								<div class="form-group">
									<label class="col-sm-1">第一级用户分享佣金比例：</label>
									<input class="col-sm-1" maxlength="20" name="spread_rate" type="text" value="{pigcms{$now_group.spread_rate|floatval}" /><span class="form_tips">用户分享链接购买商品获得佣金比例（百分比 0-100）微信中分享有效！-1 代表跳过</span>
								</div>
								<if condition="$config.open_extra_price eq 0">
								<div class="form-group">
									<label class="col-sm-1">第二级用户分享佣金比例：</label>
									<input class="col-sm-1" maxlength="20" name="sub_spread_rate" type="text" value="{pigcms{$now_group.sub_spread_rate|floatval}" /><span class="form_tips">子用户（分享用户再次分享的用户）分享链接购买商品获得佣金比例（百分比 0-100）微信中分享有效！如果此值用户分享的佣金值和大于100，则取100减去用户分享的佣金值。-1 代表跳过</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">第三级用户分享佣金比例：</label>
									<input class="col-sm-1" maxlength="20" name="third_spread_rate" type="text" value="{pigcms{$now_group.third_spread_rate|floatval}" /><span class="form_tips">子用户（分享用户再次分享的用户）分享链接购买商品获得佣金比例（百分比 0-100）微信中分享有效！如果此值用户分享的佣金值和大于100，则取100减去用户分享的佣金值。-1 代表跳过</span>
								</div>
								</if>
							</div>
							<div id="txtscore" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">使用{pigcms{$config['score_name']}：</label>
									<input  name="score_use" type="radio" value="1" <if condition="$now_group.score_use eq 1">checked="checked"</if>/>开启<input  name="score_use" type="radio" value="0" <if condition="$now_group.score_use eq 0">checked="checked"</if>/>关闭<span class="form_tips">默认开启，关闭则表示该团购不能使用{pigcms{$config['score_name']}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">{pigcms{$config['score_name']}限制数：</label>
									<input class="col-sm-1" maxlength="30" name="group_max_score_use" type="text" value="{pigcms{$now_group.group_max_score_use|floatval=###}" /><span class="form_tips">必填，0为平台默认的最大使用数 ，大于0的整数代表该团购自定义可使用的{pigcms{$config['score_name']}数</span>
								</div>
							</div>
							<div id="txtorder" class="tab-pane">
								<if condition="$config.open_group_default_sort eq 1">
								<div class="form-group">
									<label class="col-sm-1">手动排序：</label>
									<input class="col-sm-1" maxlength="20" name="sort" type="text" value="{pigcms{$now_group.sort}" /><span class="form_tips">数值越高，则在用户前台的智能排序列表展示越靠前，反之，靠后展示。</span>
								</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1">首页排序：</label>
									<input class="col-sm-1" maxlength="20" name="index_sort" type="text" value="{pigcms{$now_group.index_sort}" /><span class="form_tips">数值越高，首页排序越前。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">{pigcms{$config.group_alias_name}状态：</label>
									<select name="status" class="col-sm-1">
										<option value="1" <if condition="$now_group['status'] eq 1">selected="selected"</if>>开启</option>
										<option value="0" <if condition="$now_group['status'] eq 0">selected="selected"</if>>关闭</option>
									</select>
									<span class="form_tips">为了方便用户能查找到以前的订单，{pigcms{$config.group_alias_name}无法删除！</span>
								</div>
								<if condition="$now_group['type'] neq 1">
									<div class="form-group">
										<label class="col-sm-1">运行状态：</label>
										<select name="type" class="col-sm-1">
											<option value="1">更改</option>
											<option value="{pigcms{$now_group.type}" selected="selected">不更改</option>
										</select>
										<span class="form_tips">团够运行状态不正常,是否要更改到正常状态</span>
									</div>
								</if>
							</div>
							<div id="pickinstore" class="tab-pane">
								<div class="form-group">
									<label  class="col-sm-1">开启到店自提：</label>
										<label><input type="radio" name="pick_in_store" value="1" class="ace" <if condition='$now_group["pick_in_store"] eq 1'>checked="checked"</if>><span class="lbl" style="z-index: 1">开启</span></label>
										<label style=" margin-left:10px;"><input type="radio" name="pick_in_store" value="0" class="ace" <if condition='$now_group["pick_in_store"] eq 0'>checked="checked"</if>><span class="lbl" style="z-index: 1">关闭</span></label>
										<span class="form_tips" style=" color:red">没有设置其他到店自提地点，默认自提点为团购的店铺</span>
								</div>
							</div>
							<div id="opengroupnum" class="tab-pane">
								<div class="form-group">
									<label  class="col-sm-1">开团人数设置：</label>
									<input class="col-sm-1" maxlength="20" name="open_num" type="num" value="{pigcms{$now_group.open_num}" /><span class="form_tips">开团人数，0表示不限制</span>
								</div>
								<div class="form-group">
									<label  class="col-sm-1">立即开团人数设置：</label>
									<input class="col-sm-1" maxlength="20" name="open_now_num" type="num" value="{pigcms{$now_group.open_now_num}" /><span class="form_tips">立即成团人数，方便商家临时调整人数，值为0时对开团人数限制没有影响</span>
								</div>
								<div class="form-group">
									<label  class="col-sm-1">组团购人数设置：</label>
									<input class="col-sm-1" maxlength="20" name="group_share_num" type="num" value="{pigcms{$now_group.group_share_num}" /><span class="form_tips">组团购人数设置，组团购需要用户分享连接，0为不开启</span>
								</div>
								<div class="form-group">
									<label  class="col-sm-1">组团购退款手续费：</label>
									<input class="col-sm-1" maxlength="20" name="group_refund_fee" type="num" value="{pigcms{$now_group.group_refund_fee}" /><span class="form_tips">手续费将按单价百分比（0-100）收取</span>
								</div>
								<div class="form-group">
									<span class="form_tips">(设置组团购，开团人数则无效)</span>
								</div>
							</div>
							<div id="opengroupnum2" class="tab-pane">
								<div class="form-group">
									<label  class="col-sm-1">拼团人数设置</label>
									<input class="col-sm-1" maxlength="20" name="pin_num" type="num" value="{pigcms{$now_group.pin_num}" /><span class="form_tips">拼团人数设置，0表示不设置拼团</span>
								</div>
								<div class="form-group">
									<label  class="col-sm-1">团长优惠比例</label>
									<input class="col-sm-1" maxlength="20" name="start_discount" type="num" value="{pigcms{$now_group.start_discount}" /><span class="form_tips">团长优惠 0 为免费，100 为原价</span>
								</div>
								<div class="form-group">
									<label  class="col-sm-1">成团后退款手续费比例：</label>
									<input class="col-sm-1" maxlength="20" name="group_refund_fee" type="num" value="{pigcms{$now_group.group_refund_fee}" /><span class="form_tips">手续费将按单价百分比（0-100）收取,设置为100（成团前所有参团人也不可退）则不允许退款</span>
								</div>
								<div class="form-group">
									<label  class="col-sm-1">成团有效期</label>
									<input class="col-sm-1" maxlength="20" name="pin_effective_time" type="num" value="{pigcms{$now_group.pin_effective_time}" /><span class="form_tips">有效期内未成团团长不能取消订单，单位/时</span>
								</div>
								<div class="form-group">
									<span class="form_tips">(拼团人数设置后，团购按拼团的逻辑走，如果当前团购有组团购在进行则不能设置)</span>
								</div>
							</div>


							<if condition="!empty($now_group['trade_info'])  OR $now_group['is_appoint_bind']">
								<div id="trade" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">绑定插件：</label>
									<select name="bind_trade" id="bind_trade" class="col-sm-2">
										<option value="0">不绑定</option>

											<option value="1" <php>if('appoint'==$now_group['trade_type'] || $now_group['is_appoint_bind']){</php>selected="selected"<php>}</php>>预约模块</option>

										<option value="2" <php>if('hotel'==$now_group['trade_type']){</php>selected="selected"<php>}</php>>酒店模块</option>
									</select>
								</div>

								<div class="trade_box" id="trade_box_1" <if condition="!$now_group['is_appoint_bind']">style="display:none"</if>>
									<div class="form-group">
										<label class="col-sm-1">选择预约：</label>
										<select class="col-sm-1" name="appoint_id">
											<option value="0">请选择</option>
											<volist name="appoint_list" id="vo">
											<option value="{pigcms{$vo.appoint_id}" <php>if($vo['appoint_id']==$now_group['appoint_id']&&$now_group['trade_type']=='appoint'){</php>selected="selected"<php>}</php>>{pigcms{$vo.appoint_name}</option>
											</volist>
										</select>
									</div>
								</div>



								<div class="trade_box" id="trade_box_2" <if condition="!$now_group['trade_info'] OR $now_group['trade_type'] eq '' OR  $now_group['trade_type'] eq 'appoint'">style="display:none"</if> >
									<div class="form-group">
										<label class="col-sm-8">目前酒店行业插件只适用于单门店，用户下单时会自动在团购绑定的第一个店铺中下单，建议只绑定一个店铺。</label>
									</div>
									<div class="form-group">
										<div style="padding-left:12px;margin-bottom:10px;">绑定分类：</div>
										<volist name="trade_hotel_category_list" id="vo">
											<div class="radio">
												<label>
													<input class="paycheck ace" type="checkbox" name="trade_hotel_cat_id[]" <if condition="in_array($vo['cat_id'],$now_group['trade_info']) AND $now_group['trade_type'] eq 'hotel'">checked="checked"</if> value="{pigcms{$vo.cat_id}" id="trade_hotel_category_{pigcms{$vo.cat_id}"/>
													<span class="lbl"><label for="trade_hotel_category_{pigcms{$vo.cat_id}">{pigcms{$vo.cat_name}</label></span>
												</label>
											</div>
										</volist>
									</div>
								</div>
							

							</div>
								</if>
							<if condition="$config.open_group_percent eq 1">
								<div id="group_percent" class="tab-pane">
									<div class="form-group">
										<label  class="col-sm-1">抽成比例设置</label>
										
										<label style="float:left">
											<input type="radio" name="open_group_percent" value="1" class="ace" <if condition='$now_group["percent"] egt 0'>checked="checked"</if>>
											<span class="lbl" style="z-index: 1">开启</span>
										</label>
										<label style="float:left; margin-left:10px;">
											<input type="radio" name="open_group_percent" value="0" class="ace" <if condition='$now_group["percent"] lt 0'>checked="checked"</if>>
											<span class="lbl" style="z-index: 1">关闭</span>
										</label>
									
										<input class="col-sm-1 percent" maxlength="20" name="percent" type="num" value="{pigcms{$now_group.percent|floatval}" /><span class="form_tips percent">抽成比例设置，30表示抽成30%，0表示不抽成，关闭则沿用上一级抽成设置， 不用填%号
</span>
										
									</div>
									
								
								</div>
							<else />
							<input name="percent" type="hidden" value="0" />
							</if>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" id="save_btn">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
input.ke-input-text {
background-color: #FFFFFF;
background-color: #FFFFFF!important;
font-family: "sans serif",tahoma,verdana,helvetica;
font-size: 12px;
line-height: 24px;
height: 24px;
padding: 2px 4px;
border-color: #848484 #E0E0E0 #E0E0E0 #848484;
border-style: solid;
border-width: 1px;
display: -moz-inline-stack;
display: inline-block;
vertical-align: middle;
zoom: 1;
}
.form-group>label{font-size:12px;line-height:24px;}
#upload_pic_box{margin-top:20px;height:150px;}
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}
.percent{
	display:none;
}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<script type="text/javascript">
var diyVideo = "{pigcms{:U('Article/diyVideo')}";
function deleteImage(path,obj){
	$.post("{pigcms{:U('Group/ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
KindEditor.ready(function(K) {
	var content_editor = K.create("#content",{
		width:'702px',
		height:'260px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		autoHeightMode : true,
		afterCreate : function() {
			this.loadPlugin('autoheight');
		},
		items : [
			'source', 'fullscreen', '|', 'fontname', 'fontsize', '|','plainpaste', 'wordpaste', 'clearhtml', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'multiimage', 'link', 'table','diyVideo'
		],
		emoticonsPath : './static/emoticons/',
		uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=group/content",
		cssPath : "{pigcms{$static_path}css/group_editor.css"
	});

	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 5){
			alert('最多上传5个图片！');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('Group/ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="pic[]" value="'+title+'"/><br/><a href="#" onclick="deleteImage(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});

	$('#choose_catfid').change(function(){
		$.getJSON("{pigcms{:U('Group/ajax_get_category')}",{cat_fid:$(this).val()},function(result){
			if(result.error == 0){
				var catid_html = '';
				$.each(result.cat_list,function(i,item){
					catid_html += '<option value="'+item.cat_id+'">'+item.cat_name+'</option>';
				});
				$('#choose_catid').html(catid_html);
				if(result.custom_html == ''){
					$('#custom_html_tips').hide();
				}else{
					$('#custom_html_tips').show();
				}
				if(result.cue_html == ''){
					$('#cue_html_tips').hide();
				}else{
					$('#cue_html_tips').show();
				}
				$('#custom_html').html(result.custom_html);
				$('#cue_html').html(result.cue_html);
			}else{
				$('#choose_catid').html('<option value="0">请选择其他分类</option>');
				alert(result.msg);
			}
		});
	});
	$('#add_form').submit(function(){
		content_editor.sync();
		$('#save_btn').prop('disabled',true);
		$.post("{pigcms{:U('Group/frame_edit',array('group_id'=>$now_group['group_id']))}",$('#add_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href="{pigcms{$this->config['site_url']}/admin.php?c=Group&a=product";
			}else{
				alert(result.info);
				
			}
			$('#save_btn').prop('disabled',false);
		})
		return false;
	});

	$('#bind_trade').change(function(){
		$('.trade_box').hide();
		$('#trade_box_'+$(this).val()).show();
	});


	$('#editor_plan_btn').click(function(){
		var dialog = K.dialog({
				width : 200,
				title : '输入欲插入表格行数',
				body : '<div style="margin:10px;"><input id="edit_plan_input" style="width:100%;"/></div>',
				closeBtn : {
						name : '关闭',
						click : function(e) {
							dialog.remove();
						}
				},
				yesBtn : {
						name : '确定',
						click : function(e){
							var value = $('#edit_plan_input').val();
							if(!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value)){
								alert('请输入数字！');
								return false;
							}
							value = parseInt(value);
							var html = '<table class="deal-menu">';
							html += '<tr><th class="name" colspan="2">套餐内容</th><th class="price">单价</th><th class="amount">数量/规格</th><th class="subtotal">小计</th></tr>';
							for(var i=0;i<value;i++){
								html += '<tr><td class="name" colspan="2">内容'+(i+1)+'</td><td class="price">¥</td><td class="amount">1份</td><td class="subtotal">¥</td></tr>';
							}
							html += '</table>';
							html += '<p class="deal-menu-summary">价值: <span class="inline-block worth">¥</span>{pigcms{$config.group_alias_name}价： <span class="inline-block worth price">¥</span></p><br/><br/>介绍...';
							content_editor.appendHtml(html);

							dialog.remove();
						}
				},
				noBtn : {
						name : '取消',
						click : function(e) {
							dialog.remove();
						}
				}
		});
	});

	$('#get_key_btn').click(function(){
		var s_name = $('input[name="s_name"]');
		s_name.val($.trim(s_name.val()));
		$('#keywords').val($.trim($('#keywords').val()));
		if(s_name.val().length == 0){
			alert('请先填写商品名称！');
			s_name.focus();
		}else if($('#keywords').val().length != 0){
			alert('请先删除您填写的关键词！');
			$('#keywords').focus();
		}else{
			$.get("{pigcms{:U('Index/Scws/ajax_getKeywords')}",{title:s_name.val()},function(result){
				result = $.parseJSON(result);
				if(result.num == 0){
					alert('您的商品名称没有提取到关键词，请手动填写关键词！');
					$('#keywords').focus();
				}else{
					$('#keywords').val(result.list.join(' ')).focus();
				}
			});
		}
	});
	
	if($('input[name="percent"]').val()>=0){
		$('.percent').show();
	}
	var tmp_percent = $('input[name="percent"]').val();
	
	$('input[name="open_group_percent"]').click(function(){
		var sub = $(this);
		if(sub.val()==1){
			if($('input[name="percent"]').val()<0){
				
				$('input[name="percent"]').val(tmp_percent<0?0:tmp_percent);
			}
			$('.percent').show();
		}else{
			$('.percent').hide();
			$('input[name="percent"]').val(-1);
		}
	});
});

</script>
<include file="Public:footer"/>
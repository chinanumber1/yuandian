<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-credit-card"></i>
                <a href="{pigcms{:U('Card_new/index')}">会员卡</a>
            </li>
        </ul>
    </div>
	<div class="page-content form-horizontal ">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
					<form class="form" method="post" action="" target="_top" enctype="multipart/form-data">
						<label for="tab1" class="select_tab select" style="cursor:pointer;">基本信息</label>
						<if condition="$config['coupon_wx_sync']">
							<label for="tab2" class="select_tab" style="margin-left:-4px;cursor:pointer;">微信会员卡</label>
						</if>
						<div class="tab-content card_new" id="tab1" >
							<!--<div class="headings gengduoxian">基本信息<span class="note-inf">带<a style="color:red;">*</a>为必填项</span></div>-->
							<div class="form-group">
								<label class="tiplabel"><label>启用会员卡：</label></label>
								<label class="radiolabel first"><span><label><input name="status" value="1" type="radio" <if condition="$card.status eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="status" value="0" type="radio" <if condition="$card.status eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>会员卡背景图：</label></label>
								<select name="bg" id="sys_card_bg" class="pt" style="width:210px;">
									<?php
									for ($i = 4; $i <= 20; $i++) {
										$i = $i < 10 ? '0' . $i : $i;
										$str = './static/images/card/card_bg' . $i . '.png';
										if ($card['bg'] == $str) {
											echo $str = '<option value="' . $str . '" selected="selected" >' . $i . '</option>';
										} else {
											echo $str = '<option value="' . $str . '">' . $i . '</option>';

										}
									}
									?>
								</select>
								<span class="tip">(选择系统内置背景图)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>上传背景图：</label></label>
								<input type="text" name="diybg" id="bgs" class="px" value="{pigcms{$card.diybg}" style="width:210px;"/>
								<a class="fileupload-exists btn btn-ccc" style="margin-left:20px;font-size:12px;" onclick="upyunPicUpload('bgs',1000,600,'card')">上传图片</a>
								<span class="tip">(同时选择系统背景图和上传背景图，优先使用上传背景图)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>卡号文字颜色：</label></label>
								<input type="text" name="numbercolor" id="numbercolor" value="{pigcms{$card.numbercolor}" class="px color" style="width:80px;background-image:none;background-color:rgb(0,0,0);color:rgb(255,255,255);" onblur="document.getElementById('number').style.color=document.getElementById('numbercolor').value;"/>
								<span class="tip"></span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>会员卡折扣数：</label></label>
								<input type="text" name="discount" id="discount" class="px" value="{pigcms{$card.discount|floatval}" style="width:210px;"/><span class="tip">(请填写0到10的数字,0相当于不打折,比如95折 填写9.5即可)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>用户自助领卡：</label></label>
								<label class="radiolabel first"><span><label><input name="self_get" value="1" type="radio" <if condition="$card.self_get eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="self_get" value="0" type="radio" <if condition="$card.self_get eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(用户访问会员卡页面时自动领卡)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>支持实体卡：</label></label>
								<label class="radiolabel first"><span><label><input name="is_physical_card" value="1" type="radio" <if condition="$card.is_physical_card eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="is_physical_card" value="0" type="radio" <if condition="$card.is_physical_card eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(用户会员卡页面出现绑定实体卡的选项)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>自动领卡：</label></label>
								<label class="radiolabel first"><span><label><input name="auto_get" value="1" type="radio" <if condition="$card.auto_get eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="auto_get" value="0" type="radio" <if condition="$card.auto_get eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(用户访问会员卡页面时自动领卡，且开启该功能后用户可以先领取商家优惠券再领取商家会员卡)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>消费自动领卡：</label></label>
								<label class="radiolabel first"><span><label><input name="auto_get_buy" value="1" type="radio" <if condition="$card.auto_get_buy eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="auto_get_buy" value="0" type="radio" <if condition="$card.auto_get_buy eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(购买商家商品后自动领卡)</span>
							</div>
							
							
							
							<div class="headings gengduoxian">余额 / 充值<span class="note-inf">带<a style="color:red;">*</a>为必填项</span></div>
							<if condition="$config.merchant_card_recharge_offline eq 1">
								<div class="form-group">
									<label class="tiplabel"><label>会员卡初始金额：</label></label>
									<input type="text" name="begin_money" id="begin_money" class="px" value="{pigcms{$card.begin_money|floatval=###}" style="width:210px;"/><span class="tip">(领会员卡时自动向该卡赠送金额)</span>
								</div>
							</if>
							
							<if condition="is_array($card['recharge_rule'])" >
							<volist name="card.recharge_rule" id="vv">
							
							<div class="form-group plus_re" data-id="{pigcms{$i}">
								<label class="tiplabel"><label>充值返现规则：</label></label>
								充值&nbsp;&nbsp;<input type="text" name="recharge_count[]"  class="px" value="{pigcms{$vv.count}" style="width:60px;"/>&nbsp;&nbsp;元, 返&nbsp;&nbsp;<input type="text" name="recharge_back_money[]"  class="px" value="{pigcms{$vv.back_money}" style="width:60px;"/>&nbsp;&nbsp;元, 返&nbsp;&nbsp;<input type="text" name="recharge_back_score[]"  class="px" value="{pigcms{$vv.back_score}" style="width:60px;"/>&nbsp;&nbsp;{pigcms{$config['score_name']}
								<button type="button" class="plus_recharge" <php>if(count($card['recharge_rule'])<5 && $i==count($card['recharge_rule'])){</php> style="visibility:visible;"<php>}else{</php>style="visibility:hidden;"<php>}</php>></button>
								<button type="button" class="reduce_recharge" <php>if(count($card['recharge_rule'])<5 && $i==count($card['recharge_rule'])){</php> style="visibility:hidden;"<php>}else{</php>style="visibility:visible;"<php>}</php>></button>
								<span class="tip" <php>if($i!=1){</php> style="visibility:hidden;"<php>}else{</php>style="visibility:visible;"<php>}</php>>(用户每在线充值一笔达到金额即赠送的金额与积分，最多可添加5种充值类型)</span>
							</div>
							</volist>
							<else />
							<div class="form-group plus_re" data-id="1">
								<label class="tiplabel"><label>充值返现规则：</label></label>
								充值&nbsp;&nbsp;<input type="text" name="recharge_count[]"  class="px" value="" style="width:60px;"/>&nbsp;&nbsp;元, 返&nbsp;&nbsp;<input type="text" name="recharge_back_money[]"  class="px" value="" style="width:60px;"/>&nbsp;&nbsp;元, 返&nbsp;&nbsp;<input type="text" name="recharge_back_score[]"  class="px" value="" style="width:60px;"/>&nbsp;&nbsp;{pigcms{$config['score_name']}
								<button type="button" class="plus_recharge"></button>
								<button type="button" class="reduce_recharge"></button>
								<span class="tip">(用户每在线充值一笔达到金额即赠送的金额与积分，最多可添加5中充值类型)</span>
							</div>
							</if>
							<div class="form-group">
								<label class="tiplabel"><label>充值金额建议：</label></label>
								<input type="text" name="recharge_suggest" id="recharge_suggest" class="px" value="{pigcms{$card.recharge_suggest}" style="width:210px;"/><span class="tip">(用户可以在充值页面快速点击按钮充值该建议金额。英文逗号隔开，如 10, 20, 30 )</span>
							</div>
							
							
							
							<div class="headings gengduoxian">{pigcms{$config['score_name']}<span class="note-inf">带<a style="color:red;">*</a>为必填项</span></div>
							<div class="form-group">
								<label class="tiplabel"><label>会员卡初始{pigcms{$config['score_name']}：</label></label>
								<input type="text" name="begin_score" id="begin_score" class="px" value="{pigcms{$card.begin_score}" style="width:210px;"/><span class="tip">(领会员卡时自动向该卡赠送{pigcms{$config['score_name']})</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>消费获得{pigcms{$config['score_name']}：</label></label>
								<label class="radiolabel first"><span><label><input class="support_score_select" name="support_score_select" value="1" type="radio" <if condition="$card.support_score gt 0">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input class="support_score_select" name="support_score_select" value="0" type="radio" <if condition="$card.support_score eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(用户购买商品之后是否能获取一定的{pigcms{$config['score_name']})</span>
							</div>
							<div class="form-group support_score">
								<label class="tiplabel"><label>消费一元获得{pigcms{$config['score_name']}：</label></label>
								<input type="text" name="support_score" id="support_score" class="px" value="{pigcms{$card.support_score}" style="width:210px;"/><span class="tip">(用户每消费一元获得的{pigcms{$config['score_name']}数，大于1的整数)</span>
							</div>
							
							
							
							<div class="headings gengduoxian">优惠券<span class="note-inf">带<a style="color:red;">*</a>为必填项</span></div>
							<div class="form-group">
								<label class="tiplabel"><label>自动领优惠券：</label></label>
								<label class="radiolabel first"><span><label><input name="auto_get_coupon" value="1" type="radio" <if condition="$card.auto_get_coupon eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="auto_get_coupon" value="0" type="radio" <if condition="$card.auto_get_coupon eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(仅限第一次领卡时触发)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>微信购买自动派发优惠券开关：</label></label>
								<label class="radiolabel first"><span><label><input name="weixin_send" value="1" type="radio" <if condition="$card.weixin_send eq 1">checked="checked"</if> /></label>&nbsp;<span>开启</span></span></label>
								<label class="radiolabel"><span><label><input name="weixin_send" value="0" type="radio" <if condition="$card.weixin_send eq 0">checked="checked"</if>/></label>&nbsp;<span>关闭</span>&nbsp;</span></label>
								<span class="tip">(仅支持微信支付后自动派发优惠券)</span>
							</div>
							
							
							
							<div class="headings gengduoxian">使用说明<span class="note-inf">带<a style="color:red;">*</a>为必填项</span></div>
							<div class="form-group">
								<label class="tiplabel" style="vertical-align:top;"><label><font color="red">*</font> 充值说明：</label></label>
								<textarea name="recharge_des" id="recharge_des" class="px" style="width:410px;height:120px;">{pigcms{$card.recharge_des}</textarea>
							</div>
							<div class="form-group">
								<label class="tiplabel" style="vertical-align:top;"><label><font color="red">*</font>{pigcms{$config['score_name']}说明：</label></label>
								<textarea name="score_des" id="score_des" class="px" style="width:410px;height:120px;">{pigcms{$card.score_des}</textarea>
							</div>
							<div class="form-group">
								<label class="tiplabel" style="vertical-align:top;"><label><font color="red">*</font>会员卡说明：</label></label>
								<textarea name="info" id="info" class="px" style="width:410px;height:120px;">{pigcms{$card.info}</textarea>
							</div>
							
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
						
						<div class="tab-content card_new" id="tab2">
							<div class="headings gengduoxian" style="display:none">操作<span class="note-inf"></div>
							<div class="form-group" style="display:none">
								<label class="tiplabel"><label>同步微信会员卡：</label></label>
								
								<a href="javascript:void(0)" onclick="sysc()" class="fileupload-exists btn btn-ccc">同步</a>
								<span class="tip">(编辑下面的数据并保存后再同步)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>是否同步会员卡(<font color="red">同步请选择是</font>)</label></label>
								<label class="radiolabel first"><span><label><input name="sysc_weixin" value="1" type="radio" <if condition="$card.sysc_weixin eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="sysc_weixin" value="0" type="radio" <if condition="$card.sysc_weixin eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
							</div>
						
							<div class="headings gengduoxian">基本信息<span class="note-inf"></span>
								<a href="javascript:void(0);" id="show_card_dome">查看示例</a>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label>会员卡名称：</label></label>
								
								<input type="text" name="wx_title"  class="px" value="{pigcms{$card.wx_param.title}" style="width:210px;"/><span class="tip">(9个汉字以内)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>会员卡提醒：</label></label>
								
								<input type="text" name="wx_notice"  class="px" value="{pigcms{$card.wx_param.notice}" style="width:210px;"/><span class="tip">(16个汉字以内)</span>
							</div>
							
							<div class="form-group ">
								<label class="tiplabel"><label>会员卡颜色：</label></label>
								<select name="wx_color" id="color"class="px" <if condition="$card['wx_param']['color']">style="background-color:{pigcms{$card['wx_param']['color']}"</if>>
									<volist name="color_list" id="vo">
										<option value="{pigcms{$key}" style="background-color:{pigcms{$vo};margin:5px auto;color:{pigcms{$vo};" <php>if($card['wx_param']['color']==$vo){</php>selected="selected"<php>}</php>>{pigcms{$vo}</option>
									</volist>
								</select>
								<span class="tip">(会员卡字体颜色，不包含卡号)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>中间按钮标题：</label></label>
								
								<input type="text" name="wx_center_title"  class="px" value="{pigcms{$card.wx_param.center_title}" style="width:210px;"/><span class="tip">(6个汉字以内)</span>
								<label class="tiplabel"><label>中间按钮副标题：</label></label>
								
								<input type="text" name="wx_center_sub_title"  class="px" value="{pigcms{$card.wx_param.center_sub_title}" style="width:210px;"/><span class="tip">(8个汉字以内)</span>
								<label class="tiplabel"><label>中间按钮链接：</label></label>
								
								<input type="text" name="wx_center_url"  id="url_center-title"  class="px" value="{pigcms{$card.wx_param.center_url}" style="width:210px;"/>
								
								<a href="" id="addLink" class="fileupload-exists btn btn-ccc" onclick="addLinks('url_center-title',0)" data-toggle="modal">从功能库选择</a>
								<span class="tip">(128个字符以内)</span>
							</div>
							
							
							<div class="form-group">
								<label class="tiplabel"><label>自定义类目1(可填)：</label></label>
								
								<input type="text" name="wx_custom_url_name"  class="px" value="{pigcms{$card.wx_param.custom_url_name}" style="width:210px;"/><span class="tip">(5个汉字以内)</span>
								<label class="tiplabel"><label>提示语：</label></label>
								
								<input type="text" name="wx_custom_url_sub_title"  class="px" value="{pigcms{$card.wx_param.custom_url_sub_title}" style="width:210px;"/>
								
								<span class="tip">(6个汉字以内)</span>
								<label class="tiplabel"><label>自定义跳转链接：</label></label>
								
								<input type="text" name="wx_custom_url" id="cuntom_url" class="px" value="{pigcms{$card.wx_param.custom_url}" style="width:210px;"/>
								<a href="" id="addLink" class="fileupload-exists btn btn-ccc" onclick="addLinks('cuntom_url',0)" data-toggle="modal">从功能库选择</a>
								<span class="tip">(128个字符以内)</span>
							</div>
							<div class="form-group ">
								<label class="tiplabel"><label>自定义类目2(可填)：</label></label>
								<input type="text" name="wx_custom_cell1_name"  class="px" value="{pigcms{$card.wx_param.custom_cell1_name }" style="width:210px;"/><span class="tip">(5个汉字以内)</span>
								<label class="tiplabel"><label>提示语：</label></label>
								
								<input type="text" name="wx_custom_cell1_tips"  class="px" value="{pigcms{$card.wx_param.custom_cell1_tips}" style="width:210px;"/><span class="tip">(6个汉字以内)</span>
								<label class="tiplabel"><label>自定义跳转链接：</label></label>
								
								<input type="text" name="wx_custom_cell1_url" id="custom_cell1_url" class="px" value="{pigcms{$card.wx_param.custom_cell1_url}" style="width:210px;"/>
								<a href="" id="addLink" class="fileupload-exists btn btn-ccc" onclick="addLinks('custom_cell1_url',0)" data-toggle="modal">从功能库选择</a>
								<span class="tip">(128个字符以内)</span>
							</div>
							
							<div class="form-group ">
								<label class="tiplabel"><label>自定义类目3(可填)：</label></label>
								<input type="text" name="wx_custom_cell2_name"  class="px" value="{pigcms{$card.wx_param.custom_cell2_name }" style="width:210px;"/><span class="tip">(5个汉字以内)</span>
								<label class="tiplabel"><label>提示语：</label></label>
								
								<input type="text" name="wx_custom_cell2_tips"  class="px" value="{pigcms{$card.wx_param.custom_cell2_tips}" style="width:210px;"/><span class="tip">(6个汉字以内)</span>
								<label class="tiplabel"><label>自定义跳转链接：</label></label>
								
								<input type="text" name="wx_custom_cell2_url"  id="custom_cell2_url" class="px" value="{pigcms{$card.wx_param.custom_cell2_url}" style="width:210px;"/>
								<a href="" id="addLink" class="fileupload-exists btn btn-ccc" onclick="addLinks('custom_cell2_url',0)" data-toggle="modal">从功能库选择</a>
								<span class="tip">(128个字符以内)</span>
							</div>
							
							
							
							<div class="form-group">
								<label class="tiplabel"><label>自定义类目4(可填)：</label></label>
								<input type="text" name="wx_promotion_url_name"  class="px" value="{pigcms{$card.wx_param.promotion_url_name }" style="width:210px;"/><span class="tip">(5个汉字以内)</span>
								<label class="tiplabel"><label>提示语：</label></label>
								
								<input type="text" name="wx_promotion_url_sub_title"  class="px" value="{pigcms{$card.wx_param.promotion_url_sub_title}" style="width:210px;"/><span class="tip">(6个汉字以内)</span>
								<label class="tiplabel"><label>自定义跳转链接：</label></label>
								
								<input type="text" name="wx_promotion_url" id="promotion_url" class="px" value="{pigcms{$card.wx_param.promotion_url}" style="width:210px;"/>
								<a href="" id="addLink" class="fileupload-exists btn btn-ccc" onclick="addLinks('promotion_url',0)" data-toggle="modal">从功能库选择</a>
									<span class="tip">(128个字符以内)</span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel" style="vertical-align:top;"><label>特权说明：</label></label>
								<textarea name="wx_prerogative" id="prerogative" class="px" style="width:410px;height:120px;">{pigcms{$card.wx_param.prerogative}</textarea>
							</div>
							
							<div class="form-group wx_coupon">
		
								<label class="tiplabel"><label>商家服务类型：</label></label>
								<input type="checkbox"  name="wx_business_service[]" class="px" value="BIZ_SERVICE_DELIVER" <if condition="in_array('BIZ_SERVICE_DELIVER',$card['wx_param']['business_service'])">checked="checked"</if>>外卖服务
								<input type="checkbox"  name="wx_business_service[]" class="px" value="BIZ_SERVICE_FREE_PARK" <if condition="in_array('BIZ_SERVICE_FREE_PARK',$card['wx_param']['business_service'])">checked="checked"</if>>停车位
								<input type="checkbox"  name="wx_business_service[]" class="px" value="BIZ_SERVICE_WITH_PET" <if condition="in_array('BIZ_SERVICE_WITH_PET',$card['wx_param']['business_service'])">checked="checked"</if>>可带宠物
								<input type="checkbox"  name="wx_business_service[]" class="px" value="BIZ_SERVICE_FREE_WIFI" <if condition="in_array('BIZ_SERVICE_FREE_WIFI',$card['wx_param']['business_service'])">checked="checked"</if>>免费wifi
								<span class="tip">(已经设置就不能取消，可以更改)</span>
							</div>
							
							<div class="headings gengduoxian">卡券图文（图片大小1MB）：</div>
							
							<div class="form-group other">	
								<if condition="!empty($card['wx_param']['text_image_list'])">
									<table cellpadding="0" cellspacing="0" class="px" width="60%" >
									<volist name="card.wx_param.text_image_list" id="vo">
										<tr class="plus textIamge" >
											<td width="100"><label class="tiplabel">图文消息<label>{pigcms{$i}</label></label></td>
											
											<td>
												
												<table style="width:100%;">
												<tr class="textIamge">
													<td width="66" >图片：</td>
													<td width="380"><input type="text"   name="wx_image_url[]"  class="px input-image" value="{pigcms{$vo.image_url}"   readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="fileupload-exists btn btn-ccc J_selectImage" >上传图片</a></td>
													<td width="66" >描述：</td>
													<td width="180"><textarea  class="px" style="width:410px;height:50px;margin-right:5px;" name="wx_text[]" >{pigcms{$vo.text}</textarea></td>
													<td rowspan="2" class="delete">
														<a href="javascript:void(0)" class="fileupload-exists btn btn-ccc" onclick="del(this)">删除</a>
													</td>
												<tr/>
												
											</table>
											</td>
										</tr>
											</volist>
										<tr class="textIamge">
											<td></td>
											<td><a href="javascript:void(0)" onclick="plus()" class="fileupload-exists btn btn-ccc">增加</a></td>
										</tr>
									</table>
									
								<else />
								<table cellpadding="0" cellspacing="0" class="px" width="60%" >
								
									<tr class="plus textIamge" >
										<td width="100"><label class="tiplabel">图文消息<label>1</label></label></td>
										<td>
											<table style="width:100%;">
												<tr class="textIamge">
													<td width="66" >图片：</td>
													<td width="380"><input type="text"   name="wx_image_url[]"  class="px input-image" value=""   readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="fileupload-exists btn btn-ccc J_selectImage" >上传图片</a></td>
													<td width="66" >描述：</td>
													<td width="180"><textarea  class="px" style="width:410px;height:50px;margin-right:5px;" name="wx_text[]" ></textarea></td>
													<td rowspan="2" class="delete">
														<a href="javascript:void(0)" class="fileupload-exists btn btn-ccc" onclick="del(this)">删除</a>
													</td>
												<tr/>
												
											</table>
										</td>
									</tr>
									<tr class="textIamge">
										<td></td>
										<td><a href="javascript:void(0)" onclick="plus()" class="fileupload-exists btn btn-ccc">增加</a></td>
									</tr>
									
									
								</table>
								</if>
							</div>
							
							
							
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
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
	<div class="vipcard_box">
		<div class="vipcard">
			<img id="cardbg" src="<if condition="$card.diybg neq ''">{pigcms{$card.diybg}<elseif condition="$card.bg neq ''" />{pigcms{$card.bg}<else/>./static/images/card/card_bg04.png</if>"/>
			<strong class="pdo verify" id="number" style="color:{pigcms{$card.numbercolor};right:142px"><span>6537 1998</span></strong>
		</div>
		<span class="red">背景图宽540px高320px，图片类型png，大小不超过1MB 。</span>
	</div>
	<style>
		ul, ol { padding: 0;}
		.banners { position: relative; overflow: auto; text-align: center;}
		.banners li { list-style: none; }
		.banners ul li { float: left; }
		#b04 { width: 320px;    overflow: hidden;    left: 40%;display:none;z-index:1000}
		#b04 .dots { position: absolute; left: 0; right: 0; bottom: 20px;}
		#b04 .dots li 
		{ 
			display: inline-block; 
			width: 10px; 
			height: 10px; 
			margin: 0 4px; 
			text-indent: -999em; 
			border: 2px solid #000; 
			border-radius: 6px; 
			cursor: pointer; 
			opacity: .4; 
			-webkit-transition: background .5s, opacity .5s; 
			-moz-transition: background .5s, opacity .5s; 
			transition: background .5s, opacity .5s;
		}

		#b04 .dots li.active {
			background: #000;
			opacity: 1;
		}
		#b04 .arrow { position: absolute; top: 200px;}
		#b04 #al { left: 15px;}
		#b04 #ar { right: 15px;}
		#pwd_bg{
			background-color: #000;
	position: fixed;
	z-index: 999;
	left: 0;
	top: 0;
	
	width: 100%;
	height: 100%;
	opacity: 0.3;
	filter: alpha(opacity=30);
	-moz-opacity: 0.3;
			
		}

</style>	
	<div id="pwd_bg" style="display:none">

	</div>
	<div class="banners vipcard_box"  id="b04" >
		<ul style="margin:0;">
			<li><img src="{pigcms{$static_path}images/card1.jpg" alt="" style="width:320px;480px;" ></li>
			<li><img src="{pigcms{$static_path}images/card2.jpg" alt="" style="width:320px;480px;" ></li>
			<li><img src="{pigcms{$static_path}images/card3.png" alt="" style="width:320px;480px;" ></li>
			<li><img src="{pigcms{$static_path}images/card4.jpg" alt="" style="width:320px;480px;" ></li>
		</ul>

		<a href="javascript:void(0);" class="unslider-arrow04 prev"><img class="arrow" id="al" src="{pigcms{$static_path}images/arrowl.png" alt="prev"  style="width:20px;35px;"></a>

		<a href="javascript:void(0);" class="unslider-arrow04 next"><img class="arrow" id="ar" src="{pigcms{$static_path}images/arrowr.png" alt="next" style="width:20px;35px;"></a>

	</div>
</div>

<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script src="./static/js/cart/jscolor.js" type="text/javascript"></script>
<link rel="stylesheet" href="./static/kindeditor/themes/default/default.css"/>
<link rel="stylesheet" href="./static/kindeditor/plugins/code/prettify.css"/>


    <link rel="stylesheet" href="./static/validate/dist/css/bootstrapValidator.css"/>

    <!-- Include the FontAwesome CSS if you want to use feedback icons provided by FontAwesome -->
    <!--<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" />-->

    <script type="text/javascript" src="./static/validate/vendor/jquery/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="./static/validate/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./static/validate/dist/js/bootstrapValidator.js"></script>
<style>
	.select_tab{
		width:100px;
		height:36px;
		color: #555;
		border: 1px solid #c5d0dc;
		font-size:16px;
		z-index:9;
		line-height: 36px;
    text-align: center;
		position: relative;
	}
	label .select_tab{
		display: inline-block;
		margin: 0 0 -1px;
		padding: 15px 25px;
		font-weight: 600;
		text-align: center;
		color: #bbb;
		border: 1px solid transparent;
	}
	
	.select{
		border-top: 1px solid orange;
		border-bottom: 1px solid #fff;
	}
	.card_new{
		margin-top:-6px;
	}
	.other label,table{
		color:#a0a0a0;
	}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_path}js/unslider.min.js?2222"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script type="text/javascript">
	KindEditor.ready(function(K){
			var site_url = "{pigcms{$config.site_url}";
			var editor = K.editor({
				allowFileManager : true
			});
			$('.J_selectImage').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic_wx')}";
				editor.loadPlugin('image', function(){
					editor.plugin.imageDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							upload_file_btn.siblings('.input-image').val(url);
							editor.hideDialog();
						}
					});
				});
			});

		});
</script>
<script type="text/javascript">
    $(document).ready(function() {
		
		$('.form').bootstrapValidator({
	//        live: 'disabled',
			message: 'This value is not valid',
		  
			fields: {
				recharge_des: {
					validators: {
						notEmpty: {
							message: '充值说明不能为空'
						}
					}
				},
				score_des: {
					validators: {
						notEmpty: {
							message: '积分说明不能为空'
						}
					}
				},
				info: {
					validators: {
						notEmpty: {
							message: '会员卡说明不能为空'
						}
					}
				},
				 discount: {
					validators: {
						callback: {
							message: '错误的折扣',
							callback: function(value, validator) {
							   return (value>=0 && value<=10)
								
							}
						}
					}
				}
			}
		});
		
		$('#sys_card_bg').change(function(){
			// if($.trim($('#bgs').val()) == ''){
				$('#cardbg').attr('src', $(this).val());
				$('#bgs').val('')
			// }
		});
		
		 var unslider04 = $('#b04').unslider({
			dots: true
		}),
			data04 = unslider04.data('unslider');
			$('.unslider-arrow04').click(function() {

			var fn = this.className.split(' ')[1];

				data04[fn]();

			});
		
		$('#show_card_dome').click(function(){
			$('#b04').show();
			$('#pwd_bg').show();
		})
		
		$('#pwd_bg').click(function(){
			$('#b04').hide();
			$('#pwd_bg').hide();
		})
		
		if($('.support_score_select:checked').val()==0){
            $('.support_score').css('display','none');
		}else{
            $('.support_score').css('display','block');
		}

		$('#support_recharge').change(function(event) {
			if($('#support_recharge').val()==0){
                $('.support_recharge').css('display','none');

			}else{
                $('.support_recharge').css('display','block');
			}
		});

		$('.support_score_select').change(function(event) {
            if($('.support_score_select:checked').val()==0){
                $('.support_score').css('display','none');
			}else{
                $('.support_score').css('display','block');
			}
		});
	   $('#tab2').hide();
		$('.select_tab').click(function(){
			
			$('.select_tab').removeClass('select');
			$(this).addClass('select');
			var id_for = $(this).attr('for');
			if(id_for=='tab1'){
				// $('#sysc_weixin').val(0);
				$('#tab2').hide();
				$('.vipcard_box').show();
				
			}else{
				$('#tab1').hide();
				$('.vipcard_box').hide();
				// $('#sysc_weixin').val(1);
			}
		
			$('#'+id_for).show();
			$('#b04').hide();
			$('#pwd_bg').hide();
			
		});
		
		//$('select[name="wx_color"]').css('background-color','#63b359');	
			$('select[name="wx_color"]').change(function(event) {
				$('#wx_color').css('background-color',$('select[name="wx_color"]').find('option:selected').html());
				$(this).css('background-color',$('select[name="wx_color"]').find('option:selected').html());
			});		
		if($('.plus').length<2){
			$('.delete').children().hide();
		}
		if($('.plus_re').length<2){
			
			$('.plus_re:first').find('.reduce_recharge').css('visibility','hidden');
		}
		$('.plus_recharge').click(function(){
			var item = $('.plus_re:last');
			var newitem = $(item).clone(true);
			var No = parseInt(item.data('id'))+1;
			
			$(this).css('visibility','hidden');
			$(this).siblings('.reduce_recharge').css('visibility','visible');
			// $(this).removeClass('plus_recharge')
			if($('.plus_re').length>4){
				layer.alert('不能超过5条配置');
			}else{
				newitem.find('.tip').css('visibility','hidden');
				
				$(item).after(newitem);
				newitem.find('input').attr('value','');
				newitem.data('id',No)
				console.log(No)
				if(No==5){
					 $('.plus_re:last').find('.plus_recharge').css('visibility','hidden');
					 $('.plus_re:last').find('.reduce_recharge').css('visibility','visible');
				}
			}
			
		})
		$(".reduce_recharge").click(function() {
			if($('.plus_re').length<=1){
				$('.plus_re input').val('');
				$('.plus_re:first').find('.tip').css('visibility','visible');
				
			}else{
				
				$(this).parents('.plus_re').remove();
				$('.plus_re:last').find('.reduce_recharge').css('visibility','hidden');
				$('.plus_re:last').find('.plus_recharge').css('visibility','visible');
				
				$.each($('.plus_re'), function(index, val) {
					var No =index+1;
					if(No==1){
						$(val).find('.tip').css('visibility','visible')
					}
					$(val).data('id',No);
				
				});
			}
		})
    });
	function upload_func(){
		$('#cardbg').attr('src',$('#bgs').val());
	}
	
	function plus(){
			var item = $('.plus:last');
			var newitem = $(item).clone(true);
			var No = parseInt(item.find(".tiplabel label").html())+1;
			$('.delete').children().show();
			if(No>4){
				layer.alert('不能超过4条信息');
			}else{
				$(item).after(newitem);
				newitem.find('input').attr('value','');
				newitem.find('textarea').attr('value','');
				newitem.find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
				newitem.find(".tiplabel label").html(No);
				newitem.find('input[name="url[]"]').attr('id','url'+No);
				newitem.find('.delete').children().show();
			}
		}
		function del(obj){
			if($('.plus').length<=1){
				$('input[name="wx_image_url[]"]').val('');
				$('textarea[name="wx_text[]"]').val('');
				$('.delete').children().hide();
			}else{
				if($('.plus').length==2){
					$('.delete').children().hide();
				}
				$(obj).parents('.plus').remove();
				$.each($('.plus'), function(index, val) {
					var No =index+1;
					$(val).find(".tiplabel label").html(No);
					$(val).find('input[name="url[]"]').attr('id','url'+No);
					$(val).find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
				});
			}
		}
		
		function sysc(){
			$.ajax({
				url: '{pigcms{:U('sysc_wxcard')}',
				type: 'POST',
				dataType: 'json',
				data: {param1: 'value1'},
				beforeSend:function(){
					var index = layer.load(1, {
					  shade: [0.3,'#000'] //0.1透明度的白色背景
					});
				},
				success:function(data){
					layer.closeAll()
					layer.alert(data.info)
				}
			});
		}
		
		function addLinks(domid,iskeyword){
			art.dialog.data('domid', domid);
			art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
		}
</script>
<link rel="stylesheet" href="{pigcms{$static_path}css/card_new.css"/>

<include file="Public:footer"/>
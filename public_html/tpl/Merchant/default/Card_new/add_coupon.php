<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Card_new/index')}">会员卡</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Card_new/card_new_coupon')}">优惠券列表</a></li>
			<li class="active"><if condition="isset($coupon)">编辑优惠券<else />添加优惠券</if></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="myform"  target="_top" method="post" >
							<input type="hidden" name="coupon_id" value="{pigcms{$_GET['coupon_id']}">
							<input type="hidden" name="is_wx_card" value="{pigcms{$coupon['is_wx_card']}">
							<input type="hidden" name="wx_cardid" value="{pigcms{$coupon['wx_cardid']}">
								<label for="tab1" class="select_tab select" style="cursor:pointer;">基本信息</label>
								<if condition="$config['coupon_wx_sync']">
									<label for="tab2" class="select_tab" style="margin-left:-4px;cursor:pointer;<if condition="!$coupon['sync_wx'] && isset($coupon['sync_wx'])">display:none;</if>" >同步微信优惠券</label>
								</if>
								<div class="tab-content card_new" id="tab1" >
									<div class="headings gengduoxian">基本参数</div>
									
									<div class="form-group">
										<label class="tiplabel"><label>状态:</label></label>
										<label class="radiolabel first"><span><label><input name="status" value="1" type="radio" <if condition="$coupon['status'] eq 1 OR !isset($coupon)">checked="checked"</if> /></label>&nbsp;<span>正常</span></span></label>
										<label class="radiolabel"><span><label><input name="status" value="0" type="radio" <if condition="$coupon.status eq 0 AND isset($coupon)">checked="checked"</if>/></label>&nbsp;<span>禁止</span>&nbsp;</span></label>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label><font color="red">*</font>优惠券名称:</label></label>
										<input type="text" name="name" id="name" class="px" value="{pigcms{$coupon.name}" style="width:210px;"/><span class="tip">(要求9个汉字以内)</span>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label>上传图片:</label></label>
										<input type="text" name="img" id="img" class="px input-image" value="{pigcms{$coupon.img}" style="width:210px;" readOnly/>
										<a class="fileupload-exists btn btn-ccc" style="margin-left:20px;font-size:12px;" onclick="upyunPicUpload('img',1000,600,'card')">上传图片</a>
										<span class="tip">(宽高为200以下的正方形图片，最小60)</span>
									</div>
									
									
									<div class="form-group">
										<label class="tiplabel"><label><font color="red">*</font>数量<if condition="isset($coupon)">({pigcms{$coupon['num']-$coupon['hadpull']}张)</if>:</label></label>
										<if condition="isset($coupon)">
										<select name="add" class="px"   >
											<option value="0">增加</option>
											<option value="1">减少</option>
										</select>
										</if>
										<if condition="isset($coupon)">
											<input type="text" name="num_add" id="num_add" class="px" value="" style="width:210px;"/>
											<input type="hidden" name="num" id="num" class="px" value="{pigcms{$coupon['num']}" style="width:210px;"/>
										<else />
											<input type="text" name="num" id="num" class="px" value="" style="width:210px;"/>
										</if>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label><font color="red">*</font>领取数量限制:</label></label>
										<input type="text" name="limit" id="limit" class="px" value="{pigcms{$coupon.limit}" <if condition="isset($coupon)">disabled="disabled"</if> style="width:210px;"/>
									</div>
									<div class="form-group" style="display:none">
										<label class="tiplabel"><label><font color="red">*</font>使用数量限制:</label></label>
										<input type="text" name="use_limit"  class="px" value="1" style="width:210px;" <if condition="isset($coupon)">disabled="disabled"</if>/>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label><font color="red">*</font>优惠金额:</label></label>
										<input type="text" name="discount" id="discount" class="px" <if condition="isset($coupon)">disabled="disabled"</if> value="{pigcms{$coupon.discount}" style="width:210px;"/>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label><font color="red">*</font>最小订单金额:</label></label>
										<input type="text" name="order_money" id="order_money" class="px" <if condition="isset($coupon)">disabled="disabled"</if> value="{pigcms{$coupon.order_money}" style="width:210px;"/>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label>起始时间:</label></label>
										<input type="text" class="input-text" name="start_time" style="width:120px;" id="d4311"  value="<if condition="$coupon.start_time gt 0">{pigcms{$coupon.start_time|date='Y-m-d',###}</if>" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>-
										<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="<if condition="$coupon.end_time gt 0">{pigcms{$coupon.end_time|date='Y-m-d',###}</if>" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" />
							
									</div>
									<div class="headings gengduoxian">使用配置</div>
									
									<div class="form-group">
										<label class="tiplabel"><label>是否与商家会员卡优惠同时使用:</label></label>
										<label class="radiolabel first"><span><label><input name="use_with_card" value="1" type="radio" <if condition="$coupon.use_with_card eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
										<label class="radiolabel"><span><label><input name="use_with_card" value="0" type="radio" <if condition="$coupon.use_with_card eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
									</div>
									<div class="form-group wx_coupon">
		
										<label class="tiplabel"><label>指定店铺：</label></label>
										<volist name="store_list" id="vo">
										
											<input type="checkbox"  name="store_id[]" class="px" value="{pigcms{$vo.store_id}" <if condition="in_array($vo['store_id'],$coupon['store_id']) OR !isset($coupon)">checked="checked"</if>>【{pigcms{$vo.name}】
										</volist>
										
										
									</div>
									
									<div class="form-group">
										<label class="tiplabel"><label>是否领卡时自动领取:</label></label>
										<label class="radiolabel first"><span><label><input name="auto_get" value="1" type="radio" <if condition="$coupon.auto_get eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
										<label class="radiolabel"><span><label><input name="auto_get" value="0" type="radio" <if condition="$coupon.auto_get eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label>是否只允许新用户领取:</label></label>
										<label class="radiolabel first"><span><label><input name="allow_new" value="1" type="radio" <if condition="$coupon.allow_new eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
										<label class="radiolabel"><span><label><input name="allow_new" value="0" type="radio" <if condition="$coupon.allow_new eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
									</div>
									
									<div class="form-group ">
										<label class="tiplabel"><label>使用平台：</label></label>
										<if condition="isset($coupon)">
											{pigcms{$coupon['platform']}
											
										
										<else />
											<volist name="platform" id="vo">
											
												<input type="checkbox"  name="platform[]" class="px" value="{pigcms{$key}" checked="checked"s>{pigcms{$vo}
											</volist>
										</if>
									</div>
									<div class="form-group ">
										<label class="tiplabel"><label>使用类别：</label></label>
										<if condition="isset($coupon)">
											{pigcms{$coupon.cate_name}
										<else />
										<select name="cate_name" id="cate_name"class="px" >
											<volist name="category" id="vo">
												<option value="{pigcms{$key}" >{pigcms{$vo}</option>
											</volist>
										</select>
										</if>
									</div>
									<div class="form-group ">
										<label class="tiplabel"><label>使用分类:</label></label>
										
										<if condition="isset($coupon)">
											{pigcms{$coupon.cate_id}
									
									
										
										</if>
											<label class="radiolabel"  id="cate_id"><label>
										</if>
									</div>
									<if condition="isset($coupon) && ($coupon['cate_name'] eq $config['shop_alias_name'] OR $coupon['cate_name'] eq '全部类别' )"> 
									
									<div class="form-group ">
										<label class="tiplabel"><label>发放渠道:</label></label>
										<if condition="$coupon.send_type eq 0">
										店内店外
										<elseif condition="$coupon.send_type eq 1"/>
										仅店外
										<elseif condition="$coupon.send_type eq 2"/>
										仅店内
										</if>
									</div>
									<else/>
									<div class="form-group send_type" style="display:none">
										<label class="tiplabel"><label>发放渠道:</label></label>
										<label class="radiolabel first"><span><label><input name="send_type" value="0" type="radio" <if condition="$coupon.send_type eq 0">checked="checked"</if> /></label>&nbsp;<span>店内店外</span></span></label>
										<label class="radiolabel first"><span><label><input name="send_type" value="1" type="radio" <if condition="$coupon.send_type eq 1">checked="checked"</if> /></label>&nbsp;<span>仅店外</span></span></label>
										<label class="radiolabel"><span><label><input name="send_type" value="2" type="radio" <if condition="$coupon.send_type eq 2">checked="checked"</if>/></label>&nbsp;<span>仅店内&nbsp;</span></label>
										<span class="tip" style="color:#ca0606">(店外即商家领券中心，店内即快店店铺内部)</span>
									</div>
									</if>
									
									<div class="headings gengduoxian">描述配置</div>
									<div class="form-group">
										<label class="tiplabel"><label><font color="red">*</font>展示简短描述:</label></label>
										<input type="text" name="des" id="des" class="px" value="{pigcms{$coupon.des}" style="width:210px;"/>
									</div>
									<div class="form-group">
										<label class="tiplabel" style="vertical-align:top;"><label><font color="red">*</font>优惠券说明：</label></label>
										<textarea name="des_detial" id="des_detial" class="px" style="width:410px;height:120px;">{pigcms{$coupon.des_detial}</textarea>
									</div>
									
								</div>
								
								<div class="tab-content card_new" id="tab2">
									
								
										<if condition="isset($coupon)">
										<div class="form-group">
											<label class="tiplabel"><label style="color:red">已经完成微信同步，只能修改卡券数量</label></label>
											
										</div>
										</if>
									<div class="form-group">
										<label class="tiplabel"><label>是否同步微信卡券(<font color="red">同步请选择是</font>)</label></label>
										<label class="radiolabel first"><span><label><input name="sync_wx" value="1" type="radio" <if condition="$coupon.sync_wx eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
										<label class="radiolabel"><span><label><input name="sync_wx" value="0" type="radio" <if condition="$coupon.sync_wx eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
									</div>
								
									<div class="headings gengduoxian">基本信息<span class="note-inf"></span>
										<a href="{pigcms{:U('Card_new/show')}" class="see_qrcode">查看示例</a>
									</div>
									
									<div class="form-group">
										<label class="tiplabel"><label>商家名称：</label></label>
										
										<input type="text" name="brand_name" class="px" value="{pigcms{$coupon.wx_param.brand_name}" style="width:210px;"/><span class="tip">(12个汉字以内)</span>
									</div>
									<div class="form-group ">
										<label class="tiplabel"><label>卡券颜色：</label></label>
										<select name="color" id="color"class="px" <if condition="$coupon['wx_param']['color']">style="background-color:{pigcms{$coupon['wx_param']['color']}"</if>>
											<volist name="color_list" id="vo">
												<option value="{pigcms{$key}" style="background-color:{pigcms{$vo};margin:5px auto;color:{pigcms{$vo};" <php>if($coupon['wx_param']['color']==$vo){</php>selected="selected"<php>}</php>>{pigcms{$vo}</option>
											</volist>
										</select>
										<span class="tip">(会员卡字体颜色，不包含卡号)</span>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label>卡券提示:</label></label>
										
										<input type="text" name="notice"  class="px" value="{pigcms{$coupon.wx_param.notice}" style="width:210px;"/><span class="tip">(16个汉字以内)</span>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label>卡券副标题:</label></label>
										
										<input type="text" name="center_sub_title" class="px" value="{pigcms{$coupon.wx_param.center_sub_title}" style="width:210px;"/><span class="tip">(6个汉字以内)</span>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label>立即使用链接:</label></label>
										<input type="text" name="center_url" id="wx_center_url" class="px" value="{pigcms{$coupon.wx_param.center_url}" style="width:210px;"/>
										&nbsp;&nbsp;&nbsp;<a href="" id="addLink" class="fileupload-exists btn btn-ccc" onclick="addLinks('wx_center_url',0)" data-toggle="modal">从功能库选择</a>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label>更多优惠链接:</label></label>
										<input type="text" name="promotion_url"  id="wx_promotion_url"  class="px" value="{pigcms{$coupon.wx_param.promotion_url}" style="width:210px;"/>
										&nbsp;&nbsp;&nbsp;<a href="" id="addLink" class="fileupload-exists btn btn-ccc" onclick="addLinks('wx_promotion_url',0)" data-toggle="modal">从功能库选择</a>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label>自定义链接:</label></label>
										标题：<input type="text" name="custom_url_name"  class="px" value="{pigcms{$coupon.wx_param.custom_url_name}" style="width:210px;"/>
										<span class="tip">(5个汉字以内)</span>
										链接：<input type="text" name="custom_url"   id="custom_url" class="px" value="{pigcms{$coupon.wx_param.custom_url}" style="width:210px;"/>
										&nbsp;&nbsp;&nbsp;<a href="" id="addLink"  class="fileupload-exists btn btn-ccc" onclick="addLinks('custom_url',0)" data-toggle="modal">从功能库选择</a>
										副标题：<input type="text" name="custom_url_sub_title"  class="px" value="{pigcms{$coupon.custom_url_sub_title}" style="width:210px;"/>
										<span class="tip">(6个汉字以内)</span>
									</div>
									<div class="form-group">
										<label class="tiplabel"><label>封面图片:</label></label>
										<input type="text" name="icon_url_list"  class="px input-image" value="{pigcms{$coupon.wx_param.custom_url_name}" style="width:210px;"/>
										<a href="javascript:void(0)" class="fileupload-exists btn btn-ccc J_selectImage" >上传图片</a>
												<span class="tip">
										
									</div>
									<div class="form-group wx_coupon">
				
										<label class="tiplabel"><label>商家服务类型：</label></label>
										<input type="checkbox"  name="business_service[]"  class="px" value="BIZ_SERVICE_DELIVER" <if condition="in_array('BIZ_SERVICE_DELIVER',$coupon['wx_param']['business_service'])">checked="checked"</if>>外卖服务
										<input type="checkbox"  name="business_service[]"  class="px" value="BIZ_SERVICE_FREE_PARK" <if condition="in_array('BIZ_SERVICE_FREE_PARK',$coupon['wx_param']['business_service'])">checked="checked"</if>>停车位
										<input type="checkbox"  name="business_service[]"  class="px" value="BIZ_SERVICE_WITH_PET" <if condition="in_array('BIZ_SERVICE_WITH_PET',$coupon['wx_param']['business_service'])">checked="checked"</if>>可带宠物
										<input type="checkbox"  name="business_service[]"  class="px" value="BIZ_SERVICE_FREE_WIFI" <if condition="in_array('BIZ_SERVICE_FREE_WIFI',$coupon['wx_param']['business_service'])">checked="checked"</if>>免费wifi
										<span class="tip">(已经设置就不能取消，可以更改)</span>
									</div>
									
							
									
									<div class="headings gengduoxian">卡券图文（图片大小1MB）：</div>
									
									<div class="form-group other">	
										<if condition="!empty($coupon['wx_param']['text_image_list'])">
											<table cellpadding="0" cellspacing="0" class="px" width="60%" >
											<volist name="coupon.wx_param.text_image_list" id="vo">
												<tr class="plus textIamge" >
													<td width="100"><label class="tiplabel">图文消息<label>{pigcms{$i}</label></label></td>
													
													<td>
														
														<table style="width:100%;">
														<tr class="textIamge">
															<td width="66" >图片：</td>
															<td width="380"><input type="text"   name="image_url[]"  class="px input-image" value="{pigcms{$vo.image_url}"   readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="fileupload-exists btn btn-ccc J_selectImage" >上传图片</a></td>
															<td width="66" >描述：</td>
															<td width="180"><textarea  class="px" style="width:410px;height:50px;margin-right:5px;" name="text[]" >{pigcms{$vo.text}</textarea></td>
															<td rowspan="2" class="delete">
																<a href="javascript:void(0)" class="fileupload-exists btn btn-ccc" onclick="del(this)">删除</a>
															</td>
														<tr/>
														
													</table>
													</td>
												</tr>
													</volist>
											
											</table>
											
										<else />
										<table cellpadding="0" cellspacing="0" class="px" width="60%" >
										
											<tr class="plus textIamge" >
												<td width="100"><label class="tiplabel">图文消息<label>1</label></label></td>
												<td>
													<table style="width:100%;">
														<tr class="textIamge">
															<td width="66" >图片：</td>
															<td width="380"><input type="text"   name="image_url[]"  class="px input-image" value=""   readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="fileupload-exists btn btn-ccc J_selectImage" >上传图片</a></td>
															<td width="66" >描述：</td>
															<td width="180"><textarea  class="px" style="width:410px;height:50px;margin-right:5px;" name="text[]" ></textarea></td>
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
								</div>			
								<div class="clearfix form-actions">
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="submit" onclick="confirm_coupon()">
											<i class="ace-icon fa fa-check bigger-110"></i>
											保存
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script src="./static/js/cart/jscolor.js" type="text/javascript"></script>
<link rel="stylesheet" href="./static/kindeditor/themes/default/default.css"/>
<link rel="stylesheet" href="./static/kindeditor/plugins/code/prettify.css"/>
<style>
	.select_tab{
		width:124px;
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

<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>

<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>

    <link rel="stylesheet" href="./static/validate/dist/css/bootstrapValidator.css"/>

    <!-- Include the FontAwesome CSS if you want to use feedback icons provided by FontAwesome -->
    <!--<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" />-->

    <script type="text/javascript" src="./static/validate/vendor/jquery/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="./static/validate/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./static/validate/dist/js/bootstrapValidator.js"></script>



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
function preview1(input,img){
	if (input.files && input.files[0]){

		var reader = new FileReader();
		reader.onload = function (e) { $('#'+img).attr('src', e.target.result);}
		reader.readAsDataURL(input.files[0]);
	}
}

function viewTpl(){
	var tid = $('#tpid').val();
	chooseTpl(tid,'',2);
}

function viewTpl2(){
	var tid = $('#conttpid').val();
	chooseTpl(tid,'',4);
}
</script>


<script type="text/javascript">
		var post_url = "{pigcms{:U('Card_new/ajax_ordertype_cateid')}";
		function cate_get(order_type){
			if(order_type!='all'){
					if(order_type=='shop'){
						$('.send_type').show();
						$('input[name="send_type"]').attr('disabled',false)
					}else{
						$('input[name="send_type"]').attr('disabled',true)
						$('.send_type').hide();
						
					}
				
					$.ajax({
						url: post_url,
						type: 'POST',
						dataType: 'json',
						data: {order_type: order_type},
						success:function(date){
							$('#cate_id').html('<select name="cate_id" id="'+order_type+'"><option value="0">全选</option></select>');
							$.each(date, function(index, val) {
								$('#'+order_type).append('<option value="'+val.cat_id+'">'+val.cat_name+'</option>');
							});
						}
					});
			}else{
				$('.send_type').show();
				$('input[name="send_type"]').attr('disabled',false)
				$('#cate_id').empty();
			}
		}
		$(document).ready(function() {
			// $('.myform').bootstrapValidator({
		
			  
				// fields: {
					// name: {
						// validators: {
							// notEmpty: {
								// message: '优惠券名称不能为空'
							// }
						// }
					// },
				
					// num: {
						// validators: {
							// callback: {
								// message: '数量错误',
								// callback: function(value, validator) {
								   // return value>0 
									
								// }
							// }
						// }
					// },	
					// limit: {
						// validators: {
							// callback: {
								// message: '领取数量错误，不能超过总数',
								// callback: function(value, validator) {
									// var coupon_num = $('#num').val()
									// console.log(coupon_num)
									// console.log(value)
									// return Number(value)<=Number(coupon_num) &&  Number(value)>0
								// }
							// }
						// }
					// },
					// use_limit: {
						// validators: {
							// callback: {
								// message: '使用数量设置错误',
								// callback: function(value, validator) {
									
									// var limit_num = $('input[name="limit"]').val()
									// return value<=limit_num && value>0
								// }
							// }
						// }
					// },
					// discount: {
						// validators: {
							// callback: {
								// message: '优惠券金额设置错误',
								// callback: function(value, validator) {
									// return value>0
								// }
							// }
						// }
					// },
					// order_money: {
						// validators: {
							// callback: {
								// message: '最小订单金额设置错误',
								// callback: function(value, validator) {
									// return value>0
								// }
							// }
						// }
					// },
					
					 // des: {
							// validators: {
							// notEmpty: {
								// message: '展示简短描述不能为空'
							// }
						// }
					// },
					 // des_detial: {
							// validators: {
							// notEmpty: {
								// message: '优惠券说明不能为空'
							// }
						// }
					// }
				// }
			// });
			<if condition="!isset($coupon)">
			cate_get($('#cate_name').val());
			</if>
			$('select[name="cate_name"]').change(function(event) {
				var order_type=$(this).val();
				
				cate_get(order_type)
				
			});
			
				
			$('#tab2').hide();
			$('.select_tab').click(function(){
				
				$('.select_tab').removeClass('select');
				$(this).addClass('select');
				var id_for = $(this).attr('for');
				if(id_for=='tab1'){
					// $('#sysc_weixin').val(0);
					$('#tab2').hide();
					//$('.vipcard_box').show();
					
				}else{
					$('#tab1').hide();
					//$('.vipcard_box').hide();
					// $('#sysc_weixin').val(1);
				}
			
				$('#'+id_for).show();
				//$('#b04').hide();
				//$('#pwd_bg').hide();
				
			});
			if($('.plus').length<2){
				$('.delete').children().hide();
			}
				
				
		$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看示例',
				padding: 0,
				width: 680,
				height: 620,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});
			
			$('select[name="color"]').css('background-color','#63b359');	
			$('select[name="color"]').change(function(event) {
				$('#wx_color').css('background-color',$('select[name="color"]').find('option:selected').html());
				$(this).css('background-color',$('select[name="color"]').find('option:selected').html());
			});		

		<if condition="isset($coupon)">
		$('#tab2 input ').attr('disabled','disabled')
		$('#tab2 select ').attr('disabled','disabled')
		$('#tab2 textarea ').attr('disabled','disabled')
		$('#tab2 input ').attr('readonly',true)	
		</if>
			
		});
		
		function plus(){
			var item = $('.plus:last');
			var newitem = $(item).clone(true);
			var No = parseInt(item.find(".tiplabel label").html())+1;
			$('.delete').children().show();
			if(No>4){
				// alert('不能超过4条信息');
				layer.msg('不能超过4条信息');
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
		var no_sync_wx = false;
		function confirm_coupon(){

			// if(!no_sync_wx){
				if($('input[name="brand_name"]').val()!='' && $('input[name="sync_wx"]:checked').val()==0){
					layer.confirm('确定不同步微信？', {
					  btn: ['我要同步','不同步'] //按钮
					}, function(index){
						$("input[name='sync_wx'][value=0]").attr("checked",false); 
						$("input[name='sync_wx'][value=1]").attr("checked","checked"); 
						 layer.msg('已为您更改为同步微信卡包并提交', {icon: 1,time:2000});
						  layer.close(index);
						 var t=setTimeout("$('form').submit()",2000);
					}, function(index){
						// no_sync_wx=true;
						 layer.close(index);
						 $('form').submit();
					});
					
				}else{
					$('form').submit();
				}
			// }else{
				// $('form').submit();
			// }
		}
		
		function addLinks(domid,iskeyword){
			art.dialog.data('domid', domid);
			art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
		}
		// function upload_func(){
			// $('#img').attr('src',$('#bgs').val());
		// }
	</script>
	<link rel="stylesheet" href="{pigcms{$static_path}css/card_new.css"/>
	<style>
		#tab1 .tiplabel {
			margin-left: 20px;
			margin-right: 20px;
			min-width: 200px;!important
		}
	</style>
<include file="Public:footer"/>
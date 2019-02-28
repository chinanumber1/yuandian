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
			<li class="active">编辑优惠券</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post" action="{pigcms{:U('Card_new/edit_coupon')}">
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">优惠券名称</label></label>
									<input type="text" class="col-sm-3" name="name" value="{pigcms{$coupon.name}" />
								</div>
								
								<div class="form-group" style="margin-bottom:-35px;">
									<label class="col-sm-3"><label for="AutoreplySystem_img">优惠券展示图片</label></label>
								</div>
									
								<div class="form-group" style="width:417px;padding-left:140px;">
									<label class="ace-file-input">
										<input class="col-sm-4" id="ace-file-input" size="50" onchange="preview1(this,'img')" name="img" type="file">
										<span class="ace-file-container" data-title="选择">
											<span class="ace-file-name" data-title="上传图片..."><i class=" ace-icon fa fa-upload"></i></span>
										</span>
									</label>
									<div id="flash_preview1"><img style="width:100px;height:50px" id="img" src="{pigcms{$coupon.img}"></div>
								</div>
								
								<div class="form-group" style="margin-bottom:-35px;">
									<label class="col-sm-3"><label for="AutoreplySystem_img">指定店铺</label></label>
								</div>
								<div class="form-group" style="width:417px;padding-left:140px;">
									
									<volist name="store_list" id="vo">
										<input type="checkbox"  name="store_id[]" class="input input-image" value="{pigcms{$vo.store_id}" <if condition="in_array($vo['store_id'],$coupon['store_id'])">checked="checked"</if>>{pigcms{$vo.name}
										<br>
									</volist>
										<font color="red">(商家优惠券不参与同店铺的对账)</font>
								</div>
								
								
								<div class="form-group">
									<label class="col-sm-1">是否与商家会员卡优惠同时使用</label>
									<select name="use_with_card" >
										<option value="0" <if condition="$coupon.use_with_card eq '0'">selected = 'selected'</if>>否</option>
										<option value="1" <if condition="$coupon.use_with_card eq '1'">selected = 'selected'</if>>是</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1">是否领卡时自动领取</label>
									<select name="auto_get" >
										<option value="0" <if condition="$coupon.auto_get eq '0'">selected = 'selected'</if>>否</option>
										<option value="1" <if condition="$coupon.auto_get eq '1'">selected = 'selected'</if>>是</option>
									</select>
								</div>
							
								<div class="form-group">
									<label class="col-sm-1">是否只允许新用户领取</label>
									<select name="allow_new" >
										<option value="0"  <if condition="$coupon.allow_new eq '0'">selected = 'selected'</if>>否</option>
										<option value="1"  <if condition="$coupon.allow_new eq '1'">selected = 'selected'</if>>是</option>
									</select>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">使用平台</label>
									{pigcms{$coupon.platform}
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">使用类别</label>
									{pigcms{$coupon.cate_name}
								</div>
								
								<div class="form-group">
									<label class="col-sm-1" >使用分类</label>
									<div id="cate_id">
									{pigcms{$coupon.cate_id}
									</div>
								</div>
								
							
								
								<div class="form-group">
									<label class="col-sm-1" >展示简短描述</label>
									<textarea name="des" cols="56" rows="8" value=""  autocomplete="off" validate="required:true,maxlength:30">{pigcms{$coupon.des}</textarea>(微信卡券优惠说明)
								</div>
								
								<div class="form-group">
									<label class="col-sm-1" >使用说明</label>
									<textarea name="des_detial" cols="56" rows="8"  value=""  autocomplete="off" validate="required:true">{pigcms{$coupon.des_detial}</textarea>
									<span class="form_tips">每条描述请换行,微信卡券使用须知</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">数量{pigcms{$coupon.now_num}</label></label>
									<select name="add" class="fl" style="float:left;height:34px" >
										<option value="0">增加</option>
										<option value="1">减少</option>
									</select>
								
									<input type="text" class="col-sm-3" name="num_add" value=""  style="margin-left:10px;width:21%"/>
									已经被领了{pigcms{$coupon.had_pull}张,微信卡券将同步更新
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">领取数量限制</label></label>
									<input type="text" class="col-sm-3" name="limit" value="{pigcms{$coupon.limit}" disabled="disabled" />
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">使用数量限制</label></label>
									<input type="text" class="col-sm-3" name="use_limit"  value="{pigcms{$coupon.use_limit}" disabled="disabled" />
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">优惠金额</label></label>
									<input type="text" class="col-sm-3" name="discount"  disabled="disabled" value="{pigcms{$coupon.discount}" />
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">最小订单金额</label></label>
									<input type="text" class="col-sm-3" name="order_money" disabled="disabled" value="{pigcms{$coupon.order_money}" />
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">起始时间</label></label>
									<input type="text" class="input-text" name="start_time" style="width:120px;" id="d4311" disabled="disabled" value="{pigcms{$coupon.start_time|date='Y-m-d',###}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>-
									<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" disabled="disabled" value="{pigcms{$coupon.end_time|date='Y-m-d',###}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" />
								</div>
						
								
								<div class="form-group">
									<label class="col-sm-1">状态</label>
									<select name="status" >
										<option value="0" <if condition="$coupon.status eq '0'">selected = 'selected'</if>>禁止</option>
										<option value="1" <if condition="$coupon.status eq '1'">selected = 'selected'</if>>正常</option>
									</select>
								</div>
							
								<input type="hidden" name="coupon_id" value="{pigcms{$coupon.coupon_id}" >		
								<input type="hidden" name="is_wx_card" value="{pigcms{$coupon.is_wx_card}" >		
								<input type="hidden" name="wx_cardid" value="{pigcms{$coupon.wx_cardid}" >		
								<input type="hidden" name="num" value="{pigcms{$coupon.num}" >		
								<div class="clearfix form-actions">
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="submit">
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
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>

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
	 
		$(document).ready(function() {
			var post_url = "{pigcms{:U('Card_new/ajax_ordertype_cateid')}";
				$('select[name="cate_name"]').change(function(event) {
					var order_type=$(this).val();
					if(order_type!='all'){
						
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
						$('#cate_id').empty();
					}
					
					
				});
			
		});
		
	</script>
<include file="Public:footer"/>
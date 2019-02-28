<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('index')}">酒店管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('son_cat_list',array('cat_id'=>$now_cat['cat_id']))}">{pigcms{$now_cat.cat_name} - 子类别列表</a></li>
			<li class="active">添加房型类别</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">				
							<li class="active">
								<a href="{pigcms{:U('son_category_add',array('cat_id'=>$now_cat['cat_id']))}">添加房型类别</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post">
								<div class="form-group">
									<label class="col-sm-1"><label for="cat_name">类别名称</label></label>
									<input class="col-sm-2" size="20" name="cat_name" id="cat_name" type="text" value="{pigcms{$_POST.cat_name}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="cat_sort">类别排序</label></label>
									<input class="col-sm-1" size="10" name="cat_sort" id="cat_sort" type="text" value="{pigcms{$_POST.cat_sort|default='0'}"/>
									<span class="form_tips">默认添加顺序排序！手动调值，数值越大，排序越前</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="enter_time">入住时间</label></label>
									<input class="col-sm-1" size="10" name="enter_time" id="enter_time" type="text" value="{pigcms{$_POST.enter_time|default='12:00'}" readonly="readonly" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'HH:mm'})"/>
									<span class="form_tips">每日用户入住的时间</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="has_receipt">支持发票</label></label>
									<label class="col-sm-2 cb-box">
										<span class="cb-enable">
											<label class="cb-enable <if condition="$_POST['has_receipt'] eq 1">selected</if>">
												<span>支持</span>
												<input type="radio" name="has_receipt" value="1" <if condition="$_POST['has_receipt'] eq 1">checked="checked"</if>/>
											</label>
										</span>
										<span class="cb-disable">
											<label class="cb-disable <if condition="$_POST['has_receipt'] eq 0">selected</if>">
												<span>不支持</span>
												<input type="radio" name="has_receipt" value="0" <if condition="$_POST['has_receipt'] eq 0">checked="checked"</if>/>
											</label>
										</span>
									</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>退订政策</label></label>
									<select name="has_refund" id="has_refund">
										<option value="0" <if condition="$_POST['has_refund'] eq 0">selected="selected"</if>>任意退</option>
										<option value="1" <if condition="$_POST['has_refund'] eq 1">selected="selected"</if>>不能退</option>
										<option value="2" <if condition="$_POST['has_refund'] eq 2">selected="selected"</if>>入住前规定时间内能退</option>
									</select>
								</div>
								<div class="form-group" id="refund_hour_box" <if condition="$_POST['has_refund'] neq 2">style="display:none;"</if>>
									<label class="col-sm-1"><label for="refund_hour">退订规定时间</label></label>
									<input class="col-sm-1" size="10" name="refund_hour" id="refund_hour" type="text" value="{pigcms{$_POST.refund_hour}"/>
									<span class="form_tips">以小时为单位，入住前几个小时能退订</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="code">商家编码</label></label>
									<input class="col-sm-1" size="10" name="code" id="code" type="text" value="{pigcms{$_POST.code}"/>
									<span class="form_tips">若无编码，可不填写</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="book_day">最长预订天数</label></label>
									<input class="col-sm-1" size="10" name="book_day" id="book_day" type="text" value="{pigcms{$_POST.book_day}"/>
									<span class="form_tips">设置为0，则不限制时间。目前系统支持最长能设置180天的价格</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="discount_room">优惠价房间数</label></label>
									<input class="col-sm-1" size="10" name="discount_room" id="discount_room" type="text" value="{pigcms{$_POST.discount_room}"/>
									<span class="form_tips">预订房间数量达到的将享受优惠价，设置为0则不享受。</span>
								</div>
								<div class="form-group" >
									<label class="col-sm-1">其他描述：</label>
									<textarea name="cat_info" id="cat_info" style="width:402px;height:120px;">{pigcms{$_POST.cat_info}</textarea>
									<span class="form_tips">例如：此房型需要注意的，无窗户等</span>
								</div>
								<if condition="$ok_tips">
									<div class="form-group" style="margin-left:0px;">
										<span style="color:blue;">{pigcms{$ok_tips}</span>				
									</div>
								</if>
								<if condition="$error_tips">
									<div class="form-group" style="margin-left:0px;">
										<span style="color:red;">{pigcms{$error_tips}</span>				
									</div>
								</if>
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
<script>
$(function(){
	$('form.form-horizontal').submit(function(){
		$(this).find('button[type="submit"]').html('保存中...').prop('disabled',true);
	});
	$('#has_refund').change(function(){
		if($(this).val() == 2){
			$('#refund_hour_box').show();
		}else{
			$('#refund_hour_box').hide();
		}
	});
	
	$('.cb-enable').click(function(){
		$(this).find('label').addClass('selected');
		$(this).find('label').find('input').prop('checked',true);
		$(this).next('.cb-disable').find('label').find('input').prop('checked',false);
		$(this).next('.cb-disable').find('label').removeClass('selected');
	});
	$('.cb-disable').click(function(){
		$(this).find('label').addClass('selected');
		$(this).find('label').find('input').prop('checked',true);
		$(this).prev('.cb-enable').find('label').find('input').prop('checked',false);
		$(this).prev('.cb-enable').find('label').removeClass('selected');
	});
});
</script>

<include file="Public:footer"/>

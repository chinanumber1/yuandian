<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{$config.shop_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Shop/goods_sort',array('store_id'=>$now_store['store_id']))}">{pigcms{$now_store.name}</a></li>
			<li class="active">添加分类</li>
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
								<a href="{pigcms{:U('Shop/sort_add',array('store_id'=>$now_store['store_id']))}">添加分类</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post">
							    <if condition="$sort">
								<div class="form-group">
									<label class="col-sm-1"><label for="sort_name">父分类名称</label></label>
									<input name="fid" type="hidden" value="{pigcms{$sort['sort_id']}"/>
									<input type="text" value="{pigcms{$sort.sort_name}" disabled/>
								</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort_name">分类名称</label></label>
									<input name="fid" type="hidden" value="{pigcms{$fid}"/>
									<input class="col-sm-2" size="20" name="sort_name" id="sort_name" type="text" value="{pigcms{$now_sort.sort_name}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">分类排序</label></label>
									<input class="col-sm-1" size="10" name="sort" id="sort" type="text" value="{pigcms{$now_sort.sort|default='0'}"/>
									<span class="form_tips">默认添加顺序排序！手动调值，数值越大，排序越前</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="is_weekshow">开启星期几显示</label>
									<select name="is_weekshow" id="is_weekshow">
										<option value="0" <if condition="$now_sort['is_weekshow'] eq 0">selected="selected"</if>>关闭</option>
										<option value="1" <if condition="$now_sort['is_weekshow'] eq 1">selected="selected"</if>>开启</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="FoodType_week">星期几显示</label>
									<div class="col-sm-10" style="margin-top:5px;">
										<div style="width:80px;float:left;font-size:16px;">
											<label><input type="checkbox" value="1" name="week[]" <if condition="in_array('1',$now_sort['week'])">checked="checked"</if>/>星期一</label>&nbsp;&nbsp;
										</div>
										<div style="width:80px;float:left;font-size:16px;">
											<label><input type="checkbox" value="2" name="week[]" <if condition="in_array('2',$now_sort['week'])">checked="checked"</if>/>星期二</label>&nbsp;&nbsp;
										</div>
										<div style="width:80px;float:left;font-size:16px;">
											<label><input type="checkbox" value="3" name="week[]" <if condition="in_array('3',$now_sort['week'])">checked="checked"</if>/>星期三</label>&nbsp;&nbsp;
										</div>
										<div style="width:80px;float:left;font-size:16px;">
											<label><input type="checkbox" value="4" name="week[]" <if condition="in_array('4',$now_sort['week'])">checked="checked"</if>/>星期四</label>&nbsp;&nbsp;
										</div>
										<div style="width:80px;float:left;font-size:16px;">
											<label><input type="checkbox" value="5" name="week[]" <if condition="in_array('5',$now_sort['week'])">checked="checked"</if>/>星期五</label>&nbsp;&nbsp;
										</div>
										<div style="width:80px;float:left;font-size:16px;">
											<label><input type="checkbox" value="6" name="week[]" <if condition="in_array('6',$now_sort['week'])">checked="checked"</if>/>星期六</label>&nbsp;&nbsp;
										</div>
										<div style="width:80px;float:left;font-size:16px;">
											<label><input type="checkbox" value="0" name="week[]" <if condition="in_array('0',$now_sort['week'])">checked="checked"</if>/>星期日</label>&nbsp;&nbsp;
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">分类下产品折扣率</label></label>
									<input class="col-sm-1" size="10" name="sort_discount" id="sort_discount" type="text" value="{pigcms{$now_sort.sort_discount|default='0'}"/>
									<span class="form_tips" style="color:red">0~10之间的数字，支持一位小数！8代表8折，8.5代表85折，0与10代表无折扣</span>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="unit">分类图片</label></label>
									<span class="col-sm-2" style="padding-left:0px;">
										<input id="ytimage-file" type="hidden" value="" name="image"/>
										<input class="col-sm-1" id="image-file" size="200" onchange="previewimage(this)" name="image" type="file"/>
									</span>
									<span class="form_tips" style="color:red;">可不填。（图片文件大小不能超过{pigcms{$config.meal_pic_size}M,建议上传大尺寸的图片。） 图片宽度建议为50px，高度建议为50px</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">选择图片</label>
									<a href="#modal-table" class="btn btn-sm btn-success" onclick="selectImg('image_preview_box','goods_sort')">选择图片</a>
								</div-->
								<div id="image_preview_box"></div>

								<if condition="$print_list AND empty($sort)">
								<div class="form-group">
									<label class="col-sm-1" for="Food_status">归属打印机</label>
									<select name="print_id" id="print_id">
										<option value="0" selected>选择打印机</option>
										<volist name="print_list" id="print">
										<option value="{pigcms{$print['pigcms_id']}" <if condition="$print['pigcms_id'] eq $now_sort['print_id']">selected</if>>{pigcms{$print['name']}</option>
										</volist>
									</select>
									<span class="form_tips" style="color:red;"> 选择归属打印机，则该打印机将打印该分类的小票；不选择打印机，则该打印机不会打印小票</span>
								</div>
								</if>
								
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
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script>
$(function(){
	/*调整保存按钮的位置*/
	$(".nav-tabs li a").click(function(){
		if($(this).attr("href")=="#imgcontent"){		//店铺图片
			$(".form-submit-btn").css('position','absolute');
			$(".form-submit-btn").css('top','670px');	
		}else{
			$(".form-submit-btn").css('position','static');
		}
	});

	$('form.form-horizontal').submit(function(){
		$(this).find('button[type="submit"]').html('保存中...').prop('disabled',true);
	});
	/*分享图片*/
	$('#image-file').ace_file_input({
		no_file:'gif|png|jpg|jpeg格式',
		btn_choose:'选择',
		btn_change:'重新选择',
		no_icon:'fa fa-upload',
		icon_remove:'',
		droppable:false,
		onchange:null,
		remove:false,
		thumbnail:false
	});
});

function previewimage(input){
	if (input.files && input.files[0]){
		var reader = new FileReader();
		reader.onload = function (e) {$('#image_preview_box').html('<img style="width:120px;height:120px" src="'+e.target.result+'" alt="图片预览" title="图片预览"/>');}
		reader.readAsDataURL(input.files[0]);
	}
}
</script>

<include file="Public:footer"/>

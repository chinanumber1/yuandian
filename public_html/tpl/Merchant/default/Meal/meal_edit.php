<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Meal/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Meal/meal_sort',array('store_id'=>$now_store['store_id']))}">分类列表</a></li>
			<li class="active"><a href="{pigcms{:U('Meal/meal_list',array('sort_id'=>$now_sort['sort_id']))}">{pigcms{$now_sort.sort_name}</a></li>
			<li class="active">编辑商品 - 【{pigcms{$now_meal.name}】</li>
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
								<a href="{pigcms{:U('Meal/meal_edit',array('meal_id'=>$now_meal['meal_id']))}">编辑商品</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post">
								<if condition="$error_tips">
									<div class="alert alert-danger">
										<p>请更正下列输入错误:</p>
										<p>{pigcms{$error_tips}</p>
									</div>
								</if>
								<if condition="$ok_tips">
									<div class="alert alert-info">
										<p>{pigcms{$ok_tips}</p>				
									</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">商品名称</label></label>
									<input class="col-sm-1" size="20" name="name" id="name" type="text" value="{pigcms{$now_meal.name}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="unit">商品单位</label></label>
									<input class="col-sm-1" size="20" name="unit" id="unit" type="text" value="{pigcms{$now_meal.unit}"/>
									<span class="form_tips">必填。如个、斤、份</span>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="label">商品标签</label></label>
									<input class="col-sm-1" size="20" name="label" id="label" type="text" value="{pigcms{$now_meal.label}"/>
									<span class="form_tips">可不填。如特价、促销、招牌！多个以空格分隔，包括空格最长10位！</span>
								</div-->
								<div class="form-group">
									<label class="col-sm-1"><label for="price">商品价格</label></label>
									<input class="col-sm-1" size="20" name="price" id="price" type="text" value="{pigcms{$now_meal.price}"/>
									<span class="form_tips">必填。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="price">商品库存</label></label>
									<input class="col-sm-1" size="20" name="stock_num" id="stock_num" type="text" value="{pigcms{$now_meal.stock_num}"/>
									<span class="form_tips">0表示无限量。</span>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="old_price">商品原价</label></label>
									<input class="col-sm-1" size="20" name="old_price" id="old_price" type="text" value="{pigcms{$now_meal.old_price}"/>
									<span class="form_tips">价格的单位为元，可以设定为小数，最多两位小数，下同。原价可不填。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="vip_price">会员特定价</label></label>
									<input class="col-sm-1" size="20" name="vip_price" id="vip_price" type="text" value="{pigcms{$now_meal.vip_price}"/>
									<span class="form_tips">可不填。如果设定此值，则所有等级的会员都按此价执行。</span>
								</div-->
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">商品排序</label></label>
									<input class="col-sm-1" size="10" name="sort" id="sort" type="text" value="{pigcms{$now_meal.sort|default='0'}"/>
									<span class="form_tips">默认添加顺序排序！手动调值，数值越大，排序越前</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="Food_status">商品状态</label>
									<select name="status" id="Food_status">
										<option value="1" <if condition="$now_meal['status'] eq 1">selected="selected"</if>>正常</option>
										<option value="0" <if condition="$now_meal['status'] eq 0">selected="selected"</if>>停售</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="unit">商品图片</label></label>
									<span class="col-sm-2" style="padding-left:0px;">
										<input id="ytimage-file" type="hidden" value="" name="image"/>
										<input class="col-sm-1" id="image-file" size="200" onchange="previewimage(this)" name="image" type="file"/>
									</span>
									<span class="form_tips" style="color:red;">可不填。（图片文件大小不能超过{pigcms{$config.meal_pic_size}M,建议上传大尺寸的图片。） 图片宽度建议为195px，高度建议为146px</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">选择图片</label>
									<a href="#modal-table" class="btn btn-sm btn-success" onclick="selectImg('image_preview_box','meal')">选择图片</a>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="unit">商品图片</label></label>
									<span id="image_preview_box">
										<if condition="$now_meal['see_image']">
											<img src="{pigcms{$now_meal.see_image}" style="width:120px;height:120px"/>
										</if>
									</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="unit">商品描述</label></label>
									<textarea class="col-sm-3" rows="5" maxlength="300" name="des" id="des">{pigcms{$now_meal.des}</textarea>
								</div>
								
								<if condition="$print_list">
								<div class="form-group">
									<label class="col-sm-1" for="Food_status">归属打印机</label>
									<select name="print_id" id="print_id">
										<option value="0" <if condition="$now_meal['print_id'] eq 0">selected</if>>选择打印机</option>
										<volist name="print_list" id="print">
										<option value="{pigcms{$print['pigcms_id']}" <if condition="$now_meal['print_id'] eq $print['pigcms_id']">selected</if>>{pigcms{$print['name']}</option>
										</volist>
									</select>
									<span class="form_tips" style="color:red;">如果选择了一台非主打印机的话，那么客户在下单的时候选择的打印机和主打印机同时打印，如果不选打印机或是选择了主打印机的话，那么就主打印机打印</span>
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
	$('form.form-horizontal').submit(function(){
		$(this).find('button[type="submit"]').html('保存中...').prop('disabled',true);
	});
	
	/*调整保存按钮的位置*/
	$(".nav-tabs li a").click(function(){
		if($(this).attr("href")=="#imgcontent"){		//店铺图片
			$(".form-submit-btn").css('position','absolute');
			$(".form-submit-btn").css('top','670px');	
		}else{
			$(".form-submit-btn").css('position','static');
		}
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
		reader.onload = function (e) {$('#image_preview_box').html('<img style="width:120px;height:120px" src="'+e.target.result+'" alt="图片预览" title="图片预览"/>&nbsp;&nbsp;&nbsp;&nbsp;您需要先保存修改，图片才会变更。');}
		reader.readAsDataURL(input.files[0]);
	}
}
</script>
<include file="Public:footer"/>

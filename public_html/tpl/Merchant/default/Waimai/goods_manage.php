<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Waimai/index')}">{pigcms{$config.waimai_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Waimai/index')}">店铺管理</a></li>
			<li class="active"><if condition="!$goodInfo['store_id']">新建<else />修改</if>商品</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" action="{pigcms{:U('Waimai/save_goods')}">
						<input type='hidden' value='{pigcms{$goodInfo["goods_id"]}' name='goods_id'>
						<input type='hidden' value='{pigcms{$store_id}' name='store_id'>
						<input type='hidden' value='' name='goods_catids'>
						<input type='hidden' value='' name='storeIdStr' id='js-storeIdStr'>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="cyname">名称</label></label>
									<input class="col-sm-2" size="30" name="goodname" id="js-name" value="{pigcms{$goodInfo['name']}" type="text" jstips='请输入产品名'/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="cyname">单位</label></label>
									<input class="col-sm-2" size="30" name="unit" id="js-unit" value="<if condition="$goodInfo['unit']">{pigcms{$goodInfo['unit']}<else/>份</if>" type="text" jstips='请输入单位'/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>选择分类</label></label>
									<volist name="categoryList['category_list']" id="vo">
									<input type='radio' value="{pigcms{$vo['gcat_id']}" name="gcat_id" <if condition=" $goodInfo['gcat_id'] eq $vo['gcat_id'] || !$goodInfo['gcat_id']">checked</if> jstips='请添加商品分类'> {pigcms{$vo['gcat_name']} &nbsp;
									</volist>
								</div>
								<div class="form-group">
										<label class="col-sm-1"><label>排序</label></label>
										<input class="col-sm-2" size="20" name="cat_sort" id="js-sort" type="text" value="<if condition="$goodInfo['sort']">{pigcms{$goodInfo['sort']}<else />0</if>" onkeyup="value=value.replace(/[^1234567890]+/g,'')" jstips='请输入排序值'/>
								</div>
								<div class="form-group">
										<label class="col-sm-1"><label for="iswrite">状态</label></label>
										<span><label><span>开启</span><input id='iswrite' name="iswrite" <if condition="$goodInfo['status'] eq '1' || !$goodInfo['status']">checked="checked"</if> value="1" type="radio"></label></span>
										<span><label><span>关闭</span><input id='iswrite' name="iswrite" <if condition="$goodInfo['status'] eq '0'">checked="checked"</if> value="0" type="radio" ></label></span>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="cyname">原价</label></label>
									<input class="col-sm-2" size="30" name="old_price" id="js-old_price" value="<if condition="$goodInfo['old_price']">{pigcms{$goodInfo['old_price']}<else/>0</if>" type="text" jstips='请输入原价'/>
								</div-->
								<div class="form-group">
									<label class="col-sm-1">价格</label>
									<input class="col-sm-2" size="30" name="price" id="js-price" value="<if condition="$goodInfo['price']">{pigcms{$goodInfo['price']}<else/>0</if>" type="text" jstips='请输入外卖价格'/>
									<span class="form_tips">元（必填）</span>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1">VIP价格</label>
									<input class="col-sm-2" size="30" name="vip_price" id="js-vip_price" value="<if condition="$goodInfo['vip_price']">{pigcms{$goodInfo['vip_price']}<else/>0</if>" type="text" jstips='请输入VIP价格'/>
									<span class="form_tips">会员价</span>
								</div-->
								<if condition="$storeInfo['tools_money_have'] eq '1'">
								<div class="form-group">
									<label class="col-sm-1">餐盒费</label>
									<input class="col-sm-2" size="30" name="tools_price" id="js-tools_price" value="<if condition="$goodInfo['tools_price']">{pigcms{$goodInfo['tools_price']}<else/>0</if>" type="text" jstips='请输入餐盒费'/>
									<span class="form_tips">本商品所需的餐盒费（元）</span>
								</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1">商品图片</label>
									<a id="J_selectImage" class="btn btn-sm btn-success" href="javascript:void(0)" >上传图片</a>
									<!--span class="form_tips">第一张将作为主图片！最多上传10个图片！图片宽度建议为700px，高度建议为420px。</span-->
									<span class="form_tips">图片宽度建议为700px，高度建议为420px。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											<volist name="goodInfo['pic']" id="vo">
												<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
											</volist>
										</ul>
									</div>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1">描述</label>
									<textarea name="desc" rows="5" class="col-sm-5" jstips='请输入描述' id="js-desc"><if condition="$goodInfo['desc']">{pigcms{$goodInfo['desc']}</if></textarea>
								</div-->
								<div class="form-group">
									<label class="col-sm-1">销售量</label>
									<label class="col-sm-1"><if condition="$goodInfo['sell_count']">{pigcms{$goodInfo['sell_count']}<else />0</if></label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">推荐量</label>
									<label class="col-sm-1"><if condition="$goodInfo['digg_count']">{pigcms{$goodInfo['digg_count']}<else />0</if></label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">每日限量</label>
									<input class="col-sm-2" size="30" name="daylimit" id="js-limit" value="<if condition="$goodInfo['limit']">{pigcms{$goodInfo['limit']}<else />0</if>" type="text" jstips='请输入每日限制数'/>
									<span class="form_tips">每天商品限制量</span>
								</div>
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" onclick="$(form).submit();return false;">
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
.BMap_cpyCtrl{display:none;}
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
#upload_pic_box img{width:100px;height:70px;border:1px solid #ccc;}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K){
	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 1){
			alert('最多上传1张图片！');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('Waimai/store_ajax_upload_pic')}";
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
});
function deleteImage(path,obj){
	$.post("{pigcms{:U('Waimai/store_ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
</script>
<script>

$("form").submit(function(){
	return checkForm();
});
function checkForm(){
	var nameObj = $('#js-name');
	var unitObj = $('#js-unit');
	var sortObj = $('#js-sort');
	var priceObj = $('#js-price');
	var descObj = $('#js-desc');
	
	if(!checkLength(nameObj) || !checkLength(unitObj) || !checkLength(sortObj) || !checkLength(priceObj) || !checkLength(descObj)){
		return false;
	}
	var i = 0;
	$('input[name="gcat_id"]:checked').each(function(){
		i++;
	})
	if(i==0){
		alert('请选择商品分类!');
		return false;
	}
	return  true;
}
function checkLength(obj){
	var objValue = $.trim(obj.val());
	if(objValue.length == 0){
		alert(obj.attr('jstips'))
		return false;
	}
	return true;
}
</script>
<include file="Public:footer"/>
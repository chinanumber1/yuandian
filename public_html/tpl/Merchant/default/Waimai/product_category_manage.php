<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Waimai/product_category')}">{pigcms{$config.waimai_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Waimai/product_category')}">商家分类管理</a></li>
			<li class="active"><if condition="!$categoryDetail['store_id']">新建<else />修改</if>分类</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
		  <!--<p class="red" style="font-size: 18px;">温馨提示：暂无店铺 请去添加店铺</p>-->
			<div class="row">
				<div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post">
						<input type='hidden' value='' name='storeIdStr' id='js-storeIdStr'>
						<input type='hidden' value='{pigcms{$gcat_id}' name='gcat_id'>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="cyname">名称</label></label>
									<input class="col-sm-2" size="30" name="name" id="js-name" value="{pigcms{$categoryDetail['gcat_name']}" type="text" jstips='请输入类型名'/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="cyname">拼音</label></label>
									<input class="col-sm-2" size="30" name="pinyin" id="js-pinyin" value="{pigcms{$categoryDetail['gcat_pinyin']}" type="text" jstips='请输入拼音'/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>选择店铺</label></label>
									<volist name="storeList" id="vo">
									<if condition=" $categoryDetail['store_id'] eq $vo['store_id'] || !$categoryDetail['store_id']">
									<input type='checkbox' value="{pigcms{$vo['store_id']}" name="store_ids" 
										<if condition=" $categoryDetail['store_id'] eq $vo['store_id'] || !$categoryDetail['store_id']">checked</if>> {pigcms{$vo['name']} &nbsp;
										</if>
									</volist>
								</div>
								<div class="form-group">
										<label class="col-sm-1"><label>排序</label></label>
										<input class="col-sm-2" size="20" name="cat_sort" id="js-sort" type="text" value="<if condition="$categoryDetail['gcat_sort']">{pigcms{$categoryDetail['gcat_sort']}<else />0</if>" onkeyup="value=value.replace(/[^1234567890]+/g,'')" jstips='请输入排序值'/>
								</div>
								<div class="form-group">
										<label class="col-sm-1"><label for="iswrite">状态</label></label>
										<span><label><span>开启</span><input id='iswrite' name="iswrite" <if condition="$categoryDetail['gcat_status'] eq '1' || !$categoryDetail['gcat_status']">checked="checked"</if> value="1" type="radio"></label></span>
										<span><label><span>关闭</span><input id='iswrite' name="iswrite" <if condition="$categoryDetail['gcat_status'] eq '0'">checked="checked"</if> value="0" type="radio" ></label></span>
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
<script>

$("form").submit(function(){
	return checkForm();
});
function checkForm(){
	
	var nameObj = $('#js-name');
	var pinyinObj = $('#js-pinyin');
	var sortObj = $('#js-sort');
	if(!checkLength(nameObj) || !checkLength(pinyinObj) || !checkLength(sortObj)){
		return false;
	}
	var i=0;
	var storeIdStr = '';
	$('input[name=store_ids]:checked').each(function(){
		i++;
		storeIdStr += ','+$(this).val();
	})
	if(i==0){
		alert('请选择店铺!');
		return false;
	}else{
		$('#js-storeIdStr').val(storeIdStr);
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
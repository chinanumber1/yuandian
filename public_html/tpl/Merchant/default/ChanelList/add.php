<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-qrcode"></i>
				<a href="{pigcms{:U('Promote/index')}">商家推广</a>
			</li>
			<li class="active"><a href="{pigcms{:U('ChanelList/index')}">渠道二维码消息列表</a></li>
			<li class="active"><if condition="ACTION_NAME  eq 'add'">添加渠道消息<elseif condition="ACTION_NAME eq 'edit'" />编辑渠道消息</if></li>
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
							<if condition="ACTION_NAME  eq 'add'"><a href="{pigcms{:U('ChanelList/add')}">添加渠道消息</a><elseif condition="ACTION_NAME eq 'edit'" /><a href="{pigcms{:U('ChanelList/edit')}">编辑渠道消息</a></if>
								</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post">
								<div style="margin-bottom:20px;">标记标题：<input type="text" name="Full_title" value="{pigcms{$Full_title}" style="width:300px;"></div>
								<input type="hidden" name="chanel_id" value="{pigcms{$_GET['chanel_id']}">
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
						<table class="table table-striped table-bordered table-hover" >
							<thead>
								<tr>
									<th >编号</th>
									<th >小标题</th>
									<th>图片</th>
									<th>描述</th>
									<th>链接</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$chanel_content">
									<volist name="chanel_content" id="vo">
										<tr class="odd">
											<input type="hidden" name="id[]" value="{pigcms{$vo.id}"/>
											<td ><label>{pigcms{$i}</label></td>
											<td><input style="width:150px;"  size="20" name="title[]" id="name" type="text" value="{pigcms{$vo.title}"/></td>
											<td>
												<input type="text"  style="width:200px;" name="img[]" id="img{pigcms{$i}" class="input input-image" value="{pigcms{$vo.img}" readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a>
												&nbsp;&nbsp;<a href="#modal-table" id="selectImg" class="btn btn-sm btn-success" onclick="selectImg('img{pigcms{$i}','chanel')">选择图片</a>
											</td>
											<td><textarea  maxlength="300" name="des[]" id="des" style="margin: 0px; width: 300px; height: 50px;">{pigcms{$vo.des}</textarea></td>
											<td><input type="text"  style="width:200px;" class="input" name="url[]" id="url{pigcms{$i}" value="{pigcms{$vo.url}"/>&nbsp;&nbsp;<a href="" id="addLink" class="btn btn-sm btn-success" onclick="addLinks('url{pigcms{$i}',0)" data-toggle="modal">从功能库选择</a></td>
											<td class="delete">
												<a href="javascript:void(0)" onclick="del(this)">[删除]</a>
											</td>
										</tr>
									</volist>
										<tr>
											<td align="center" colspan="6"><a  href="javascript:void(0)" onclick="plus()"><img style="width:30px;height:30px;" src="{pigcms{$static_path}images/plus.jpg"/></a></td>
										</tr>
									
								
								<else />
								
									<tr class="odd">
										<td ><label>1</label></td>
										<td><input style="width:200px;" size="20" name="title[]" id="name" type="text" value=""/></td>
										<td>
											<input type="text"  style="width:150px;" name="img[]" id="img1" class="input input-image" value="" readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a>
											&nbsp;&nbsp;<a href="#modal-table" id="selectImg" class="btn btn-sm btn-success" onclick="selectImg('img1','chanel')">选择图片</a>
										</td>
										<td><textarea   maxlength="300" name="des[]" id="des" style="margin: 0px; width: 300px; height: 50px;">{pigcms{$now_meal.des}</textarea></td>
										<td><input type="text"  style="width:200px;" class="input" name="url[]" id="url" value=""/>&nbsp;&nbsp;<a href="" id="addLink" class="btn btn-sm btn-success" onclick="addLinks('url',0)" data-toggle="modal">从功能库选择</a></td>
										<td class="delete">
											<a href="javascript:void(0)" onclick="del(this)">[删除]</a>
										</td>
									</tr>
									<tr>
										
										<td align="center" colspan="6"><a  href="javascript:void(0)" onclick="plus()"><img style="width:30px;height:30px;" src="{pigcms{$static_path}images/plus.jpg"/></a></td>
									</tr>
								</if>
							</tbody>
						</table>
							
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
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>

<script type="text/javascript">
	KindEditor.ready(function(K){
			var site_url = "{pigcms{$config.site_url}";
			var editor = K.editor({
				allowFileManager : true
			});
			$('.J_selectImage').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
				editor.loadPlugin('image', function(){
					editor.plugin.imageDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							upload_file_btn.siblings('.input-image').val(site_url+url);
							editor.hideDialog();
						}
					});
				});
			});

		});
</script>
<script>
$(document).ready(function() {
	if($('.odd').length<=1){
		$('.delete').children('a').hide();
	}
});
$(function(){
	
	/*调整保存按钮的位置*/
	$(".nav-tabs li a").click(function(){
		if($(this).attr("href")=="#imgcontent"){		//店铺图片
			$(".form-submit-btn").css('position','absolute');
			$(".form-submit-btn").css('top','670px');	
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
function plus(){
	var item = $('.odd:last');
	var item_id = item.find('input[name="img[]"]').attr('id');
	item_id = item_id.match(/\d+/ig);
	var newitem = $(item).clone(true);
	var No = parseInt(item.find("label").html())+1;  
	$('.delete').children().show();
	if(No>10){
		alert('不能超过10条信息');
	}else{
		$(item).after(newitem);
		newitem.find('input').attr('value','');
		newitem.find('input[name="img[]"]').attr('id','img'+No);
		newitem.find('textarea[name="des[]"]').attr('value','');
		newitem.find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
		newitem.find("#selectImg").attr('onclick',"selectImg('img"+No+"','chanel')");
		newitem.find("label").html(No);
		newitem.find('input[name="url[]"]').attr('id','url'+No);
		newitem.find('.delete').children().show();
	}
}
function del(obj){
		
	if($('.odd').length<=1){
		$('.delete').children().hide();
	}else{
		if(confirm('确定删除吗?')){
			var id = $(obj).parents('tr').find('input[name="id[]"]').val();
			if(typeof(id) != "undefined"&&id!=''){
				$.get("/merchant.php?g=System&c=ChanelList&a=del&delete_content="+id, function(data) {});
			}
			if($('.odd').length==2){
				$('.delete').children().hide();
			}
			$(obj).parents('.odd').remove();
			$.each($('.odd'), function(index, val) {
				var No =index+1;
				$(val).find('label').html(No);
				$(val).find('input[name="url[]"]').attr('id','url'+No);
				$(val).find('input[name="img[]"]').attr('id','img'+No);
				$(val).find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
				$(val).find("#selectImg").attr('onclick',"selectImg('img"+No+"','chanel')");
			});
		}
	}
}
function previewimage(input){
	if (input.files && input.files[0]){
		var reader = new FileReader();
		reader.onload = function (e) {$('#image_preview_box').html('<img style="width:120px;height:120px" src="'+e.target.result+'" alt="图片预览" title="图片预览"/>');}
		reader.readAsDataURL(input.files[0]);
	}
}
function addLinks(domid,iskeyword){
	art.dialog.data('domid', domid);
	art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
}
</script>
<include file="Public:footer"/>

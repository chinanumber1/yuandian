<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-qrcode"></i>
				<a href="{pigcms{:U('Promote/index')}">商家推广</a>
			</li>
			<li class="active">二维码回复内容自定义</li>
			
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
				
					<div class="tab-content">
						<div class="grid-view">
						<form enctype="multipart/form-data" class="form-horizontal" method="post">
							
								
							
						<table class="table table-striped table-bordered table-hover" >
							<thead>
								<tr>
									<th >编号</th>
									<th >内容</th>
									
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="!empty($content) AND !empty($content['title'])">
									<volist name="content['title']" id="vo">
								
										<tr class="odd">
											<td ><label>{pigcms{$i}</label></td>
											<td class="content">
												<a href="" id="addLink" class="btn btn-sm btn-success" onclick="addLinks('url{pigcms{$i}',0)" data-toggle="modal">从功能库选择</a>
												<input style="width:200px;" size="20" name="title[]" id="url{pigcms{$i}-title" type="text" value="{pigcms{$content['title'][$i-1]}" readonly />
												<input style="width:200px;" size="20" name="info[]" id="url{pigcms{$i}-info" type="text" value="{pigcms{$content['info'][$i-1]}" readonly />
												<input type="hidden"  style="width:150px;" name="img[]" id="url{pigcms{$i}-img" class="input input-image" value="{pigcms{$content['img'][$i-1]}"   />
												<input type="hidden"  style="width:200px;" class="input" name="url[]" id="url{pigcms{$i}" value="{pigcms{$content['url'][$i-1]}"  />
												<img src="{pigcms{$content['img'][$i-1]}" id="url{pigcms{$i}-img-show">
												<a href="{pigcms{$content['url'][$i-1]}" id="url{pigcms{$i}-show" target="_blank">查看链接</a> 
											</td>
											
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
										<td class="content">
											<a href="" id="addLink" class="btn btn-sm btn-success" onclick="addLinks('url',0)" data-toggle="modal">从功能库选择</a>
											<input style="width:200px;" size="20" name="title[]" id="url-title" type="text" value="" readonly />
											<input style="width:200px;" size="20" name="info[]" id="url-info" type="text" value="" readonly />
											<input type="hidden"  style="width:150px;" name="img[]" id="url-img" class="input input-image" value=""   />
											<input type="hidden"  style="width:200px;" class="input" name="url[]" id="url" value=""  />
											<img src="" id="url-img-show">
											<a href="" id="url-show" target="_blank">查看链接</a> 
										</td>
										<td class="delete">
											<a href="javascript:void(0)" onclick="del(this)">[删除]</a>
										</td>
									</tr>
									<tr>
										
										<td align="center" colspan="3"><a  href="javascript:void(0)" onclick="plus()"><img style="width:30px;height:30px;" src="{pigcms{$static_path}images/plus.jpg"/></a></td>
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
<style>
td img{max-width:60px;max-height:40px; width:100%;height:100%;}
</style>
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
	if(No>8){
		alert('不能超过8条信息');
	}else{
		$(item).after(newitem);
		newitem.find('input').attr('value','');
		newitem.find('input[name="img[]"]').attr('id','url'+No+'-img');
		newitem.find('input[name="info[]"]').attr('id','url'+No+'-info');
		newitem.find('input[name="title[]"]').attr('id','url'+No+'-title');
		newitem.find('img').attr('id','url'+No+'-img-show');
		newitem.find('img').attr('src','');
		newitem.find('.content a:last').attr('id','url'+No+'-show');
		newitem.find('.content a:last').attr('href','');
		console.log(newitem.find('a:last'))
		newitem.find("#addLink").attr('onclick',"addLinks('url"+No+"',0)");
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
				$.get("/merchant.php?c=Promote&a=del&delete_content="+id, function(data) {});
			}
			if($('.odd').length==2){
				$('.delete').children().hide();
			}
			$(obj).parents('.odd').remove();
			$.each($('.odd'), function(index, val) {
				var No =index+1;
				$(val).find('label').html(No);
				$(val).find('input[name="url[]"]').attr('id','url'+No);
				$(val).find('input[name="img[]"]').attr('id','url'+No+'-img');
				$(val).find('input[name="info[]"]').attr('id','url'+No+'-info');
				$(val).find('input[name="title[]"]').attr('id','url'+No+'-title');
				$(val).find('img').attr('id','url'+No+'-img-show');
				$(val).find('.content a:last').attr('id','url'+No+'-show');
				$(val).find("#addLink").attr('onclick',"addLinks('url"+No+"',0)");
			
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
	art.dialog.open('?g=Admin&c=Link&a=content_insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
}
</script>
<include file="Public:footer"/>

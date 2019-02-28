<include file="Public:header"/>
	<style>.frame_form td{vertical-align:middle;}</style>
	<form id="myform" method="post" action="{pigcms{:U('Send/chanel_msg_edit')}" frame="true" refresh="true" >
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			
		
			<tr>
				<td width="80">标题</td>
				<td>
					<input type="text"  style="width:300px;" class="input fl" name="Full_title" value="{pigcms{$Full_title}"  autocomplete="off" />
					<input type="hidden" name="chanel_id" value="{pigcms{$_GET['chanel_id']}"/>
				</td>
			</tr>
			<if condition="$chanel_content">
			<volist name="chanel_content" id="vo">
				<tr class="plus">
					<td width="40">图文<label>{pigcms{$i}</label></td>
					<td>
						<table style="width:100%;border:#d5dfe8 1px solid;padding:2px;">
							<tr>
								<td width="60">标题：</td>
								<input type="hidden" name="id[]" value="{pigcms{$vo.id}"/>
								<td><input type="text" style="width:200px;" class="input" name="title[]"  value="{pigcms{$vo.title}" /></td>
								<td width="60">图片：</td>
								<td><input type="text"  style="width:200px;" name="img[]" class="input input-image" value="{pigcms{$vo.img}" readonly >&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a></td>
								<td rowspan="2" class="delete">
									<a href="javascript:void(0)" onclick="del(this)"><img style="width:30px;height:30px;" src="{pigcms{$static_path}images/del.jpg"/></a>
								</td>
							<tr/>
							<tr>
								<td width="60">描述：</td>
								<td><textarea  style="width:200px;height:60px" class="input" name="des[]" >{pigcms{$vo.des}</textarea></td>
								<td width="60">链接：</td>
								<td><input type="text"  style="width:200px;" class="input" name="url[]" id="url{pigcms{$i}" value="{pigcms{$vo.url}" /><a href="#modal-table" id="addLink" class="btn btn-sm btn-success" onclick="addLink('url{pigcms{$i}',0)" data-toggle="modal">从功能库选择</a></td>
							</tr>
						</table>
					</td>
				</tr>
			</volist>
			<else />
				<tr class="plus">
				<td width="40">图文<label>1</label></td>
				<td>
					<table style="width:100%;border:#d5dfe8 1px solid;padding:2px;">
						<tr>
							<input type="hidden" name="id[]" value="{pigcms{$vo.id}"/>
							<td width="60">标题：</td>
							<td><input type="text" style="width:200px;" class="input" name="title[]" value=""  /></td>
							<td width="60">图片：</td>
							<td><input type="text"  style="width:200px;"  name="img[]" class="input input-image" value="" readonly  >&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a></td>
							<td rowspan="2" class="delete">
								<a href="javascript:void(0)" onclick="del(this)"><img style="width:30px;height:30px;" src="{pigcms{$static_path}images/del.jpg"/></a>
							</td>
						<tr/>
						<tr>
							<td width="60">描述：</td>
							<td><textarea  style="width:200px;height:60px"   class="input" name="des[]"></textarea></td>
							<td width="60">链接：</td>
							<td><input type="text"  style="width:200px;"  class="input" name="url[]" id="url" value=""/><a href="#modal-table" id="addLink" class="btn btn-sm btn-success" onclick="addLink('url',0)" data-toggle="modal">从功能库选择</a></td>
						</tr>
					</table>
				</td>
			</tr>
			</if>
			<tr>
				<td></td>
				<td><a href="javascript:void(0)" onclick="plus()"><img style="width:30px;height:30px;" src="{pigcms{$static_path}images/plus.jpg"/></a></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
		
	</form>
	<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
	<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>

	<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
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
	<script type="text/javascript">
		$(document).ready(function() {
			if($('.plus').length<=1){
				$('.delete').children('a').hide();
			}
		});

		function plus(){
			var item = $('.plus:last');
			var newitem = $(item).clone(true);
			var No = parseInt(item.find("label").html())+1;
			$('.delete').children().show();
			if(No>10){
				alert('不能超过10条信息');
			}else{
				$(item).after(newitem);
				newitem.find('input').attr('value','');
				newitem.find('textarea').attr('value','');
				newitem.find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
				newitem.find("label").html(No);
				newitem.find('input[name="url[]"]').attr('id','url'+No);
				newitem.find('.delete').children().show();
			}
		}
		function del(obj){
			
			if($('.plus').length<=1){
				$('.delete').children().hide();
			}else{
				if(confirm('确定删除吗?')){
					var id = $(obj).parents('tr').find('input[name="id[]"]').val();
					if(typeof(id) != "undefined"&&id!=''){
						$.get("/admin.php?g=System&c=Send&a=delete_chanel_msg_list&delete_content="+$(obj).parents('tr').find('input[name="id[]"]').val(), function(data) {});
					}
					if($('.plus').length==2){
						$('.delete').children().hide();
					}
					$(obj).parents('.plus').remove();
					$.each($('.plus'), function(index, val) {
						var No =index+1;
						$(val).find('label').html(No);
						$(val).find('input[name="url[]"]').attr('id','url'+No);
						$(val).find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
					});
				}
				
			}
		}
		function addLink(domid,iskeyword){
			art.dialog.data('domid', domid);
			art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
		}
	</script>
<include file="Public:footer"/>


<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('House/open_door_adver_add')}" enctype="multipart/form-data">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">广告名称</th>
				<td><input type="text" class="input fl" name="name" size="20" placeholder="请输入名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="80">小区名称</th>
				<td><input type="text" class="input fl" name="village_name" id="village_name" size="20" placeholder="请输入名称" validate="maxlength:20,required:true"/></td>
				<input type="hidden" name="village_id" id="village_id" value="">
 			</tr>
			<tr class="hide" >
				<th width="80">小区信息</th>
				<td id="village_info"></td>
 			</tr>
			<tr>
				<th width="80">图片展示</th>
				<td><img scr="" id="img" style="width:200px;height:100px;"></td>
			</tr>
			
		
			<tr>
				<th width="80">广告图片(大小550*340)</th>
				<td><input type="text"  style="width:200px;" name="android_pic" class="input input-image" value=""   readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a>
				<em class="notice_tips" tips="开门成功后展示"></em>
				</td>
			</tr>
			
			
			<!--tr>
				<th width="160">广告有效期</th>
				<td><input type="text" class="input fl" name="begin_time" style="margin-right:5px" size="20" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})" />
				<input type="text" class="input fl" name="end_time"  size="20" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})" tips="广告有效期起始结束时间"></td>
			</tr>
				<tr>
				<th width="80">播放时间</th>
				<td>
					<input type="text" class="input fl" name="play_time" id="play_time" style="width:200px;" placeholder="播放时间，单位/秒" validate="maxlength:200,required:true"/>
					<em class="notice_tips" tips="请设置3-9秒内"></em>	
				</td>
			</tr-->
			<tr>
				<th width="80">链接地址</th>
				<td>
					<input type="text" class="input fl" name="url" id="url" style="width:200px;" placeholder="请填写链接地址" validate="maxlength:200,required:true,url:true"/>
					
					<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 0)" data-toggle="modal">从功能库选择</a>
					
				</td>
			</tr>
			
			<tr>
				<th width="80">广告状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="status" value="0" /></label></span>
				</td>
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
								$('#img').attr('src',site_url+url);
								editor.hideDialog();
							}
						});
					});
				});
			
			});
			
			$(function(){
				$('#village_name').change(function(){
					var vallage_name=$(this).val()
					$.post('{pigcms{:U('get_village_name')}',{'village_name':vallage_name},function(date){
						if(date.village){
							var village_info = date.village
							$('#village_id').val(village_info.village_id);
							var html_val="小区id:"+village_info.village_id+"<br>";
							html_val += "小区名称:"+village_info.village_name+"<br>";
							html_val += "小区物业:"+village_info.property_name+"<br>";
							html_val += "小区地址:"+village_info.village_address+"<br>";
							$('#village_info').html(html_val)
							$('#village_info').parent('tr').show()
						}else{
							window.top.msg(0,"社区不存在",true,3);
						}
					},'json')
				})
			})
		
	</script>
<script>
function addLink(domid, iskeyword, type){
	art.dialog.data('domid', domid);
	if (type == 1) {
		art.dialog.open('?g=Admin&c=LinkPC&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	} else {
		art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	}
}
$("#yes").click(function(){
	$("#adver_region").hide();
})
$("#no").click(function(){
	$("#adver_region").show();
})
</script>
<include file="Public:footer"/>
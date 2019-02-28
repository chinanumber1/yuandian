<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Adver/app_fullscreen_edit')}" enctype="multipart/form-data">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">广告名称</th>
				<td><input type="text" class="input fl" name="name" size="20" placeholder="请输入名称" value="{pigcms{$now_adver.name}" validate="maxlength:20,required:true"/></td>
			</tr>
			<input type="hidden" name="id" value="{pigcms{$now_adver.id}" >
			<tr>
				<th width="80">图片展示</th>
				<td><img src="{pigcms{$now_adver.ios_pic_s}" id="img" style="width:200px;height:100px;"></td>
			</tr>
			
			<tr>
				<th width="80">ios广告图片(大小640X1136)</th>
				<td><input type="text"  style="width:200px;" name="ios_pic_s" class="input input-image" value="{pigcms{$now_adver.ios_pic_s}"   readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a>
				<em class="notice_tips" tips="开启后商家中心会显示此商家已签约标签即商家是优质客户，所有新增的产品都无需审核"></em>
				
				</td>
			</tr>
			<tr>
				<th width="80">ios广告图片(大小1242X2208)</th>
				<td><input type="text"  style="width:200px;" name="ios_pic_b" class="input input-image" value="{pigcms{$now_adver.ios_pic_b}"   readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a>
				<em class="notice_tips" tips="开启后商家中心会显示此商家已签约标签即商家是优质客户，所有新增的产品都无需审核"></em>
				</td>
			</tr>
			<tr>
				<th width="80">安卓广告图片(大小1080X1920)</th>
				<td><input type="text"  style="width:200px;" name="android_pic" class="input input-image" value="{pigcms{$now_adver.android_pic}"   readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a>
				<em class="notice_tips" tips="开启后商家中心会显示此商家已签约标签即商家是优质客户，所有新增的产品都无需审核"></em>
				</td>
			</tr>
			
			<tr>
				<th width="160">广告有效期</th>
				<td><input type="text" class="input fl" name="begin_time" style="margin-right:5px"  value ="{pigcms{$now_adver.begin_time|date='Y-m-d ',###}" size="20" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})" />
				<input type="text" class="input fl" name="end_time"  value ="{pigcms{$now_adver.end_time|date='Y-m-d',###}" size="20" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})" tips="广告有效期起始结束时间"></td>
			</tr>
				<tr>
				<th width="80">播放时间</th>
				<td>
					<input type="text" class="input fl" name="play_time" id="play_time" style="width:200px;"value ="{pigcms{$now_adver.play_time}" placeholder="播放时间，单位/秒" validate="maxlength:200,required:true"/>
					<em class="notice_tips" tips="请设置3-9秒内"></em>	
				</td>
			</tr>
			
			
			<tr>
				<th width="80">链接地址</th>
				<td>
					<input type="text" class="input fl" name="url" id="url" value="{pigcms{$now_adver.url}" style="width:200px;" placeholder="请填写链接地址" validate="maxlength:200,required:true,url:true"/>
					
					<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 0)" data-toggle="modal">从功能库选择</a>
					
				</td>
			</tr>
			
			<tr>
				<th width="80">广告状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_adver['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1" <if condition="$now_adver['status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_adver['status'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="status" value="0" <if condition="$now_adver['status'] eq 0">checked="checked"</if>/></label></span>
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
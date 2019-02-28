<include file="Public:header"/>
	<form id="myform" method="post" action="__SELF__" enctype="multipart/form-data">
		<input type="hidden" name="id" value="{pigcms{$foot_menu_info.id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="100">菜单名称</th>
				<td><input type="text" class="input fl" name="name" value="{pigcms{$foot_menu_info.name}" size="20" placeholder="请输入名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<if condition="$foot_menu_info['pic_path']">
				<tr>
					<th width="100">菜单图片展示</th>
					<td><img src="{pigcms{$config.site_url}/upload/slider/{pigcms{$foot_menu_info.pic_path}" style="width:25px;height:25px;" class="view_msg"/></td>
				</tr>
			</if>
             <tr>
				<th width="100">菜单图片</th>
				<td><input type="file" class="input fl" name="pic_path" style="width:200px;" placeholder="请上传图片" tips="可不上传"/></td>
			</tr>
            
            <if condition="$foot_menu_info['hover_pic_path']">
				<tr>
					<th width="100">菜单选中图片展示</th>
					<td><img src="{pigcms{$config.site_url}/upload/slider/{pigcms{$foot_menu_info.hover_pic_path}" style="width:25px;height:25px;" class="view_msg"/></td>
				</tr>
			</if>
            
           
			<tr>
				<th width="100">菜单选中图片</th>
				<td><input type="file" class="input fl" name="hover_pic_path" style="width:200px;" placeholder="请上传图片" tips="可不上传"/></td>
			</tr>
			<tr>
				<th width="100">链接地址</th>
				<td>
				<input type="text" class="input fl" name="url" id="url" value="{pigcms{$foot_menu_info.url}" style="width:200px;" placeholder="请填写链接地址" validate="maxlength:200,required:true,url:true"/>
                <a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url',0)" data-toggle="modal">从功能库选择</a>
				</td>
			</tr>
			<tr>
				<th width="100">菜单排序</th>
				<td><input type="text" class="input fl" name="sort" style="width:100px;" value="{pigcms{$foot_menu_info.sort}" validate="maxlength:10,required:true,number:true"/></td>
			</tr>
			<tr>
				<th width="100">菜单状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$foot_menu_info['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1" <if condition="$foot_menu_info['status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$foot_menu_info['status'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="status" value="0" <if condition="$foot_menu_info['status'] eq 0">checked="checked"</if>/></label></span>
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
<script>
function addLink(domid,iskeyword, type){
	art.dialog.data('domid', domid);
	if (type == 1) {
		art.dialog.open('?g=Admin&c=LinkPC&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	} else {
		art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	}
	
}
</script>
<include file="Public:footer"/>
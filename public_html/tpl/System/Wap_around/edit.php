<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Wap_around/amend')}" enctype="multipart/form-data">
	   <input type="hidden" name="id" value="{pigcms{$wap_around.id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">附近模块分类</th>
				<td>{pigcms{$alias_name[$wap_around['name']]}</td>
			</tr>
			<tr>
				<th width="80">模块描述</th>
				<td><input type="text" class="input fl" name="des" style="width:80px;" value="{pigcms{$wap_around.des}" validate="maxlength:10,required:true"/></td>
			</tr>
			<input type="hidden"  name="id" style="width:80px;" value="{pigcms{$wap_around.id}" validate="maxlength:10,required:true"/>
			<if condition="$wap_around['pic']">
				<tr>
					<th width="80">模块现图</th>
					<td><img src="{pigcms{$config.site_url}/upload/wap/{pigcms{$wap_around.pic}" style="width:80px;height:80px;" class="view_msg"/></td>
				</tr>
			</if>
			<tr>
				<th width="80">模块图片</th>
				<td><input type="file" class="input fl" name="pic" style="width:200px;" placeholder="请上传图片" tips="不修改请不上传！上传新图片，老图片会被自动删除！"/></td>
			</tr>
			<tr>
				<th width="80">链接地址</th>
				<td>
				<input type="text" class="input fl" name="url" id="url" value="{pigcms{$wap_around.url}" style="width:200px;" placeholder="请填写链接地址" validate="maxlength:200,required:true,url:true"/>
				<if condition="$now_category['cat_type'] neq 1">
				<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url',0, 0)" data-toggle="modal">从功能库选择</a>
				<else />
				<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url',0, 1)" data-toggle="modal">从功能库选择</a>
				</if>
				</td>
			</tr>
			<tr>
				<th width="80">模块排序</th>
				<td><input type="text" class="input fl" name="sort" style="width:80px;" value="{pigcms{$wap_around.sort}" validate="maxlength:10,required:true,number:true"/></td>
			</tr>
			<tr>
				<th width="80">模块状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$wap_around['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1" <if condition="$wap_around['status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$wap_around['status'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="status" value="0" <if condition="$wap_around['status'] eq 0">checked="checked"</if>/></label></span>
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
<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Adver/adver_modify')}" enctype="multipart/form-data">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">广告名称</th>
				<td><input type="text" class="input fl" name="name" size="20" placeholder="请输入名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="80">广告副标题</th>
				<td><input type="text" class="input fl" name="sub_name" size="20" placeholder="请输入副标题" validate="required:true"/></td>
			</tr>
			
			<if condition="$many_city eq 1 AND $now_category['cat_key'] neq 'levelad'">
				<tr>
					<th width="15%">通用广告</th>
					<td width="35%" class="radio_box">
						<span class="cb-enable"><label class="cb-enable selected"><span>通用</span><input id="yes" type="radio" name="currency" value="1" checked="checked" /></label></span>
						<span class="cb-disable"><label class="cb-disable"><span>不通用</span><input id="no" type="radio" name="currency" value="2" /></label></span>
					</td>
				</tr>
				<tr id="adver_region" style="display:none;">
					<th width="15%">所在区域</th>
					<td width="85%" colspan="3" id="choose_cityareass" province_idss="" city_idss=""></td>
				</tr>
			</if>
			<tr>
				<th width="80">广告图片</th>
				<td><input type="file" class="input fl" name="pic" style="width:200px;" placeholder="请上传图片" validate="required:true"/></td>
			</tr>

            <tr>
                <th width="80">视频地址</th>
                <td><input type="text" class="input fl" name="video" style="width:200px;" placeholder="输入视频" validate="required:false"/></td>
            </tr>

			<tr>
				<th width="80">背景颜色</th>
				<td><input type="text" class="input fl" name="bg_color" id="choose_color" style="width:120px;" placeholder="可不填写" tips="请点击右侧按钮选择颜色，用途为如果图片尺寸小于屏幕时，会被背景颜色扩充，主要为首页使用。"/>&nbsp;&nbsp;<a href="javascript:void(0);" id="choose_color_box" style="line-height:28px;">点击选择颜色</a></td>
			</tr>
			<tr>
				<th width="80">链接地址</th>
				<td>
					<input type="text" class="input fl" name="url" id="url" style="width:200px;" placeholder="请填写链接地址" validate="maxlength:200,required:true,url:true"/>
					<if condition="!C('butt_open')">
						<if condition="$now_category['cat_type'] neq 1">
							<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 0)" data-toggle="modal">从功能库选择</a>
						<else />
							<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 1)" data-toggle="modal">从功能库选择</a>
						</if>
					</if>
				</td>
			</tr>
			<tr>
				<th width="80">广告排序</th>
				<td><input type="text" class="input fl" name="sort" style="width:80px;" value="0" validate="maxlength:10,required:true,number:true"/></td>
			</tr>
			<tr>
				<th width="80">广告状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="status" value="0" /></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">以通用广告来补全广告位</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>是</span><input type="radio" name="complete" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>否</span><input type="radio" name="complete" value="0" /></label></span>
					<em tips="当设置过城市广告时，城市广告数量不够总数量，是否使用通用广告来补全城市广告位的数量。" class="notice_tips"></em>
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
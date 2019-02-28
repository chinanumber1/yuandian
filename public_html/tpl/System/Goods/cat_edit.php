<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Goods/cat_amend')}" enctype="multipart/form-data">
		<input type="hidden" name="id" value="{pigcms{$now_category.id}"/>
		<input type="hidden" name="fid" value="{pigcms{$parentid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">商品分类名称</th>
				<td><input type="text" class="input fl" name="name" id="name" value="{pigcms{$now_category.name}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="90">商品分类排序</th>
				<td><input type="text" class="input fl" name="sort" id="sort" value="{pigcms{$now_category.sort}" placeholder="" validate="maxlength:20,number:true" tips="分类排序（数值越大排在前面）"/></td>
			</tr>
			<tr>
				<th width="90">商品分类状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_category['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1"  <if condition="$now_category['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_category['status'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="status" value="0"  <if condition="$now_category['status'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
            <if condition="empty($parentid)">
            <tr>
                <th width="80">分类广告现图</th>
                <td><img src="{pigcms{$config.site_url}/upload/goodscategory/{pigcms{$now_category.image}" style="width:260px;height:80px;" class="view_msg"/></td>
            </tr>
            <tr>
                <th width="80">分类广告图</th>
                <td><input type="file" class="input fl" name="image" style="width:200px;" placeholder="请上传图片" tips="不修改请不上传！上传新图片，老图片会被自动删除！建议尺寸1200*225"/></td>
            </tr>
            <tr>
                <th width="80">链接地址</th>
                <td>
                <input type="text" class="input fl" name="url" id="url" value="{pigcms{$now_category.url}" style="width:200px;" placeholder="请填写链接地址" validate="maxlength:200,url:true"/>
                <a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url')" data-toggle="modal">从功能库选择</a>
                </td>
            </tr>
            </if>
            <tr>
                <th width="90">是否热门</th>
                <td>
                    <span class="cb-enable"><label class="cb-enable <if condition="$now_category['is_hot'] eq 1">selected</if>"><span>是</span><input type="radio" name="is_hot" value="1" <if condition="$now_category['is_hot'] eq 1">checked="checked"</if> /></label></span>
                    <span class="cb-disable"><label class="cb-disable <if condition="$now_category['is_hot'] eq 0">selected</if>"><span>否</span><input type="radio" name="is_hot" value="0" <if condition="$now_category['is_hot'] eq 0">checked="checked"</if> /></label></span>
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
function addLink(domid){
    art.dialog.data('domid', domid);
    art.dialog.open('?g=Admin&c=LinkPC&a=insert',{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
}
</script>
<include file="Public:footer"/>
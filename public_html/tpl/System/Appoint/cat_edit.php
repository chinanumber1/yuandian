<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Appoint/cat_amend')}" enctype="multipart/form-data">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">分类名称</th>
				<td><input type="text" class="input fl" name="cat_name" id="cat_name" value="{pigcms{$now_category.cat_name}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">短标记(url)</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$now_category.cat_url}" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>

					<tr>
						<th width="80">分类LOGO图标现图</th>
						<td><img src="{pigcms{$config.site_url}/upload/system/{pigcms{$now_category['cat_pic']}" style="width:50px;height:50px;" class="view_msg"/></td>
					</tr>
<tr>
					<th width="80">分类LOGO图标</th>
					<td><input type="file" class="input fl" name="cat_pic" style="width:145px;" placeholder="分类LOGO图片" tips="分类LOGO小图标，建议为透明白色 尺寸为100*100 的图标"/></td>
				</tr>
				
                
				<if condition='$now_category["cat_fid"]'>
                <tr>
						<th width="80">分类图片现图</th>
						<td><img src="{pigcms{$config.site_url}/upload/system/{pigcms{$now_category['cat_big_pic']}" style="width:50px;height:50px;" class="view_msg"/></td>
					</tr>
					
<tr>
					<th width="80">分类图片</th>
					<td><input type="file" class="input fl" name="cat_big_pic" style="width:145px;" placeholder="分类图片" tips="分类图片，用于微信回复和PC端展示，建议尺寸900*500"/></td>
				</tr>
                </if>

			<tr>
				<th width="80">分类排序</th>
				<td><input type="text" class="input fl" name="cat_sort" value="{pigcms{$now_category.cat_sort}" size="10" placeholder="分类排序" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。"/></td>
			</tr>
<tr>
				<th width="80">背景颜色</th>
				<td><input type="text" class="input fl" name="bg_color" value="{pigcms{$now_category.bg_color}" id="choose_color" style="width:120px;" placeholder="可不填写" tips="请点击右侧按钮选择颜色，用途为页面显示。"/>&nbsp;&nbsp;<a href="javascript:void(0);" id="choose_color_box" style="line-height:28px;">点击选择颜色</a></td>
			</tr>
            <if condition='$now_category["cat_fid"]'>
                <tr>
                <th width="160">平台运营类别</th>
                <td>
                <select name="is_autotrophic" class="is_autotrophic">
                    <option <if condition='$now_category["is_autotrophic"] eq 0'>selected="selected"</if>  value="0">商家入驻</option>
                    <option value="1" <if condition='$now_category["is_autotrophic"] eq 1'>selected="selected"</if>>平台自营</option>
                    <option value="2" <if condition='$now_category["is_autotrophic"] eq 2'>selected="selected"</if>>第三方入驻</option>
                </select>
                <em class="notice_tips" tips="平台运营类别，可选择商家入驻，平台自营，第三方入驻（第三方入驻：项目预约与第三方进行联系，与平台无关）"></em>
                </tr>
            </if>
            
			<tr>
				<th width="80">是否热门</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_category['is_hot'] eq 1">selected</if>"><span>是</span><input type="radio" name="is_hot" value="1" <if condition="$now_category['is_hot'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_category['is_hot'] eq 0">selected</if>"><span>否</span><input type="radio" name="is_hot" value="0"  <if condition="$now_category['is_hot'] eq 0">checked="checked"</if> /></label></span>
					<em class="notice_tips" tips="如果选择热门，颜色会有变化"></em>
				</td>
			</tr>
			<tr>
				<th width="80">分类状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_category['cat_status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="cat_status" value="1"  <if condition="$now_category['cat_status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_category['cat_status'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="cat_status" value="0"  <if condition="$now_category['cat_status'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
            
            <if condition='$now_category["cat_fid"]'>
                <tr>
                    <th width="80">PC端能否下单</th>
                    <td>
                        <span class="cb-enable"><label <if condition="$now_category['is_pc_order'] eq 1">class="cb-enable selected"<else />class="cb-enable"</if>><span>启用</span><input type="radio" name="is_pc_order" value="1" <if condition="$now_category['is_pc_order'] eq 1">checked="checked"</if> /></label></span>
                        <span class="cb-disable"><label <if condition="$now_category['is_pc_order'] eq 0">class="cb-disable selected"<else />class="cb-disable"</if>><span>关闭</span><input type="radio" name="is_pc_order" value="0" <if condition="$now_category['is_pc_order'] eq 0">checked="checked"</if>/></label></span>
                    </td>
                </tr>
                
                <tr>
                    <th width="80">描述</th>
                    <if condition='!$_GET["frame_show"]'>
                    	<td><textarea name="desc"  cols="35" rows="4">{pigcms{$now_category['desc']}</textarea><em class="notice_tips" tips="微信回复、电脑网站介绍时需要用到，换行符表示换行"></em></td>
                    <else/>
                    	<td><div class="show" style="word-wrap:break-word; word-break:break-all; width:145px">{pigcms{$now_category['desc']}</div></td>
                    </if>
				</tr>
            </if>
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


var is_autotrophic=$('.is_autotrophic').val();
get_outsourced_phone(is_autotrophic);
$('.is_autotrophic').change(function(){
	var is_autotrophic=$('.is_autotrophic').val();
			get_outsourced_phone(is_autotrophic);
});

function get_outsourced_phone(is_autotrophic){
	var outsourced_phone="{pigcms{$now_category['outsourced_phone']}"
			var shtml='<tr class="outsourced"><th width="80">第三方入驻联系号码</th><td><input type="text" class="input fl" validate="maxlength:20,required:true" name="outsourced_phone" id="outsourced_phone" size="25" placeholder="" tips="" value="'+outsourced_phone+'"/></td></tr>';
			if(is_autotrophic == 2){
				$('.is_autotrophic').parents('tr').after(shtml);
			}else{
				$('.outsourced').remove();
			}
}
</script>
<include file="Public:footer"/>
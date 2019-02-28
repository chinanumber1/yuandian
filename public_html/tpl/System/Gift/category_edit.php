<include file="Public:header"/>
	<form id="myform" method="post" action="__SELF__" enctype="multipart/form-data">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">分类名称</th>
				<td><input type="text" class="input fl" name="cat_name" id="cat_name" value="{pigcms{$now_category.cat_name}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>

					<tr>
						<th width="80">分类图片</th>
						<td><img src="{pigcms{$config.site_url}/upload/system/{pigcms{$now_category['cat_pic']}" style="width:50px;height:50px;" class="view_msg"/></td>
					</tr>
<tr>
					<th width="80">分类图片</th>
					<td><input type="file" class="input fl" name="cat_pic" style="width:145px;" placeholder="分类图片" tips="分类图片，尺寸为298*198的图标"/></td>
				</tr>
				
			<tr>
				<th width="80">分类排序</th>
				<td><input type="text" class="input fl" name="cat_sort" value="{pigcms{$now_category.cat_sort}" size="10" placeholder="分类排序" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。"/></td>
			</tr>
<tr>
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
            
           <tr>
                    <th width="80">描述</th>
                    <if condition='!$_GET["frame_show"]'>
                    	<td><textarea name="desc"  cols="35" rows="4">{pigcms{$now_category['desc']}</textarea><em class="notice_tips" tips="微信回复、电脑网站介绍时需要用到，换行符表示换行。最多支持12个字。"></em></td>
                    <else/>
                    	<td><div class="show" style="word-wrap:break-word; word-break:break-all; width:145px">{pigcms{$now_category['desc']}</div></td>
                    </if>
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
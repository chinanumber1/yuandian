<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Appoint/cat_modify')}" enctype="multipart/form-data">
		<input type="hidden" name="cat_fid" value="{pigcms{$_GET.cat_fid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">分类名称</th>
				<td><input type="text" class="input fl" name="cat_name" id="cat_name" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">短标记(url)</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>

				<tr>
					<th width="80">分类LOGO图标</th>
					<td><input type="file" class="input fl" name="cat_pic" style="width:200px;" placeholder="分类LOGO图片" validate="required:true" tips="分类LOGO小图标，建议为透明白色 尺寸为100*100 的图标"/></td>
				</tr>
            
			<tr>
            
            <if condition='$_GET["cat_fid"]'>
            <tr>
					<th width="80">分类图片</th>
					<td><input type="file" class="input fl" name="cat_big_pic" style="width:200px;" placeholder="分类图片" validate="required:true" tips="分类图片，用于微信回复PC端展示，建议尺寸900*500"/></td>
				</tr>
            </if>
			<tr>
            
				<th width="80">分类排序</th>
				<td><input type="text" class="input fl" name="cat_sort" value="0" size="10" placeholder="分类排序" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。"/></td>
			</tr>

<tr>
				<th width="80">背景颜色</th>
				<!--<td><input type="text" class="input fl" name="bg_color" id="choose_color" style="width:120px;" placeholder="可不填写" tips="请点击右侧按钮选择颜色，用途为如果图片尺寸小于屏幕时，会被背景颜色扩充，主要为首页使用。"/>&nbsp;&nbsp;<a href="javascript:void(0);" id="choose_color_box" style="line-height:28px;">点击选择颜色</a></td>-->
                <td><input type="text" class="input fl" name="bg_color" id="choose_color" style="width:120px;" placeholder="可不填写" tips="请点击右侧按钮选择颜色，用途为页面显示。"/>&nbsp;&nbsp;<a href="javascript:void(0);" id="choose_color_box" style="line-height:28px;">点击选择颜色</a></td>
			</tr>         
            
            <!--<if condition='$_GET["cat_fid"]'>
                <tr>
                    <th width="80">是否平台自营</th>
                    <td>
                        <span class="cb-enable"><label class="cb-enable"><span>是</span><input type="radio" name="is_autotrophic" value="1" /></label></span>
                        <span class="cb-disable"><label class="cb-disable selected"><span>否</span><input type="radio" name="is_autotrophic" value="0" checked="checked" /></label></span>
                        <em class="notice_tips" tips="平台自营，可派单到指定商家"></em>
                    </td>
                </tr>
            </if>-->
            
            <if condition='$_GET["cat_fid"]'>
                <tr>
                <th width="160">平台运营类别</th>
                <td>
                <select name="is_autotrophic" class="is_autotrophic">
                    <option selected="selected"  value="0">商家入驻</option>
                    <option value="1">平台自营</option>
                    <option value="2">第三方入驻</option>
                </select>
                <em class="notice_tips" tips="平台运营类别，可选择商家入驻，平台自营，第三方入驻（第三方入驻：项目预约与第三方进行联系，与平台无关）"></em>
                </tr>
            </if>

			<tr>
				<th width="80">是否热门</th>
				<td>
					<span class="cb-enable"><label class="cb-enable"><span>是</span><input type="radio" name="is_hot" value="1" /></label></span>
					<span class="cb-disable"><label class="cb-disable selected"><span>否</span><input type="radio" name="is_hot" value="0" checked="checked" /></label></span>
					<em class="notice_tips" tips="如果选择热门，颜色会有变化"></em>
				</td>
			</tr>
			<tr>
				<th width="80">分类状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="cat_status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="cat_status" value="0" /></label></span>
				</td>
			</tr>
            <if condition='$_GET["cat_fid"]'>
                <tr>
                    <th width="80">PC端能否下单</th>
                    <td>
                        <span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="is_pc_order" value="1" checked="checked" /></label></span>
                        <span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="is_pc_order" value="0" /></label></span>
                    </td>
                </tr>
                <tr>
                    <th width="80">描述</th>
                    <td><textarea name="desc" cols="35" rows="4"></textarea><em class="notice_tips" tips="微信回复、电脑网站介绍时需要用到，换行符表示换行"></em></td>
				</tr>
            </if>
            
            
            
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
    
    <script type="text/javascript">
    	$('.is_autotrophic').change(function(){
			var is_autotrophic=$(this).val();
			var shtml='<tr class="outsourced"><th width="80">第三方入驻联系号码</th><td><input type="text" class="input fl" validate="maxlength:20,required:true" name="outsourced_phone" id="outsourced_phone" size="25" placeholder="" tips=""/></td></tr>';
			if(is_autotrophic == 2){
				$(this).parents('tr').after(shtml);
			}else{
				$('.outsourced').remove();
			}
		});
    </script>
<include file="Public:footer"/>
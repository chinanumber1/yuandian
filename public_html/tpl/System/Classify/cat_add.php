<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Classify/cat_modify')}" frame="true" refresh="true">
		<input type="hidden" name="fcid" value="{pigcms{$fcid}"/>
		<input type="hidden" name="pfcid" value="{pigcms{$pfcid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">分类名称</th>
				<td><input type="text" class="input fl" name="cat_name" id="cat_name" size="25" onBlur="TextSEO(this.value)" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">短标记(url)</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			<if condition='empty($_GET["fcid"]) && empty($_GET["pfcid"])'>
				<tr>
					<th width="80">字体颜色</th>
					<td><input type="text" tips="请点击右侧按钮选择颜色，用途为页面显示。" placeholder="可不填写" style="width:120px;" id="choose_color" value="" name="font_color" class="input fl">&nbsp;&nbsp;<a style="line-height:28px;" id="choose_color_box" href="javascript:void(0);">点击选择颜色</a></td>
				</tr>
			</if>


            <if condition='empty($_GET["fcid"]) && empty($_GET["pfcid"])'>
                <tr>
                    <th width="80">选择打赏类型</th>
                    <td>
                        <select  id="reward_type" name="reward_type" onchange="select_reward_type(this)" >
                            <option value="1">关闭打赏</option>
                            <option value="2">发布时打赏</option>
                            <option value="3">打赏后查看</option>
                            <option value="4">发布时打赏和打赏后查看并存</option>
                        </select>
                    </td>
                </tr>

                <tr style="display: none;" id="reward_publish_tr">
                    <th width="80">发布时打赏金额</th>
                    <td><input type="text" class="input fl" name="reward_publish" id="reward_publish" size="25" onBlur="moneyChange(this)" placeholder="" tips="正数且保留两位小数"/></td>
                </tr>
                <tr style="display: none;" id="reward_look_tr">
                    <th width="80">打赏后查看金额</th>
                    <td><input type="text" class="input fl" name="reward_look" id="reward_look" size="25" onBlur="moneyChange(this)" placeholder="" tips="正数且保留两位小数"/></td>
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
			<if condition="$pfcid eq 0">
			<tr>
				<th width="80">分类排序</th>
				<td><input type="text" class="input fl" name="cat_sort" value="0" size="10" placeholder="分类排序" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。"/></td>
			</tr>

			<tr>
				<th width="80">分类状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="cat_status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="cat_status" value="0" /></label></span>
				</td>
			</tr>
			</if>
			<if condition="$fcid eq 0 AND $pfcid eq 0">
            <tr>
                <th width="80">分类LOGO图标</th>
                <td><input type="file" class="input fl" name="cat_pic" style="width:200px;" placeholder="分类LOGO图片" tips="分类LOGO小图标，建议尺寸100*100" validate="required:true"/></td>
            </tr>
            </if>
		<if condition="$fcid gt 0 AND $pfcid eq 0">
			<tr>
				<th width="80">允许发布时上传图片</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>允许</span><input type="radio" name="isupimg" value="1" checked="checked"/></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>不允许</span><input type="radio" name="isupimg" value="0" /></label></span>
				</td>
			</tr>
		</if>
		<tr><th width="80">SEO标题：</th>
		<td><input type="text" tips="一般不超过80个字符！" validate="" style="width:260px;" value="" id="seo_title" name="seo_title" class="input fl"></td></tr>
		<tr><th width="80">SEO关键词：</th><td><input type="text" tips="一般不超过100个字符！" validate="" style="width:260px;" value="" id="seo_keywords" name="seo_keywords" class="input fl"></td></tr>
		<tr><th width="80">SEO描述：</th><td><textarea tips="一般不超过200个字符！" validate="" id="seo_description" name="seo_description" style="width:250px;height:90px;"></textarea></td></tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>

<script type="text/javascript">
    function TextSEO(vv){
       vv=$.trim(vv);
       if(vv){
         var seo_title=$.trim($('#seo_title').val());
         var seo_keywords=$.trim($('#seo_keywords').val());
         var seo_description=$.trim($('#seo_description').val());
         if(!seo_title) $('#seo_title').val(vv);
         if(!seo_keywords) $('#seo_keywords').val(vv);
         if(!seo_description) $('#seo_description').val(vv);
       }
    }

    function select_reward_type(obj) {
        var val = $(obj).val();
        console.log('切换的值为： ', val);

        if (1 == val) {
            console.log('切换的值为1： ', val);
            $('#reward_publish_tr').css('display', 'none');
            $('#reward_look_tr').css('display', 'none');
        } else if (2 == val) {
            console.log('切换的值为2： ', val);
            $('#reward_publish_tr').css("display", "table-row");
            $('#reward_look_tr').css('display', 'none');
        } else if (3 == val) {
            console.log('切换的值为3： ', val);
            $('#reward_publish_tr').css('display', 'none');
            $('#reward_look_tr').css("display", "table-row");
        } else if (4 == val) {
            console.log('切换的值为4： ', val);
            $('#reward_publish_tr').css("display", "table-row");
            $('#reward_look_tr').css("display", "table-row");
        }
    }

   // 数字保留两位小数
    function moneyChange(obj) {
        var val = $(obj).val();
        if(val === "" || val ==null){
            alert('请输入正确的打赏金额数字！');
            return false;
        }
        if(isNaN(val)){
            alert('请输入正确的打赏金额数字！');
            return false;
        }
        console.log('数字保留两位小数', val);
        if (val) {
            val = Math.abs(Math.round(val * 100)/100);
        }
        $(obj).val(val);
    }
</script>
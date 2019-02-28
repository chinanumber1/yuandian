<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Shop/edit_amend')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$now_shop.store_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">店铺名称</th>
				<td>{pigcms{$now_shop.name}</td>
			</tr>
			<tr class="delivery_range_type0">
				<th width="90">手动排序</th>
				<td><input type="text" class="input fl" name="sort" value="{pigcms{$now_shop.sort}" id="sort" size="10" tips="数值越大，在店铺列表页排序越前"/></td>
			</tr>
			</tr>
			<tr class="delivery_range_type0">
				<th width="90">虚拟销量</th>
				<td><input type="text" class="input fl" name="virtual_sale_count" value="{pigcms{$now_shop.virtual_sale_count}" id="sort" size="10" tips="显示给用户看的销量=虚拟销量+实际销量"/></td>
			</tr>
            <if condition="!empty($config['eleme_app_key'])">
				<tr class="">
					<th width="90">饿了么店铺ID</th>
					<td><input type="text" class="input fl" name="eleme_shopId" value="{pigcms{$now_shop.eleme_shopId}" id="eleme_shopId" size="10" tips="填写已授权给平台饿了么应用的店铺ID"/></td>
				</tr>
            </if>
			<tr>
				<th width="90">店铺关键词</th>
				<td><input type="text" class="input fl" name="search_keywords" value="{pigcms{$now_shop.search_keywords}" id="search_keywords" size="30" tips="{pigcms{$config.shop_alias_name}搜索页面的热门关键词，会随机推荐符合显示条件的店铺设置的关键词，请注意搜索词只用于推荐，搜索结果和搜索词无关，建议填写店铺名称或商品名称。以空格分割，最多100个字"/></td>
			</tr>
			<tr>
				<th width="90">是否设置为优选</th>
				<td>
					<span class="cb-enable preference_btn"><label class="cb-enable <if condition="$now_shop['preference_status'] eq 1">selected</if>"><span>是</span><input type="radio" name="preference_status" value="1"  <if condition="$now_shop['preference_status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable preference_btn"><label class="cb-disable <if condition="$now_shop['preference_status'] eq 0">selected</if>"><span>否</span><input type="radio" name="preference_status" value="0" <if condition="$now_shop['preference_status'] eq 0">checked="checked"</if>/></label></span>
					<img src="./tpl/System/Static/images/help.gif" class="tips_img" title="设置为优选店铺，请先在商家后台上传正方形LOGO" style="margin-top:1px;"/>
				</td>
			</tr>
			<tr class="preference_setting">
				<th width="90">优选排序</th>
				<td><input type="text" class="input fl" name="preference_sort" value="{pigcms{$now_shop.preference_sort}" id="sort" size="10" tips="值越高，在优选店铺中排序越前。优选中排序仅依靠此值"/></td>
			</tr>
			<tr class="preference_setting">
				<th width="90">优选描述</th>
				<td><input type="text" class="input fl" name="preference_reason" value="{pigcms{$now_shop.preference_reason}" id="sort" size="30" tips="一句话描述店铺优势，尽量控制在十六字以内，能展现多少字以页面效果为准"/></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script>
		$('.preference_btn').click(function(){
			var preference_status = $(this).find('input').val();
			if(preference_status == 1){
				$('.preference_setting').show();
			}else{
				$('.preference_setting').hide();
			}
		});
		$('.preference_btn .selected').closest('.preference_btn').trigger('click');
	</script>
<include file="Public:footer"/>
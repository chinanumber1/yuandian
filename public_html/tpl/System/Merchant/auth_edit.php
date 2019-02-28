<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Merchant/auth_edit')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td width="80">店铺编号：</td>
				<td>
					<label>{pigcms{$now_store['store_id']}</label>
					<input type="hidden" name="store_id" value="{pigcms{$now_store['store_id']}">
				</td>
			</tr>
			<tr>
				<td width="80">店铺名称：</td>
				<td>
					<label>{pigcms{$now_store['name']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">店铺电话：</td>
				<td>
					<label>{pigcms{$now_store['phone']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">资质证书图片：</td>
				<td>
				<volist name="now_store['auth_files']" id="vo">
					<img style="width:400px;" src="{pigcms{$vo.url}" class="show_bigimage"/>
				</volist>
				</td>
			</tr>
			<tr>
				<td width="80">审核：</td>
				<td>
					<select name="status">
						<option value="1" <if condition="$now_store['auth'] eq 3">selected="selected" </if>>审核通过</option>
						<option value="0" <if condition="$now_store['auth'] neq 3">selected="selected" </if>>审核不通过</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="80">审核备注：</td>
				<td>
					<textarea name="reason">{pigcms{$now_store['reason']}</textarea>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="审核" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script>
    $(document).ready(function(){
        $('.show_bigimage').click(function(){
            window.top.art.dialog({
    			padding: 0,
    			title: '大图',
    			content: '<img src="'+$(this).attr('src')+'" />',
    			lock: true
    		});
		});
    });
	</script>
<include file="Public:footer"/>

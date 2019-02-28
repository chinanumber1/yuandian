<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Yuedan/examine_edit')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td width="80">服务编号：</td>
				<td>
					<label>{pigcms{$service_info['rid']}</label>
					<input type="hidden" name="rid" value="{pigcms{$service_info['rid']}">
				</td>
			</tr>
			<tr>
				<td width="80">服务标题：</td>
				<td>
					<label>{pigcms{$service_info['title']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">用户名：</td>
				<td>
					<label>{pigcms{$service_info['nickname']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">服务位置：</td>
				<td>
					<label>{pigcms{$service_info['address_name']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">价格/单位：</td>
				<td>
					<label>{pigcms{$service_info.price}/{pigcms{$service_info.unit}</label>
				</td>
			</tr>
			<tr>
				<td width="80">所属分类：</td>
				<td>
					<label>{pigcms{$service_info.cat_name}</label>
				</td>
			</tr>
			<tr>
				<td width="80">添加时间：</td>
				<td>
					<label>{pigcms{$service_info.add_time|date="Y-m-d H:i:s",###}</label>
				</td>
			</tr>
			<tr>
				<td width="80">服务图片：</td>
				<td>
					<volist name="service_info['img']" id="vo">
						<img style="width:100px; padding: 10px;" src="{pigcms{$vo}" class="show_bigimage"/>
					</volist>
				</td>
			</tr>
			<tr>
				<td width="80">审核：</td>
				<td>
					<select name="status" id="status">
						<option value="2" <if condition="$service_info['status'] eq 2">selected="selected"</if> >审核通过</option>
						<option value="3" <if condition="$service_info['status'] eq 3">selected="selected"</if> >审核不通过</option>
					</select>
				</td>
			</tr>
			<tr style="display: none;" class="reasons" id="">
				<td width="80">拒绝理由：</td>
				<td>
					<textarea name="reasons" id="reasons" style="width: 300px; height: 100px;"></textarea>
				</td>
			</tr>

		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="审核" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script>
	$("#status").on("change",function(){
		var status = $("option:selected",this).val();
		if(status == 2){
			$(".reasons").css('display','none');
		}else{
			$(".reasons").css('display','');
		}
	});

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

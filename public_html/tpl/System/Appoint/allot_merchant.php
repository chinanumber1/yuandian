<include file="Public:header"/>
	<form id="myform" method="post" action="__SELF__" enctype="multipart/form-data">
		<input type="hidden" name="appoint_id" value="{pigcms{$order_info.appoint_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">

            
            <tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>选择服务商家：</b></td>
			</tr>
			<tr>
            

			<if condition='$appoint_order_info["store_id"] eq 0'>
                <th width="80">选择商户</th>
                    <td colspan="3">
                        <select name="mer_id">
                            <option value="0">请选择</option>
                            <volist name='merchant_list' id='vo'>
                            <option value="{pigcms{$key}" <if condition='$appoint_order_info["mer_id"] eq $key'>selected="selected"</if>>{pigcms{$vo}</option>
                            </volist>
                        </select>
						<img title="商户筛选：是以上门服务且不收定金的商户提供的预约信息为准。" class="tips_img" src="./tpl/System/Static/images/help.gif">
                    </td>
                </tr>
            </if>
            
            <if condition='$appoint_order_info'>
            <tr>
				<th width="80"><span class="red">分配信息</span></th>
				<td colspan="3">
                	<if condition='$appoint_order_info["merchant_name"]'><font>商户名称：{pigcms{$appoint_order_info["merchant_name"]}</font>&nbsp;&nbsp;</if>
                    <if condition='$appoint_order_info["store_name"]'><font>店铺名称：{pigcms{$appoint_order_info["store_name"]}</font>&nbsp;&nbsp;</if>
                    <if condition='$appoint_order_info["worker_name"]'><font>技师名称：{pigcms{$appoint_order_info["worker_name"]}</font></if>
                </td>
			</tr>
            </if>
			
			<if condition='($appoint_order_info["is_del"] eq 0) && ($appoint_order_info["paid"] eq 0)'>
				<tr>
					<td colspan="4">
						<input type="button"  class="button" value="确认取消订单" id="cancel_order" />
					</td>
				</tr>
			</if>
		</table>
        <div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script>
$('#cancel_order').click(function(){
	
	if(confirm('取消后，将无法恢复，是否确认取消？')){
		var url = "{pigcms{:U('ajax_merchant_del')}";
		var order_id = "{pigcms{$_GET['order_id']}";
		$.post(url,{'order_id':order_id},function(data){
			alert(data.msg);
			if(data.status){
				location.reload();
			}
		},'json')
	}
});
</script>
<include file="Public:footer"/>
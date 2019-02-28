<include file="Public:header"/>
	<form id="myform"  frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$supply_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr class="open_own" >
				<th width="90">修改配送状态为</th>
				<td>
					<select name="supply_id" id="change">
                        <option value="a" selected>请选择</option>
                        <option value="0">配送失败</option>
                        <option value="4">配送中</option>
                        <option value="5">配送成功</option>
					</select>
				</td>
			</tr>
        </table>
<!--            <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />-->
<!--            <input type="reset" value="取消" class="button" />-->
    </form>
<script type="text/javascript">
var supply_id = "{pigcms{$supply_id}";
        $(function () {
            $("#change").bind("change", function () {
                if (this.value == "0") {
                    window.top.art.dialog({
                        lock: true,
                        content: '修改为配送失败后，则该配送将终止，且将会对用户自动退款，修改后将不可恢复，请谨慎操作！',
                        ok: function () {
                            $.get("{pigcms{:U('Deliver/change_status')}", {supply_id: supply_id,status: 0}, function (response) {
                                if (response.error_code) {
                                    window.top.msg(0, response.msg);
                                } else {
                                    window.top.msg(1, response.msg, true);
                                    location.reload();
                                }
                            }, 'json');
                        },
                        cancel: function(){
                            $("#change option:first").attr("selected", 'selected');
                        }
                    });
                } else if(this.value == "5") {
                    window.top.art.dialog({
                        lock: true,
                        content: '您确定要将该配送订单修改成配送完成吗？修改后相应的订单状态变成已消费！',
                        ok: function () {
                            $.get("{pigcms{:U('Deliver/change_status')}", {supply_id: supply_id,status: 5}, function (response) {
                                if (response.error_code) {
                                    window.top.msg(0, response.msg);
                                } else {
                                    window.top.msg(1, response.msg, true);
                                    location.reload();
                                }
                            }, 'json');
                        },
                        cancel: function(){
                            $("#change option:first").attr("selected", 'selected');
                        }
                    });
                }else{
                    window.top.art.dialog({
                        lock: true,
                        content: '您确定要将该配送订单修改成配送中吗？修改后将不可恢复，请谨慎操作！',
                        ok: function () {
                            $.get("{pigcms{:U('Deliver/change_status')}", {supply_id: supply_id,status: 4}, function (response) {
                                if (response.error_code) {
                                    window.top.msg(0, response.msg);
                                } else {
                                    window.top.msg(1, response.msg, true);
                                    location.reload();
                                }
                            }, 'json');
                        },
                        cancel: function(){
                            $("#change option:first").attr("selected", 'selected');
                        }
                    });
                }
            });
        });
</script>
<include file="Public:footer"/>
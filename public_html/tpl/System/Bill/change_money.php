<include file="Public:header"/>
	
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">金额</th>
				<td><input type="text" class="input fl" name="money" size="75" placeholder="金额" value="" validate="maxlength:50"/></td>
			</tr>
		</table>
		
		（*只有点击 “确认对帐并在线提现” 按钮改变的金额才会生效！）<input type="button" value="确定" class="button" onclick="fun()" style="float:right" />
			

	<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<script type="text/javascript">
	var domid = art.dialog.data('domid');
	dom_id = domid.id;
	var money = art.dialog.data('money');
	$(document).ready(function(){
		$('input[name="money"]').val( Math.round(money)/100);
	});
	function fun(){
		// 返回数据到主页面
		var now_money = $('input[name="money"]').val();
		var origin = artDialog.open.origin;
		var dom = origin.document.getElementById(dom_id);
		var mer_desert = origin.document.getElementById('mer_desert');
		$(mer_desert).html('￥'+now_money);
		$(dom).val(Math.round(now_money*100));
		
		art.dialog.close();
	}
	</script>
<include file="Public:footer"/>
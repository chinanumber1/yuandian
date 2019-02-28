<volist name="redpack_list" id="vo">
	<div class="hong_item">
		<div class="hong_portrait">
			<img src="{pigcms{$vo.avatar|default='./static/images/user_avatar.jpg'}"/>
		</div>
		<div class="hong_iteminfo">
			<p>{pigcms{$vo.nickname}</p>
			<p class="hong_itemdate">{pigcms{:tmspan($vo['get_time'])}</p>
		</div>
		<div class="hong_itemamount">
			<p>{pigcms{$vo.money}元</p>
		</div>
	</div>
</volist>

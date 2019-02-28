<volist name="list" id="vo">
	<a href="{pigcms{:U('member',array('uid'=>$vo['visit_uid']))}" class="weui-cell">
		<div class="weui-cell__hd" style="position:relative;margin-right:10px;">
			<img src="{pigcms{$vo.avatar|default='./static/images/user_avatar.jpg'}" style="width:30px;height:30px;margin-right:5px;display:block;border-radius:50%"/>
		</div>
		<div class="weui-cell__bd">
			<p>{pigcms{$vo.nickname}</p>
		</div>
		<div class="weui-cell__ft">{pigcms{:tmspan($vo['time'])}</div>
	</a>
</volist>

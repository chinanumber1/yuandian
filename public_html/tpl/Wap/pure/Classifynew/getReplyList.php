<if condition="$list">
	<volist name="list" id="vo">
		<a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg cmt_p"  data-pubid="{pigcms{$vo.reply_id}" data-authorid="{pigcms{$vo.uid}" data-author="{pigcms{$vo.nickname}">
			<div class="weui-media-box__hd">
				<img class="weui-media-box__thumb" src="{pigcms{$vo.avatar|default='./static/images/user_avatar.jpg'}"/>
			</div>
			<div class="weui-media-box__bd">
				<h4 class="weui-media-box__title">
					<if condition="$vo['reply_uid']">
						<span>{pigcms{$vo.nickname} </span> 回复 <span>{pigcms{$vo.reply_nickname} :</span>
					<else/>
						<span>{pigcms{$vo.nickname}: </span>
					</if>
					<span class="y f13 c9">{pigcms{:tmspan($vo['add_time'])} </span>
				</h4>
				<p class="weui-media-box__desc">{pigcms{$vo.content}</p>
			</div>
		</a>
	</volist>
<else/>
	<div class="weui-cell">
		<div class="weui-cell__bd">
			<p class="c9 f14">暂无评论</p>
		</div>
	</div>
</if>
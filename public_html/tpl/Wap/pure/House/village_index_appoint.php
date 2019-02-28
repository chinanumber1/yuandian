<if condition="$appoint_list">
	<section class="appoint">
		<div class="headBox">推荐{pigcms{$config.appoint_alias_name}<div class="right link-url" data-url="{pigcms{:U('House/village_appointlist',array('village_id'=>$now_village['village_id']))}">更多</div></div>
		<dl class="likeBox dealcard">
			<volist name="appoint_list" id="vo">
				<dd class="link-url" data-url="{pigcms{$vo.url}">
					<div class="dealcard-img imgbox">
						<!--img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.name}"/-->
						<img src="/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.appoint_name}"/>   
					</div>
					<div class="dealcard-block-right">
						<div class="brand">{pigcms{$vo.appoint_name} <if condition="$vo['range']"><span class="location-right">{pigcms{$vo.range}</span></if></div>
						<div class="title" style="font-size:14px;margin:4px 0;"><if condition="$vo['payment_money']">定金:￥{pigcms{$vo.payment_money}<else/>无需定金</if>|{pigcms{$vo.appoint_content}</div>
						<div class="price">
							<if condition="$vo['appoint_type'] eq 1"><span class="imgLabel shangmen"></span><else/><span class="imgLabel daodian"></span></if>
							<if condition="$vo['appoint_sum']"><span class="line-right">已预约{pigcms{$vo.appoint_sum}</span></if>
						</div>
					</div>       
				</dd>
			</volist>
		</dl>
	</section>
</if>
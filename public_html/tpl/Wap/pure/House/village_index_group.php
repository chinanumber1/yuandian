<if condition="$group_list">
	<section class="group">
		<div class="headBox">推荐{pigcms{$config.group_alias_name}<div class="right link-url" data-url="{pigcms{:U('House/village_grouplist',array('village_id'=>$now_village['village_id']))}">更多</div></div>
		<dl class="likeBox dealcard">
			<volist name="group_list" id="vo">
				<dd class="link-url" data-url="{pigcms{$vo.url}">
					<div class="dealcard-img imgbox">
						<!--img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.name}"/-->
						<img src="/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.name}"/>
					</div>
					<div class="dealcard-block-right">
						<div class="brand"><if condition="$vo['tuan_type'] neq 2">{pigcms{$vo.merchant_name}<else/>{pigcms{$vo.s_name}</if><if condition="$vo['range']"><span class="location-right">{pigcms{$vo.range}</span></if></div>
						<div class="title">[{pigcms{$vo.prefix_title}]{pigcms{$vo.group_name}</div>
						<div class="price">
							<strong>{pigcms{$vo['price']}</strong><span class="strong-color">元</span>
                            <if condition="$vo['wx_cheap']"><span class="tag"><if condition="!$is_app_browser">微信<else/>APP</if>再减{pigcms{$vo.wx_cheap}元</span></if>
                            <if condition="$vo['sale_count']+$vo['virtual_num']"><span class="line-right">已售{pigcms{$vo['sale_count']+$vo['virtual_num']}</span></if>
						</div>
					</div>
				</dd>
			</volist>
		</dl>
	</section>
</if>
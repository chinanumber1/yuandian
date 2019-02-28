<if condition="$meal_list">
	<section class="meal">
		<div class="headBox">推荐{pigcms{$config.meal_alias_name}<div class="right link-url" data-url="{pigcms{:U('House/village_meallist',array('village_id'=>$now_village['village_id']))}">更多</div></div>
		<dl class="likeBox dealcard">
			<volist name="meal_list" id="vo">
				<dd class="link-url" data-url="{pigcms{$vo.wap_url}">
					<div class="dealcard-img imgbox">
						<!--img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.name}"/-->
						<img src="/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.name}"/>
					</div> 
					<div class="dealcard-block-right">
						<div class="brand">{pigcms{$vo.name} <if condition="$vo['range']"><span class="location-right">{pigcms{$vo.range}</span></if></div>
						<div class="title" style="font-size:14px;margin:4px 0;">{pigcms{$vo.adress}<if condition="$vo['mean_money']">|人均{pigcms{$vo.mean_money}元</if></div>
						<div class="price">
							<if condition="$vo['store_type'] eq 0 || $vo['store_type'] eq 1"><span class="imgLabel daodian"></span></if><if condition="$vo['store_type'] eq 0 || $vo['store_type'] eq 2"><span class="imgLabel waisong"></span></if>
							<if condition="$vo['sale_count']"><span class="line-right">已售{pigcms{$vo.sale_count}</span></if>
						</div>
					</div>
				</dd>
			</volist>
			
		</dl>
	</section>
</if>
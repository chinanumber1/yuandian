<if condition="$news_list">
	<section class="villageBox newsBox">
		<div class="headBox">社区新闻<div class="right link-url" data-url="{pigcms{:U('House/village_newslist',array('village_id'=>$now_village['village_id']))}">更多</div></div>
		<dl>
			<volist name="news_list" id="vo">
				<dd class="link-url" data-url="{pigcms{:U('House/village_news',array('village_id'=>$now_village['village_id'],'news_id'=>$vo['news_id']))}">
					<div>{pigcms{$vo.title}</div>
					<span class="right">{pigcms{$vo.add_time|date='m-d H:i',###}</span>
				</dd>
			</volist>
		</dl>
	</section>
</if>
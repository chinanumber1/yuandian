<if condition="$activity_list['list']">
	<section class="villageBox newsBox">
		<div class="headBox">社区活动<div class="right link-url" data-url="{pigcms{:U('House/village_activitylist',array('village_id'=>$now_village['village_id']))}">更多</div></div>
		<dl>
			<volist name="activity_list['list']" id="vo">
				<dd class="link-url" data-url="{pigcms{:U('House/village_activity',array('village_id'=>$now_village['village_id'],'id'=>$vo['id']))}">
					<div>{pigcms{$vo.title}</div>
					<if condition='($vo["remain_num"] eq 0) && (isset($vo["remain_num"]))'>
					<span class="right" style="color:red">报名已满</span>
					<elseif condition="time() gt $vo['apply_end_time']" />
					<span class="right" style="color:red">报名已截止</span>
					<else/>
					<span class="right">报名截止：{pigcms{$vo.apply_end_time|date='m-d',###}</span>
					</if>
				</dd>
			</volist>
		</dl>
	</section>
</if>
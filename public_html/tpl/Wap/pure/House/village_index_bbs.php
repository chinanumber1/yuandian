<if condition="$bbs_article_list">
	<section class="villageBox bbsBox">
		<div class="headBox">热门话题<div class="right link-url" data-url="{pigcms{:U('Bbs/web_index',array('village_id'=>$now_village['village_id']))}">更多</div></div>
		<dl>
			<volist name="bbs_article_list['aricle']" id="vo">
				<dd class="link-url <if condition='$i eq 1'>first</if>" data-url="{pigcms{$vo.url}">
					<div class="articleImg">
						<img src="{pigcms{$vo.aricle_img}" height='119px' width="100%"/>
					</div>
					<div class="articleTxt">
						<div class="artileTitle">{pigcms{$vo.aricle_title}</div>
						<div class="artileDesc clearfix">
							<div class="left">{pigcms{$vo.update_time|date='m-d',###}</div>
							<div class="right">
								<div class="up">{pigcms{$vo.aricle_praise_num}</div>
								<div class="comment">{pigcms{$vo.aricle_comment_num}</div>
							</div>
						</div>
					</div>
				</dd>
			</volist>
		</dl>
	</section>
</if>
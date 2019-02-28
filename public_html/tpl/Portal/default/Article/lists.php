<include file="Public/header" />
<div class="content w-1200 clearfix">
	<!-- 主体 -->
        <div class="crumb-bar clearfix">
            <span>当前位置：</span>
            <a href="{pigcms{:U('Index/index')}">首页</a>
            <if condition="$fcid_info neq ''">
            	<a href="{pigcms{:U('Article/lists',array('fcid'=>$fcid_info['cid']))}">{pigcms{$fcid_info.cat_name}</a>
        	</if>
        	<if condition="$cid_info neq ''">
            	<a href="{pigcms{:U('Article/lists',array('cid'=>$cid_info['cid']))}">{pigcms{$cid_info.cat_name}</a>
        	</if>
            <span class="cur_tit"></span>
        </div>
		<div class="clearfix" style="margin-top:12px;">
			<div class="zx_col_sub">
				<div id="fixed" style="width:143px;">
				<div class="zx-side-menu" id="list_nav_2013">
				 <div class="title"><a href="{pigcms{:U('Article/lists')}">全部</a></div>
					<ul>
					<if condition="$article_cates">
					<volist name="article_cates" key="k" id="vo">
						<li class="item <if condition="$vo['cid'] eq $fcid">open</if>">
   							<a href="{pigcms{:U('Article/lists',array('fcid'=>$vo['cid']))}">{pigcms{$vo.cat_name}</a>
							<i class="rights" onclick="showHide(this,'items{pigcms{$k}');"></i>
							<ul class="items ul" id="items{pigcms{$k}" style="display:<if condition="$vo['cid'] eq $fcid">block<else/>none;</if>;">
							<li <if condition="$vo['cid'] eq $fcid and $cid eq 0">class="cur"</if>><a href="{pigcms{:U('Article/lists',array('fcid'=>$vo['cid']))}">全部分类</a></li>
							<if condition="$vo['childs']">
							<volist name="vo['childs']" id="vv">
								<li <if condition="$vv['cid'] eq $cid">class="cur"</if>><a href="{pigcms{:U('Article/lists',array('cid'=>$vv['cid']))}">{pigcms{$vv.cat_name}</a></li>
							</volist>
							</if>
							</ul>    
						</li>
					</volist>
					</if>
					</ul>
				</div>
	
				</div>
			</div>
			<div class="zx-list-rside">
			  <div class="zx-hotnews">
				<h4>精彩图文</h4>
				<ul>
				<if condition="$hot_img_news">
				<volist name="hot_img_news" id="vo">
					<li>
						<a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank"><img src="{pigcms{$vo.thumb}" alt="" /></a>
						<p><a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title}</a></p>
					</li>
				</volist>
				</if>
				</ul>
			  </div>
			</div>
			<div class="zx-listcon zx-listcon2" id="fixed_can">
				<div class="zx-mcon">
					<div class="list">
						<ul>
						<if condition="$article_list">
						<volist name="article_list" id="vo">
							<if condition="$vo['thumb'] neq './tpl/System/Static/images/addimg.jpg'">
								<li class="img">
									<div class="pic">
									    <a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">
									   		<img src="{pigcms{$vo.thumb}">
									    </a>
									</div>
									<div class="con">
										<h3><a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title}</a></h3>
										<p>{pigcms{$vo.desc}</p>
										<dl class="clearfix">
											<dt class="time">{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}</dt>
											<dd class="browse">{pigcms{$vo.PV}</dd>
										</dl>
									</div>
								</li>
							<else/>
								<li>
									<div class="con">
										<h3>
											<h3><a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title}</a></h3>
										</h3>
										<p>{pigcms{$vo.desc}</p>
										<dl class="clearfix">
											<dt class="time">{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}</dt>
											<dd class="browse">{pigcms{$vo.PV}</dd>
										</dl>
									</div>
								</li>
							</if>

							

						</volist>
						</if>
						<li class="line">&nbsp;</li>
							
						</ul>
					</div>
					<div class="pageNavigation">
						{pigcms{$pagebar}
					</div>
					
				</div>
			</div>
		</div>
	<!-- 主体 结束 -->
	</div>
<include file="Public/footer" />
<script>
$(function() {
	$('#mySle').selectbox();
	$(document).modCity();
	$('#fabu').showMore();
	$('#weixin').showMore();
	$.returnTop();
	$('#fixed').fixed($('#fixed_can'));
	$('#list_nav_2013').find('.cur').parent().parent().find('.rights').removeClass().addClass('xias').parent().addClass('open');
});
</script>
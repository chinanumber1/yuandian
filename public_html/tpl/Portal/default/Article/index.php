<include file="Public/header" />
<div class="content w-1200 clearfix">
		<div class="crumb-bar clearfix"><span>当前位置：</span><a href="{pigcms{:U('Article/index')}">首页</a> <span class="cur_tit">本地资讯</span></div>
		<div class="advs mar10" style="margin-bottom:10px;">
			<a href="{pigcms{$portal_article_index_top[0]['url']}" target="_blank"><img src="{pigcms{$portal_article_index_top[0]['pic']}" alt="{pigcms{$portal_article_index_top[0]['name']}" /></a>		
		</div>
			
		<div class="clearfix">
			<div class="zx-lside">
				<div class="public-no" id="newsWeixin">
					<h4>微信公众号</h4>
					<dl>
						<dt>{pigcms{$config.site_name}</dt>
						<dd class="clearfix">
							<tt>微信号：</tt>
							<span class="weixinuser">{pigcms{$config.wechat_name}</span>

							<a href="#" onMouseOver="wxOpen(1)" onMouseOut="wxOpen(2)" class="wxgz">加关注</a>
							<div class="po po_ab"><h6>打开微信扫一扫</h6><img src="{pigcms{$config.wechat_qrcode}" alt="" /><p>{pigcms{$config.wechat_name}</p></div>
							<script>
								function wxOpen(type){
									if(type == 1){
										$(".clearfix").addClass('open');
									}else{
										$(".clearfix").removeClass('open');
									}
								}
							</script>
						</dd>
					</dl>
				</div>
				<div class="zx-arclist">
				  <div class="zx-title clearfix">
					  <h4>最新快讯</h4>
					  <span class="zx-more"><a href="{pigcms{:U('Article/lists')}" target="_blank">更多</a></span>
				  </div>
					<ul>
					<if condition="$news_article">
					<volist name="news_article" id="vo">
						<li><a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title}</a></li>
					</volist>
					</if>
					</ul>
				</div>
				<div class="adv"><a href="{pigcms{$portal_article_index_left[0]['url']}"><img style="width: 300px; height: 139px;" src="{pigcms{$portal_article_index_left[0]['pic']}" alt="{pigcms{$portal_article_index_left[0]['name']}" /></a></div>
				<div class="zx-arclist top">
					<div class="zx-title no-b clearfix">
						<h4>贴吧精华推荐</h4>
						<span class="zx-more"><a href="{pigcms{:U('Tieba/index',array('essence'=>1))}" target="_blank">更多</a></span>
					</div>
					<ul>
						<volist name="essenceTiebaList" id="vo">
							<li>
								<a href="{pigcms{:U('Tieba/detail',array('tie_id'=>$vo['tie_id']))}" target="_blank">{pigcms{$vo.title}</a>
							</li>
						</volist>
					</ul>
				</div>
				<div class="zx-arclist top">
					<div class="zx-title no-b clearfix">
						<h4>贴吧新帖</h4>
						<span class="zx-more"><a href="{pigcms{:U('Tieba/index')}" target="_blank">更多</a></span>
					</div>
					<ul>
						<volist name="addTiebaList" id="vo">
							<li>
								<a href="{pigcms{:U('Tieba/detail',array('tie_id'=>$vo['tie_id']))}" target="_blank">{pigcms{$vo.title}</a>
							</li>
						</volist>
					</ul>
				</div>
			</div>
			<div class="zx-middle">
				<div class="slide-img">
					<div class="slide">
						<ul class="hd dot" id="tabs"><volist name="portal_article_round" id="kvo"><li></li></volist></ul>
						<div class="bd">
							<ul class="picList clearfix" id="output">
								<volist name="portal_article_round" id="vo">
									<li>
										<div class="pic">
											<a href="{pigcms{$vo.url}" target="_blank">
												<img src="{pigcms{$vo.pic}" alt="" />							
											</a>
										</div>
										<div class="title">
											<a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.name}</a>
										</div>
										<div class="bg"></div>
									</li>
								</volist>
							</ul>
						</div>
						<a class="next" href="javascript:void(0)"></a>
						<a class="prev" href="javascript:void(0)"></a>
					</div>
				</div>
				<div class="zx-mcon" id="tab01">
					<div class="zx-title zx-tabs no-b clearfix">
						<ul class="clearfix tab-hd">
                        <if condition="$labels">
                        <volist name="labels" key="k" id="vo">
                         <li <if condition="$k eq 0"> class="selected"</if>><a href="#">{pigcms{$vo.title}</a></li>
                        </volist>
                        </if>
						</ul>
						<span class="zx-more"><a href="{pigcms{:U('Article/lists')}">更多</a></span>
					</div>
					<div class="list">
                    <if condition="$labels">
                    <volist name="labels" id="vo">
                        <if condition="$vo['article_list']">
                        <ul class="tab-cont">
                        <volist name="vo['article_list']" id="vv">
							<li class="hasPic1">
                                <div class="pic">
                                    <a href="{pigcms{:U('Article/detail',array('aid'=>$vv['aid']))}" target="_blank">
                                        <if condition="$vv['thumb'] neq ''">
                                        <img src="{pigcms{$vv.thumb}">
                                        <else/>
                                        <img src="{pigcms{$static_path}public/images/livelistnopic.gif"/>
                                        </if>
                                    </a>
                                </div>
                                <div class="con">
                                    <h3 style="width: 400px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><a href="{pigcms{:U('Article/detail',array('aid'=>$vv['aid']))}" target="_blank">{pigcms{$vv.title}</a></h3>
                                    <a href="{pigcms{:U('Article/detail',array('aid'=>$vv['aid']))}" target="_blank"><p>{pigcms{$vv.desc}</p></a>
                                    <dl class="clearfix">
                                        <dt class="time">{pigcms{$vv.dateline|date="Y-m-d H:i:s",###}</dt>
                                        <dd class="browse">{pigcms{$vv.PV}</dd>
                                    </dl>
                                </div>
                            </li>
                        </volist>
                        </ul>
                        </if>
                    </volist>
                    </if>
						<div class="zx-more2"><a href="{pigcms{:U('Article/lists')}">更多资讯<i></i></a></div>
					</div>
				</div>
			</div>
			<div class="zx-rside">
			  <div class="zx-search clearfix">
				  <dl class="stit" id="sel_search">
					<i></i>
					<dt class="title">标题</dt>
					<dd>
						<ul class="list">
							<li><a href="#" data-val="0">标题</a></li>
						</ul>
					</dd>
				  </dl>
				  <div>
					<input type="text" id="keyword" placeholder="请输入关键字" value="" />
					<button class="so" onclick="search()">搜索</button>
                    <script type="text/javascript">
                        // 搜索
                        function search(){
                            var keyword = $.trim($('#keyword').val());
                            if(keyword == ''){
                                return;
                            }
                            window.location.href="{pigcms{:U('Article/lists')}&keyword=" + keyword;    
                        }
                    </script>
				  </div>
			  </div>
			  <div class="zx-indreco">
				  <div class="zx-title clearfix">
					  <h4>特别推荐</h4>
				  </div>
				  <div class="slide2">
					  <div class="list bd">
						  <ul id="">
                            <if condition="$recommend_list">
                            <volist name="recommend_list" id="vo">
							  <li>
                                <div class="pic">
                                    <a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">
                                        <if condition="$vo['thumb'] neq ''">
                                        <img src="{pigcms{$vo.thumb}">
                                        <else/>
                                        <img src="{pigcms{$static_path}public/images/livelistnopic.gif"/>
                                        </if>
                                    </a>
                                </div>
                                  <div class="con">
                                      <h5><a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title}</a></h5>
                                      <p>{pigcms{$vo.dateline|date="Y-m-d",###}</p>
                                  </div>
                              </li>
                            </volist>
                            </if>
						  </ul>
					  </div>
				  </div>
			  </div>

			  <div class="adv"><a href="{pigcms{$portal_article_index_right[0]['url']}"><img  style="width: 300px; height: 198px;" src="{pigcms{$portal_article_index_right[0]['pic']}" alt="{pigcms{$portal_article_index_right[0]['name']}" /></a></div>
				<div class="zx-arclist top">
					<div class="zx-title no-b clearfix">
						<h4>热点排行榜</h4>
					</div>
					<ul>
                        <if condition="$hot_news">
                        <volist name="hot_news" id="vo">
                            <li><a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title}</a></li>
                        </volist>
                        </if>
					</ul>
				</div>
				<div class="wonderful-graphic">
				<div class="zx-title no-b clearfix">
					<h4>精彩图文</h4>
				</div>
				<ul class="clearfix">
                <if condition="$hot_img_news">
                <volist name="hot_img_news" id="vo">
					<li>
                      <a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank"><img src="{pigcms{$vo.thumb}" alt=""></a>
                      <p><a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title}</a></p>
                      <s></s>
                    </li>
                </volist>
                </if>
				</ul>
			</div>
		   </div>
		</div>
		
	</div>
	</div>
<include file="Public/footer" />
<div class="zx-ind-rmenu">
	<ul>
	<if condition="$cate_list">
	<volist name="cate_list" id="vo">
		<li>
			<a href="{pigcms{:U('Article/lists',array('fcid'=>$vo['cid']))}">{pigcms{$vo.cat_name}</a>
		</li>
	</volist>
	</if>
		<li class="top">
			<a href="#" id="iGo2Top" style="display: block;">top</a>
		</li>
	</ul>
</div>
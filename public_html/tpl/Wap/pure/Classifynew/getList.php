<if condition="$list">
	<volist name="list" id="vo">
		<div class="li newli" id="li_{pigcms{$vo.id}">
			<div class="po-avt-wrap">
				<a href="{pigcms{:U('member',array('uid'=>$vo['uid']))}">
					<img class="po-avt" src="{pigcms{$vo.avatar|default='./static/images/user_avatar.jpg'}"/>
				</a>
			</div>
			<div class="po-cmt">
				<div class="po-hd cl">   
					<a class="abs " href="{pigcms{:U('category',array('cat_id'=>$vo['cat_id']))}">{pigcms{$vo.cat_name}</a>
					<div class="usr-name mod-usr-name lv">
						<span class="name">{pigcms{$vo.nickname|msubstr=###,0,15}</span>
						<if condition="$vo['topsort']">
							<div class="mod-lv is-star">置顶</div>
						</if>
						<if condition="$vo['redpack_count']">
							<div class="mod-lv is-hot view_jump" data-id="{pigcms{$vo.id}" data-stid="0">红包</div>
						</if>
					</div>
					<div class="post cl">
						<if condition="$vo['content_label']">
							<div class="cl mt8 item_tags view_jump" data-id="{pigcms{$vo.id}" data-stid="0">
								<volist name="vo['content_label']" id="voo" key="kk">
									<span class="mod-feed-tag b-color{pigcms{$kk}">{pigcms{$voo}</span>
								</volist>
							</div>
						</if>
						<div id="view_jump_{pigcms{$vo.id}" class="view_jump mod-feed-text is-three cl" data-id="{pigcms{$vo.id}" data-stid="0">
							{pigcms{$vo.description}
							<span class="block"><span class="main_color">联系人：</span>{pigcms{$vo.lxname}</span>
						</div>
						<a class="showfull main_color" id="showfull_{pigcms{$vo.id}" onclick="showfull('{pigcms{$vo.id}', '{pigcms{$vo.lxtel}', '','');return false;">全文</a>
						<div class="cl feed-preview-pic">
							<php>if($vo['imgs']){$imgsArr = unserialize($vo['imgs']);$imgsArr = array_slice($imgsArr,0,3);}</php>
							<if condition="$vo['imgs']">
								<volist name="imgsArr" id="imgvo">
									<span class="imgloading view_jump" data-id="{pigcms{$vo.id}" data-stid="0">
										<img src="{pigcms{$imgvo}"/>
									</span>
								</volist>
							</if>
						</div>
					</div>
					<if condition="$_GET['hongbao']">
						<div class="weui-flex pr">
							<div class="weui-flex__item">
								<span class=" color-red" >
									<i class="iconfont icon-hongbao3 f18"></i> 
									<span class="f12">&yen;</span>
									<span class="f20">{pigcms{$vo.redpack_money}</span>
									<span class="f12">元</span>
								</span>
							</div>
							<if condition="$vo['redpack_count'] eq $vo['redpack_count_get']">
								<div class="color-gray f12 hlisttip">来晚一步，抢光了</div>  
							<else/>
								<div class="color-red f12 hlisttip">抢红包进行中……</div>
							</if>
						</div>
					<else/>
						<div class="cl pr footer">
							<p class="time">
								<span><em>{pigcms{$vo.views}</em>浏览，</span>
								<if condition="$vo['shares']"><span><em>{pigcms{$vo.shares}</em>分享，</span></if>
								<span>{pigcms{:date('m-d',$vo['updatetime'])}发布</span>
							</p>
							<if condition="$vo['uid'] neq $user_session['uid']">
								<i class="c-icon iconfont icon-qunawanhuifu footer-show-handle" id="c_icon_{pigcms{$vo.id}"></i>
								<div class="touch-panel animated opannel">
									<div class="touch-panel-c weui-flex">
										<if condition="$_GET['type'] neq 'collect'">
											<a href="javascript:void(0)" class="weui-flex__item praise" data-id="{pigcms{$vo.id}" data-href="{pigcms{:U('collectOpt',array('vid'=>$vo['id']))}"><i id="praise_{pigcms{$vo.id}" class="iconfont icon-jinlingyingcaiwangtubiao44"></i>收藏</a>
										<else/>
											<a href="javascript:void(0)" class="weui-flex__item praise" data-id="{pigcms{$vo.id}" data-href="{pigcms{:U('collectOpt',array('vid'=>$vo['id'],'type'=>'cancle'))}"><i id="praise_{pigcms{$vo.id}" class="iconfont icon-jinlingyingcaiwangtubiao24"></i>取消</a>
										</if>
										<a href="tel:{pigcms{$vo.lxtel}" class="weui-flex__item"><i class="icon-dianhua2 iconfont"></i>电话</a>
										<if condition="$config['is_im']">
											<a href="{pigcms{:U('My/go_im',array('hash'=>'group_user'.$vo['uid'],'title'=>urlencode($vo['nickname'])))}" class="weui-flex__item"><i class="icon-sixin2 iconfont"></i>私信</a>                    
										</if>
									</div>
								</div>
							<else/>
								<a class="a c_opt" href="javascript:;" id="pubitem_{pigcms{$vo.id}" data-id="{pigcms{$vo.id}" data-uid="{pigcms{$vo.uid}" data-wc="0" data-canzd="1" data-canhb="<if condition="$vo['redpack_count'] eq 0">1<else/>0</if>" onclick="return showansi(this);">操作/扩散</a>
							</if>
						</div>
					</if>
				</div>
				<div class="r" id="r_{pigcms{$vo.id}" style="display:none"></div>
				<div class="cmt-wrap" id="cmt_wrap_{pigcms{$vo.id}" style="display:none">
					<div class="like cl">
						<span class="likenum c9"><em id="praises_{pigcms{$vo.id}">0</em>赞</span>
						<span class="likeuser z" id="praise_list_{pigcms{$vo.id}"></span>
					</div>
				</div>
			</div>
			<if condition="$vo['uid'] eq $user_session['uid']">
				<div class="po-act">
					<a class="weui-btn weui-btn_mini p0 mt0" href="javascript:;" onclick="hb_dig('{pigcms{$vo.id}');">置顶</a>
					<if condition="$vo['redpack_count'] eq 0">
						<a class="weui-btn weui-btn_mini p0 mt0" href="javascript:;" onclick="hb_hbchoice('{pigcms{$vo.id}');">红包</a>
					</if>
					<a class="weui-btn weui-btn_mini p0 mt0" href="javascript:;" onclick="hb_shuaxin('{pigcms{$vo.id}');">刷新</a>
					<a class="weui-btn weui-btn_mini p0 mt0" href="javascript:;" onclick="$('#pubitem_{pigcms{$vo.id}').trigger('click');">更多</a>
				</div>
			</if>
		</div>
	</volist>
	<style>
		.newli .po-hd{
			overflow:visible;
		}
		.newli .footer{
			margin-right: -15px;
			overflow: hidden;
		}
		.newli .footer-show-handle{
			padding-right: 15px;
		}
		.newli .touch-panel{
			right:47px;
		}
	</style>
</if>
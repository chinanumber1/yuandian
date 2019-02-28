<!doctype html>
<html>
<head>
	<include file="header"/>
</head>
<body>
	<div class="weui-pull-to-refresh__layer">
		<div class='weui-pull-to-refresh__arrow'></div>
		<div class='weui-pull-to-refresh__preloader'></div>
		<div class="down">下拉刷新</div>
		<div class="up">释放刷新</div>
		<div class="refresh">正在刷新</div>
	</div>
	<link rel="stylesheet" href="{pigcms{$static_path}classifynew/css/cropper.css"/>
	<style>.x_header{z-index:10}.weui-cells_form .weui-cell__ft{font-size:17px}</style>
	<if condition="!$is_wexin_browser && !$is_app_browser">
		<header class="x_header bgcolor_11 cl f15">
			<a class="z f14" href="javascript:window.history.go(-1);"><i class="iconfont icon-fanhuijiantou w15"></i>返回</a>
			<a class="y sidectrl " href="{pigcms{:U('about')}">帮助</a>    
			<div class="navtitle">{pigcms{$page_title}</div>
		</header>
		<div class="x_header_fix" ></div>
	</if>
	<div class="page__bd">
		<div class="weui-cells__title">
			【特别提醒】请认真全面填写信息，以便大家查阅。<br/>
			【免责声明】发布的所有信息，平台只负责发布、展示，与平台本身无关，不承担任何责任。</div>    <div class="weui-cells">
		</div>
		<div class="weui-cells__title">请选择发布类别</div>
		<div class="weui-grids bgf weui-grids-nob">
			<volist name="classify_first_category" id="vo">
				<a onclick="return showcat('{pigcms{$vo.cid}', '{pigcms{$vo.cat_name}');" class="weui-grid js_grid">
					<div class="weui-grid__icon">
						<img src="{pigcms{$vo.cat_pic}" alt="{pigcms{$vo.cat_name}"/>
					</div>
					<p class="weui-grid__label">{pigcms{$vo.cat_name}</p>
				</a>
			</volist>
		</div>
		<script>
			var CAT = [];
			<volist name="classify_second_category" id="vo">
				CAT.push({pid:'{pigcms{$vo.fcid}', id:'{pigcms{$vo.cid}', name:'{pigcms{$vo.cat_name}'});
			</volist>
		</script>
		<script>
			function showcat(id, name){
				var act = [];
				for(var i =0; i<CAT.length; i++){
					if(CAT[i].pid==id){
						var surl = "{pigcms{:U('fabu_detail')}&fcid="+CAT[i].pid+"&cid="+CAT[i].id+_URLEXT;
						act.push({text: '<a class="sel_a" href="'+surl+'">'+CAT[i].name+'</a>'});
					}
				}
				console.log(act);
				if(act.length==0){
					tip_common('error|此分类暂时不能发布');
					return false;
				}
				$.actions({
					title: '发布'+name+'',
					actions: act
				});
				return false;
			}
		</script>
    </div>
	<div id="popctrl" class="weui-popup__container">
		<div class="weui-popup__overlay"></div>
		<div class="weui-popup__modal">
			<div style="height:100vh"><img id="photo"></div>
			<div class="pub_funcbar">
				<a class="weui-btn close-popup weui-btn_primary" data-method="confirm">确定</a>
				<a class="weui-btn close-popup weui-btn_default" data-method="destroy">取消</a>
			</div>
		</div>
	</div>
	
	<div class="masker" style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5);display:none;z-index:1000" onclick='$("#choose_sh").select("close")'></div>
	<div class="cl footer_fix"></div>
	<div class="weui-tabbar">
		<a href="{pigcms{:U('index')}" class="weui-tabbar__item">
			<i class="iconfont icon-index weui-tabbar__icon"></i>
			<p class="weui-tabbar__label">首页</p>
		</a>
        <a href="{pigcms{:U('hongbao')}" class="weui-tabbar__item">
			<i class="iconfont icon-hongbao2 weui-tabbar__icon"></i>
			<p class="weui-tabbar__label">红包</p>
		</a>
		<a href="{pigcms{:U('fabu')}" class="weui-tabbar__item weui-bar__item_on showpubfont">
			<div class="pub_circle"></div>
			<i class="iconfont icon-fabuhei weui-tabbar__icon"></i>
			<p class="weui-tabbar__label pub_circle_p" style="color:#777!important">发布</p>    
		</a>
        <a href="{pigcms{:U('collect')}" class="weui-tabbar__item ">
			<i class="iconfont icon-jieban weui-tabbar__icon"></i>
			<p class="weui-tabbar__label">收藏</p>
		</a>
		<a href="{pigcms{:U('my')}" class="weui-tabbar__item ">
			<span style="display: inline-block;position: relative;">
				<i class="iconfont icon-xiaolian2 weui-tabbar__icon"></i>
			</span>
			<p class="weui-tabbar__label">我的</p>
		</a>
	</div>
	<if condition="$is_wexin_browser">
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Classifynew/index')}",
				"tTitle": "{pigcms{$page_title}",
				"tContent": "{pigcms{$config.site_name}"
			};
		</script>
		{pigcms{$shareScript}
	</if>
	<include file="footer"/>
	<if condition="$config['is_demo_domain']">
		<script>
			var isShow = hb_getcookie('classifyFabuDemoTip');
			if(!isShow){
				demoDomain_tip("每个分类都是在系统后台添加，同时可以根据分类特征在后台设置自定义填写项，例如房屋分类是否需要填写房型、楼层，均可自定义，发布后其他用户浏览可见。同时也可设置为分类筛选项其他用户进行筛选查看，每分类最多支持4个筛选项。", "发布页面使用提醒");
				hb_setcookie('classifyFabuDemoTip','1',86400*365);
			}
		</script>
	</if>
</body>
</html>
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
	<style>
	.hong_res{position: relative}
	.footer_fix{display:none}
	</style>
	<div class="page__bd hong">
		<if condition="!$is_wexin_browser && !$is_app_browser">
			<header class="x_header bgcolor_11 cl f15">
				<a class="z f14" href="javascript:window.history.go(-1);"><i class="iconfont icon-fanhuijiantou w15"></i>返回</a>
				<a class="y sidectrl " href="{pigcms{:U('My/my_money')}">我的钱包</a>    
			</header>
		</if>
		<div class="hong_res animated zoomIn" style="display:block">
			<div class="hong_res_wrap">
				<div class="hong_res_head">
					<div class="hong_res_head_in">
						<img src="{pigcms{$user.avatar|default='./static/images/user_avatar.jpg'}"/>
					</div>
				</div>
				<div class="hong_res_cnt">
					<div class="hong_res_box">
						<p>{pigcms{$user.nickname}</p>
						<p>埋了一个红包</p>
					</div>
					<div class="hong_list_outer" style="display: block">
						<div class="hong_list_h weui-flex">
							<span></span>
							<p class="weui-flex__item tit js-cnt">共<span id="total">{pigcms{$detail.redpack_count}</span>个红包，已被挖<span id="sendnum">{pigcms{$detail.redpack_count_get}</span>份，还剩<span id="over">{pigcms{$detail['redpack_count']-$detail['redpack_count_get']}</span>份</p>
							<span></span>
						</div>
						<div class="hong_list" id="hong_list">

						</div>
					</div>
				</div>
				<div class="sub_bg"></div>
			</div>
		</div>
	</div>
	<div class="cl footer_fix"></div>
	<include file="footer"/>
	<script>
		var loadingurl = '{pigcms{:U('hongbao_getlist',array('id'=>$_GET['id']))}&page=';
		$(document.body).infinite().on("infinite", function() {
			if(loading) return;
			loading = true;
			load_morehong();
		});
		load_morehong();

		function load_morehong(){
			if(page<=0){
				return ;
			}
			$.ajax({
				type: 'post',
				url: loadingurl+''+page,
				success: function (data) {
					if(!data){
						page = -1;
						return ;
					}
					$("#hong_list").append(data);
					loading = false;
					console.log(page);
					page ++;
				},
				error: function() {
					loading = false;
				}
			});
		}
	</script>
</body>
</html>
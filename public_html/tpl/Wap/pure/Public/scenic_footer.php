		<!-- 悬浮 -->
		<div class="suspension">
			<div class="suspension_n">
				<ul>
					<if condition="$config['many_city']">
						<li><a href="{pigcms{:U('Scenic_index/index')}"><img src="{pigcms{$static_path}scenic/images/syxft_01.png">首页</a></li>
						<li><a href="{pigcms{:U('Scenic_index/all_city')}"><img src="{pigcms{$static_path}scenic/images/syxft_02.png">城市</a></li>
					<else/>
						<li><a href="{pigcms{:U('Scenic_index/city_index')}"><img src="{pigcms{$static_path}scenic/images/syxft_01.png">首页</a></li>
					</if>
					<li><a href="{pigcms{:U('Scenic_user/index')}"><img src="{pigcms{$static_path}scenic/images/syxft_03.png">我的</a></li>
					<li><a href="{pigcms{:U('Scenic_index/search')}"><img src="{pigcms{$static_path}scenic/images/syxft_06.png">搜索</a></li>
				</ul>
			</div>
			<div class="susp-img"></div>
		</div>
	</body>
	<script type="text/javascript">
		$(".susp-img").click(function(){
			$(".suspension_n").toggle(200);
		})
		$(".suspension_n li").last().css("border","none");
	</script>
</html>
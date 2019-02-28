<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/list_page.css"/>
		<script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
	</head>
	
	<body>
		<header class="after">
			<if condition="$_GET['type'] eq 1">
				<a href="{pigcms{:U('Yuedan/index')}"><i></i></a>
			<else/>
				<a href="{pigcms{:U('Yuedan/cat_list')}"><i></i></a>
			</if>
			
			<span>{pigcms{$categoryInfo['cat_name']}</span>
			<b onclick="search()"></b>
		</header>
		<script>
			function search(){
				var cid = "{pigcms{$_GET['cid']}"
				if(cid){
					location.href = "{pigcms{:U('search')}&cid="+cid;
				}else{
					location.href = "{pigcms{:U('search')}";
				}
			}
		</script>
		<section>
			<div class="screen">
				<ul class="after">
					<li class="ft click_sort"><span class="click_sort_text"></span><i></i></li>
					<li class="ft click_screen"><span>筛选</span> <i></i></li>
				</ul>
			</div>
			<!--个性服务-->
			

				
				
			

			<if condition="is_array($service_list)">
				<div class="personality">
					<volist name="service_list" id="vo">
						<a href="{pigcms{:U('Yuedan/service_detail',array('rid'=>$vo['rid']))}">
							<div class="sevice" style="height: 155px">
								<!-- <img src="{pigcms{$vo.listimg}"/> -->
								<p style="display:inline-block;display:inline-block; width:140px;background: transparent url({pigcms{$vo.listimg}) no-repeat 0% 0px;background-size:cover; height:140px;text-align: center;"></p>
								<ul>
									<li>{pigcms{$vo.title}</li>
									<li><b>￥{pigcms{$vo.price}</b><span>/{pigcms{$vo.unit}</span></li>
									<li><i>{pigcms{$vo.nickname}</i></li>
								</ul>
							</div>
						</a>
					</volist>
				</div>
			<else/>
				<div style="text-align: center; margin-top: 40%; font-size: 20px; color: red;">暂无数据!</div>
			</if>

		</section>
		<!--排序弹层-->
		<div class="sort hidden">
			<ul>
				<li class="after <if condition="$_GET['order'] eq ''">active</if>" onclick="sortOrder('','')"><span class="ft">智能排序</span><i class="rg"></i></li>
				<li class="after <if condition="$_GET['order'] eq 'price' && $_GET['sort'] eq 'asc'">active</if>" onclick="sortOrder('price','asc')"><span class="ft">价格最低</span><i class="rg"></i></li>

				<li class="after <if condition="$_GET['order'] eq 'price' && $_GET['sort'] eq 'desc'">active</if>" onclick="sortOrder('price','desc')"><span class="ft">价格最高</span><i class="rg"></i></li>

				<li class="after <if condition="$_GET['order'] eq 'add_time' && $_GET['sort'] eq 'desc'">active</if>" onclick="sortOrder('add_time','desc')"><span class="ft">最新发布</span><i class="rg"></i></li>

				<li class="after <if condition="$_GET['order'] eq 'sales_volume' && $_GET['sort'] eq 'desc'">active</if>" onclick="sortOrder('sales_volume','desc')"><span class="ft">销量最高</span><i class="rg"></i></li>

				<li class="after <if condition="$_GET['order'] eq 'comment_sum' && $_GET['sort'] eq 'desc'">active</if>" onclick="sortOrder('comment_sum','desc')"><span class="ft">评价最高</span><i class="rg"></i></li>
			</ul>
		</div>
		<!--筛选弹层-->
		<div class="show_screen hidden">
			<p>价格区间 (元)</p>
			<div class="bg">
				<input type="number" value="{pigcms{$_GET['minPrice']}" id="cminPrice" placeholder="最低价格"/>
				<span>-</span>
				<input type="number" value="{pigcms{$_GET['maxPrice']}" id="cmaxPrice" placeholder="最高价格"/>
			</div>
			<div class="anniu">
				<ul class="after">
					<li class="ft reset">重置</li>
					<li class="rg success">完成</li>
				</ul>
			</div>
		</div>
		<div class="mask hidden"></div>
		<input type="hidden" id="order" value="{pigcms{$_GET['order']}">
		<input type="hidden" id="sort" value="{pigcms{$_GET['sort']}">
		<input type="hidden" id="minPrice" value="{pigcms{$_GET['minPrice']}">
		<input type="hidden" id="maxPrice" value="{pigcms{$_GET['maxPrice']}">
		<input type="hidden" id="search" value="{pigcms{$_GET['search']}">
		<input type="hidden" id="cid" value="{pigcms{$_GET['cid']}">
		<input type="hidden" id="type" value="{pigcms{$_GET['type']}">
		<script type="text/javascript">
			//智能排序点击
			$(function(){
				var text = $('.active').find('span').text();
				$('.click_sort_text').text(text);
			})

			$('.click_sort').click(function(e){
				$('.show_screen').addClass('hidden');
				var av_text=$(this).text();
				for(var i=0;i<$('.sort li').length;i++){
					if(av_text==$('.sort li:eq('+i+') span').text()){
						$('.sort li:eq('+i+')').addClass('active').siblings('li').removeClass('active');
					}
				}

				if($('.sort').hasClass('hidden')){
					$('.sort').removeClass('hidden');
					$('.mask').removeClass('hidden');
				}else{
					$('.sort').addClass('hidden');
					$('.mask').addClass('hidden');
				}

				
			});

			$('.sort li').click(function(e){
				var text=$(this).find('span').text();
				$('.sort').addClass('hidden');
				$('.mask').addClass('hidden');
				$('.click_sort_text').text(text);
				$('.click_sort').addClass('active');
			});

			$('.mask').click(function(e){
				$('.sort').addClass('hidden');
				$('.mask').addClass('hidden');
			});

			//筛选点击
			$('.click_screen').click(function(e){
				if($(this).is('.active')){
					$(this).removeClass('active');
				}else{
					$(this).addClass('active');
				}
				$('.sort').addClass('hidden');
				$('.mask').addClass('hidden');

				if($('.show_screen').hasClass('hidden')){
					$('.show_screen').removeClass('hidden');
				}else{
					$('.show_screen').addClass('hidden');
				}
				
			});

			$('.show_screen .reset').click(function(e){
				$("#cminPrice").val('');
		        $("#cmaxPrice").val('');
		        // $('.click_screen ').removeClass('active');
		        // getUrl();
				// $('.show_screen').addClass('hidden');
			});

			$('.show_screen .success').click(function(e){
				var minPrice = $("#cminPrice").val();
		        var maxPrice = $("#cmaxPrice").val();
		        $("#minPrice").val(minPrice);
		        $("#maxPrice").val(maxPrice);
		        $('.click_screen ').removeClass('active');
		        getUrl();
				$('.show_screen').addClass('hidden');
			});







			function sortOrder(order,sort){
		        $("#order").val(order);
		        $("#sort").val(sort);
				getUrl();
			}

			function getUrl(){
		        var minPrice = $("#minPrice").val();
		        var maxPrice = $("#maxPrice").val();
		        var search = $("#search").val();
		        var sort = $("#sort").val();
		        var order = $('#order').val();
		        var cid = $('#cid').val();
		        var type = $('#type').val();
		        var data = '';
		        if(type){
		        	var data = data + "&type="+type;
		        }
		        if(cid){
		            var data = data + "&cid="+cid;
		        }
		        if(minPrice){
		            var data = data + "&minPrice="+minPrice;
		        }
		        if(maxPrice){
		            var data = data + "&maxPrice="+maxPrice;
		        }
		        if(search){
		            var data = data + "&search="+search;
		        }
		        if(order){
		            var data = data + "&order="+order;
		            var data = data + "&sort="+sort;
		        }
		        // alert(data);
		        location.href = "{pigcms{:U('Yuedan/service_list')}"+data;
		    }

		</script>


	</body>
</html>

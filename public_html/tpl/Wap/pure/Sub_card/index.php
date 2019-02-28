<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>免单套餐</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}sub_card/css/index.css?21511"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script src="{pigcms{$static_public}js/laytpl.js"></script>
		<script src="{pigcms{$static_path}sub_card/js/sub_card.js"></script>
		<script type="text/javascript">
			// !function(e,t){function n(){var n=l.getBoundingClientRect().width;t=t||540,n>t&&(n=t);var i=100*n/e;r.innerHTML="html{font-size:"+i+"px;}"}var i,d=document,o=window,l=d.documentElement,r=document.createElement("style");if(l.firstElementChild)l.firstElementChild.appendChild(r);else{var a=d.createElement("div");a.appendChild(r),d.write(a.innerHTML),a=null}n(),o.addEventListener("resize",function(){clearTimeout(i),i=setTimeout(n,300)},!1),o.addEventListener("pageshow",function(e){e.persisted&&(clearTimeout(i),i=setTimeout(n,300))},!1),"complete"===d.readyState?d.body.style.fontSize="16px":d.addEventListener("DOMContentLoaded",function(e){d.body.style.fontSize="16px"},!1)}(640,640);
		</script>
	</head>
	<body>
	<!--排序功能-->
	<ul class="sort after">
		<li class="ft" data-sort="id"><span>智能排序</span></li>
		<li class="ft active sale_count" data-sort="sale_count"><div class="border">
			<span>销量</span><dl><dd class="up "></dt><dd class="down less active" ></dd></dl>
		</div>
		</li>
		<li class="ft price" data-sort="price"><span>价格</span><dl><dd class="up active"></dt><dd class="down "></dd></li>
	</ul>
	<!--洗车大礼包-->
	<!--1-->
	<div class="all_cars" id="card_html">
	
	</div>
	
	<div class="bottom_css more" >
		点击加载更多
	</div>
	<script id="cardlist" type="text/html">
					
		{{# for(var i = 0, len = d.length; i < len; i++){ }}
			<div class="car_package" data-url="{pigcms{:U('Sub_card/sub_card_detail')}&sub_card_id={{ d[i].id }}">
				<div class="car_header">
					<h4>{{ d[i].name }}</h4>
					<div class="frequency">
						<span><b>￥<b>{{ d[i].price }}</b></b><span>/{{ d[i].free_total_num }}次</span></span>
						<a href="javascript:;">共含{{ d[i].join_num }}个店铺{{# if(d[i].use_time_type==1){ }}，购买后{{ d[i].effective_days }}天内有效{{# }else{ }}，{{# if(d[i].forever_txt!=''){ }}{{ d[i].forever_txt }}{{# }else{ }}购买后永久有效{{# } }}{{# } }}</a>
					</div>
				</div>
				<div class="car_content">
					<p>{{ d[i].desc }}</p>
				</div>
				<div class="car_footer after">
					<p class="ft"><i></i><span> {{# if(d[i].buy_time_type==1){ }}限时: {{ d[i].start_time }} 至 {{ d[i].end_time }}{{# }else{ }} 限时: 不限时{{# } }}</span></p>
					<span class="rg">已售: <span>{{ d[i].sale_count }}</span></span>
				</div>
				{{# var index = i%3; }}
				<div class="bg{{# if(index==0){ }}3{{# }else if(index==1){ }}2{{# }else if(index==2){ }}1{{# } }} ft">
					
				</div>
			</div>
		{{# } }}
	</script>
	<script type="text/javascript">
		var more = 0,up = 0,now_count= 0,page=0;
		var sort = $('.sort li.active').data('sort');
		show_list(more,sort,up);
		$('.more').click(function(){
			show_list(1,sort,up);
		})
		function show_list(more,sort,up){
			if(!more){
				page = 0;
				document.getElementById('card_html').innerHTML  = '';
			}else{
				page += 10;
			}
			$.post("{pigcms{:U('Sub_card/ajax_get_card')}",{page:page,sort:sort,up:up},function(result){
				data = result.card_list.list;
				count= result.card_list.count;
				now_count += data.length;
				if(data.length > 0){
					laytpl(document.getElementById('cardlist').innerHTML).render(data, function(html){
						if(more){
							document.getElementById('card_html').innerHTML += html;
						}else{
							document.getElementById('card_html').innerHTML = html;
						}
						if(count>now_count){
							$('.more').show();
						}else{
							$('.more').hide();
						}
						$('.car_package').bind('click',function(){
							console.log(111)
							window.location.href=$(this).data('url')
						})
					});
				}else{
					alert('未查找到内容！');
				}
			},'json');
		}
		
		
		$('.sort li').click(function(e){
			$(this).addClass('active').siblings('li').removeClass('active');
			if(sort!=$(this).data('sort')){
				$('.sort li dd').removeClass('active');
				$('.sort li.price .down').addClass('active').siblings('li').removeClass('active');
				$('.sort li.sale_count .up').addClass('active').siblings('li').removeClass('active');
			}
			sort = $(this).data('sort');
			
			now_count = 0;
			if(sort!='id'){
				if($(this).find('.up').is('.active')){
					up = 0;
					$(this).find('.up').removeClass('active').siblings('dd').addClass('active');
				}else{
					up = 1;
					$(this).find('.up').addClass('active').siblings('dd').removeClass('active');
				}
			}
			show_list(0,sort,up);
		});	
		
		
		

	</script>
</body>
</html>
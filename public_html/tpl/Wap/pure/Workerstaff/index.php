<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>技师首页</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/worker_deliver.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
</head>
<body>
	<section class="pic">
		<div class="MyEx_top">
		<if condition="$worker_session['avatar_path']">
			<span class="bjt" style="background: url({pigcms{$config.site_url}/upload/appoint/{pigcms{$worker_session['avatar_path']}) center no-repeat;background-size:104px"></span>
		<elseif condition='$worker_session["merchant_store_id"]' />
			<span class="bjt" style="background: url({pigcms{$store['image']}) center no-repeat;"></span>
		<else />
			<span class="bjt" style="background: url({pigcms{$config.site_logo}) center no-repeat;"></span>
		</if>
			<h2>{pigcms{$worker_session['name']}<i></i></h2>
			<div class="evaluate_right clr">
				<span class="fl">{pigcms{$store['name']|msubstr=###,0,10}</span>
				<div class="atar_Show">
					<p></p>
				</div>
				<span class="fr"><i>{pigcms{$worker_session['all_avg_score']}</i>分</span>
			</div>	
		</div>	
	</section>

	<section class="linkA clr">
		<a href="{pigcms{:U('grab')}" class="fl">
			<i>{pigcms{$gray_count}</i>
			<p>待服务</p>
		</a>
		<a href="{pigcms{:U('pick')}" class="fl">
			<i>{pigcms{$deliver_count}</i>
			<p>服务中</p>
		</a>
		<a href="{pigcms{:U('finish')}" class="fl">
			<i>{pigcms{$finish_count}</i>
			<p>已服务</p>
		</a>
	</section>

	

	<section id="service-date"  style="padding-bottom: 60px;">
		<div class="yxc-pay-main yxc-payment-bg pad-bot-comm">
			<header class="yxc-brand">
				<a class="arrow-wrapper" data-role="cancel">
					<i class="bt-brand-back"></i>
				</a>
			</header>
            <div class="yxc-time-con number-{pigcms{:count($timeOrder)} clr">
				<volist name="timeOrder" id="timeOrderInfo">
					<dl <if condition="$i eq count($timeOrder)">class="last"</if>>
						<dt <if condition="$i eq 1">class="active"</if> data-role="date" data-text="<if condition="$key eq date('Y-m-d')" > 今天<elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />明天
	<elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />后天<else />{pigcms{$key}
								</if>" data-date="{pigcms{$key}">
								<if condition="$key eq date('Y-m-d')" > 今天
								<elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />明天
								<elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />后天
								<else />
								</if>
							<span>{pigcms{$key}</span>
						</dt>
					</dl>
				</volist>
			</div>
            
			<div class="yxc-time-con number-{pigcms{:count($timeOrder)}">
				
			</div>
			<div class="yxc-time-con" data-role="timeline" id="worker_time">
				<volist name="timeOrder" id="timeOrderInfo">
					<div class="date-{pigcms{$key} timeline clr" <if condition="$i neq 1">style='display:none'</if> >
					   <volist name="timeOrderInfo" id="vo">
						<dl>
							<dd data-role="item" data-peroid="{pigcms{$vo['start']}" <if condition="$vo['order'] eq 'no' || $vo['order'] eq 'all' ">class="disable"</if>>{pigcms{$vo['start']}<br>
							<if condition="$vo['order'] eq 'no' ">不可预约<elseif condition="$vo['order'] eq 'all' " />已约满<else />可预约</if></dd>
						</dl>
						</volist>
					</div>
				</volist>
            </div>
		</div>
	</section>
<section class="bottom">
		<div class="bottom_n">
			<ul>
				<li class="Statistics fl">
                    <a href="{pigcms{:U('tongji')}">统计</a>
				</li>
				<li class="home homeon fl">
					<a href="javascript:void(0);"><i></i>首页</a>
				</li>
				<li class="My fl">
					<a href="{pigcms{:U('info')}">我的</a>
				</li>
			</ul>
		</div>
	</section>
	<script type="text/javascript">
	
	
  	// 显示分数
      $(".evaluate_right").each(function() {
        var num=$(this).find("i").text();
        var www=num*16;
        $(this).find("p").css("width",www);
    });


		$('.yxc-time-con dt[data-role="date"]').click(function(){
		$('.yxc-time-con dt[data-role="date"]').removeClass('active');
		$(this).addClass('active');
		$('.date-'+$(this).data('date')).show().siblings('div').hide();
	});
	</script>
</body>
</html>
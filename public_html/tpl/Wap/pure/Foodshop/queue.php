<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$store['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/css_whir.css"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
<script>var queue_save_url = '{pigcms{:U("Foodshop/queue_save")}', queue_cancel_url = '{pigcms{:U("Foodshop/queue_cancel")}', notice_save_url = '{pigcms{:U("Foodshop/notice_save")}';</script>
<script type="text/javascript" src="{pigcms{$static_path}js/foodshopqueue.js"></script>
</head>
<body>
<header class="picture" style="background: url({pigcms{$store['image']}) no-repeat; background-size: 100% 100%">
	<div class="picture_text">
		<i>1</i>/<em>{pigcms{$store['image_list']|count}</em>
	</div>
	<volist name="store['image_list']" id="img">
		<input type="hidden" class="img" value="{pigcms{$img}"/>
	</volist>
</header>
<section class="Tnumber">
<input type="hidden" id="store_id" value="{pigcms{$store['store_id']}" />
	<table>
		<tr>
			<th>餐桌类型</th>
			<th>等待桌数</th>
			<th>预计等待</th>
		</tr>
		<volist name="queue_list" id="qrow">
		<tr>
			<td>{pigcms{$qrow['name']}<i>{pigcms{$qrow['min_people']}-{pigcms{$qrow['max_people']}人</i></td>
			<td><em>{pigcms{$qrow['wait']}</em>桌</td>
			<td>{pigcms{$qrow['wait_time']}分钟</td>
		</tr>
		</volist>
     </table>
</section>
<if condition="empty($queue)">
<section class="Takethe" >
	<if condition="empty($queue_list)">
	<input type="button" value="暂无可选桌台类型" >
	<elseif condition="$store['queue_is_open']" />
		<input type="submit" value="立即取号" class="immediately">
	<elseif condition="$notice" />
		<input type="button" value="已设置取号提醒" >
	<else />
		<input type="button" value="还没开始,设置取号提醒" id="notice_save">
	</if>
	<span>听到号请到迎宾台，过号作废</span>
</section>
</if>
<section class="Popup">
	<div class="Popup_top">
		<span>立即取号</span>
		<a href="javascript:void(0)" class="Popup_gb"></a>
	</div>
	<div class="Popup_end">
		<ul>
			<li class="clr">
				<span>人数</span>
				<input type="text" placeholder="请输入就餐人数" id="num">
			</li>
			<li class="clr">
				<span>餐桌类型</span>
				<input type="text" id="city_dummy" class="" placeholder="" readonly="">
				<select id="city" class="demo-test-select dw-hsel" data-role="none" tabindex="-1">
					<volist name="queue_list" id="qrow">
					<option value="{pigcms{$qrow['id']}" data-min="{pigcms{$qrow['min_people']}" data-max="{pigcms{$qrow['max_people']}">{pigcms{$qrow['name']}({pigcms{$qrow['min_people']}-{pigcms{$qrow['max_people']}人)</option>
					</volist>
				</select>
			</li>
		</ul>
		<div class="Determine">
			<input type="button" value="确定" class="tcTakewc" id="queue_save">
		</div>
	</div>
</section>
<if condition="$store['queue_is_open'] eq 1 AND $queue">
<section class="Takewc" >
	<div class="Takewc_top">
		<div class="Takewc_n clr">
			<div class="Takewc_left">
				<i>{pigcms{$queue['number']}</i>
				<em>{pigcms{$queue['name']}</em>
			</div>
			<div class="Takewc_right">
				<ul>
					<li class="Takewc_yqh">
						<span class="yqh_span1">已取号</span>
						<span class="yqh_span2">{pigcms{$queue['create_time']}</span>
					</li>
					<if condition="$queue['wait']">
					<li class="wait">还需等待   <i>{pigcms{$queue['wait']}</i>桌</li>
					<li class="wait">{pigcms{$queue['wait_time']}</li>
					<else />
					<li class="wait">叫号中...</li>
					<li class="wait">请耐心等待店员叫号</li>
					</if>
					
				</ul>
			</div>
  	 	</div>
	<div class="warning">过号请重新取号，谢谢配合！</div>
	</div>
	<div class="Takewc_end">
		<input type="button" value="取消排队" id="queue_cancel">
	</div>
</section> 
</if>
<div class="Mask"></div>
{pigcms{$shareScript}
</body>
</html>
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
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body>

<section class="Cart CartReserve">
	<if condition="($old_goods_list OR $goods_list OR $package_list OR $must_list)">
	<div class="CartReserve_top"><span>已预订菜品</span></div>
	<div style='text-align:center;color:#fff;line-height:35px;height:35px; margin: 8px 0px;'>
        <span style='  padding: 7px 14px;background: #2BC7A2;'>邀请好友一起点餐</span>
	</div>
	<if condition="$old_goods_list OR $package_list OR $must_list">
	<div class="Cart_list">
		<ul>
            <li class="clr" style='paddong:0px'>
                <div class="Clist_left">
                    <img src="{pigcms{$static_path}images/dengdai.png" style='border-radius: 50%; width: 30px;  height: 30px;'>
                </div>
                <div style='padding-left: 10px;padding-top: 5px;  float: left;'>
                    <span style='font-weight:bold;color:#000000'>唐金涛</span>
                </div>
                <div class="Clist_right" style='color: #d0d0d0; margin-top: -14px; border: 1px solid #bbb; padding: 1px 10px;  box-shadow: 0px 1px 1px #bbbbbb;'>
                        <span>删除ta</span>
                </div>
            </li>
			<volist name="old_goods_list" id="goods">
			<li class="clr packages">
				<div class="Clist_left">
					<h2>{pigcms{$goods['name']}</h2>
					<if condition="$goods['spec']">
					<span>({pigcms{$goods['spec']})</span>
					</if>
				</div>
				<div class="Clist_right">
					<div class="MenuPrice">
						<i>￥</i>{pigcms{$goods['price']|floatval}<if condition="$config.open_extra_price AND $goods.extra_price gt 0">+{pigcms{$goods['extra_price']|floatval}{pigcms{$config.extra_price_alias_name}</if>
					</div>
					<div class="Addsub">
						<span>{pigcms{$goods['num']|floatval}{pigcms{$goods['unit']}</span>
						<!--a href="javascript:void(0)" class="jian"></a-->
						<!--a href="javascript:void(0)" class="jia"></a-->
					</div>
				</div>
			</li>
			</volist>
			<!--  新增html -->		
			<volist name="package_list" id="plist">
			<li class="clr on">
				<div class="clr Package">
					<div class="Clist_left">
						<h2 style="color: #29c7a2;">{pigcms{$plist['name']}</h2>
					</div>
                    <if condition="isset($plist['isNew']) AND $plist['isNew'] eq 1">
                    <div class="phpo">
                        <img src="{pigcms{$static_path}images/dengdai.png">
                    </div>
                    </if>
					<div class="Clist_right">
						<div class="MenuPrice">
							<i>￥</i>{pigcms{$plist['price']|floatval}	
						</div>
						<div class="Addsub">
							<span>{pigcms{$plist['num']|floatval}份</span>
						</div>
					</div>
				</div>
				<div class="Package_end">
					<dl>
						<volist name="plist['list']" id="row">
						<dd>
							{pigcms{$row['name']} <if condition="$row['spec']"><i>({pigcms{$row['spec']})</i></if>
							<div class="fr"><span class="on">￥{pigcms{$row['price']|floatval}</span> <span>{pigcms{$row['num']|floatval}/{pigcms{$row['unit']}</span></div> 
						</dd>
						</volist>
						
					</dl>
					<a href="javascript:void(0)" class="more"></a>
				</div>
				<div class="bottom"></div>
			</li>
			</volist>
			<volist name="must_list" id="must">
			<li class="clr">
				<div class="Clist_left">
					<h2>{pigcms{$must['name']} <i>必点</i></h2>
				</div>
				<div class="Clist_right">
					<div class="MenuPrice">
						<i>￥</i>{pigcms{$must['price']|floatval}	
					</div>
					<div class="Addsub">
						<span>{pigcms{$must['num']|floatval}{pigcms{$must['unit']}</span>
					</div>
				</div>
			</li>
			</volist>
			<!--  新增html -->
		</ul>
	</div>
	</if>
	<!--  新增html -->  
	<div class="Cart_list Cartbot_list" style='overflow-y: auto;'>
		<ul>
			<volist name="goods_list" id="goods">
			<li class="clr">
				<div class="Clist_left">
					<h2>{pigcms{$goods['name']}</h2>
					<if condition="$goods['spec']">
					<span>({pigcms{$goods['spec']})</span>
					</if>
				</div>
				<div class="phpo">
					<img src="{pigcms{$static_path}images/dengdai.png">
				</div>
				<div class="Clist_right">
					<div class="MenuPrice">
						<i>￥</i>{pigcms{$goods['price']|floatval}<if condition="$config.open_extra_price AND $goods.extra_price gt 0">+{pigcms{$goods['extra_price']|floatval}{pigcms{$config.extra_price_alias_name}</if>
					</div>
					<div class="Addsub">
						<span>{pigcms{$goods['num']|floatval}{pigcms{$goods['unit']}</span>
					</div>
				</div>
			</li>
			</volist>
			
		</ul>
	</div>
	<!--  新增html -->
	</if>
	<div class="Reservation" style="margin-top: 1px;">
		<div class="textarea" style="margin-bottom: 5px;">
			<textarea placeholder="如有附加要求，可填写，我们会尽量安排" name="note" id="note">{pigcms{$order['note']}</textarea>
		</div>
	</div>
	<div class="Serving">
		<dl>
			<!-- <dt>总计：{pigcms{$price|floatval}元</dt> -->
			<dd class="vegetables clr">
				<a href="{pigcms{:U('Foodshop/menu', array('order_id' => $order['order_id'], 'store_id' => $order['store_id']))}" class="add" <if condition="$is_add_menu eq 0">style="display:none"</if>>加菜</a>
				<a href="javascript:void(0)" class="notice" <if condition="$is_call_store eq 0">style="display:none"</if>>通知上菜</a>
				<a href="javascript:void(0)" class="call" <if condition="$order['status'] gt 2">style="display:none"</if>>呼叫服务</a>
			</dd>
		</dl>
	</div>
</section>


<if condition="$order['book_time']">
<if condition="empty($old_goods_list) AND empty($goods_list)">
<section class="Success"><span>座位详情！</span></section>
</if>
<section class="Sudetails">
	<ul>
		<li class="Su_zh">
			<dl>
				<dd>{pigcms{$order['book_time_show']}</dd>
				<dd>{pigcms{$order['book_num']}人 | {pigcms{$order['table_type_name']}  <span class="Su_sit"><if condition="$order['status'] eq 0"><b style="color:red">未付</b><else/>已付</if>定金:￥{pigcms{$order['book_price']|floatval}</span></dd>
				<dd>{pigcms{$order['name']} <if condition="$order['sex'] eq 1">先生<else />女士</if> {pigcms{$order['phone']}</dd>
			</dl>
		</li>
	</ul>
</section>
</if>

<!--  新增html --> 
<section class="cartetails">
	<div class="cartetails_list">
		<h1>订单详情</h1>
		<ul>
			<li class="clr">
				<i>订单编号</i>
				<span class="fr">{pigcms{$order['real_orderid']}</span>
			</li>
			<li class="clr">
				<i>餐台类型</i>
				<span class="fr">{pigcms{$order['table_type_name']}</span>
			</li>
			<li class="clr">
				<i>餐台名称</i>
				<span class="fr">{pigcms{$order['table_name']}</span>
			</li>
			<li class="clr">
				<i>下单时间</i>
				<span class="fr">{pigcms{$order['create_time']|date='Y-m-d H:i', ###}</span>
			</li>
			<if condition="$order['book_pay_time']">
			<li class="clr">
				<i>预订支付时间</i>
				<span class="fr">{pigcms{$order['book_pay_time']|date='Y-m-d H:i', ###}</span>
			</li>
			</if>
			<if condition="$order['book_pay_type']">
			<li class="clr">
				<i>预订支付方式</i>
				<span class="fr">{pigcms{$order['book_pay_type']}</span>
			</li>
			</if>
			<if condition="$order['pay_time']">
			<li class="clr">
				<i>买单时间</i>
				<span class="fr">{pigcms{$order['pay_time']|date='Y-m-d H:i', ###}</span>
			</li>
			</if>
			<if condition="$order['pay_type']">
			<li class="clr">
				<i>买单支付方式</i>
				<span class="fr">{pigcms{$order['pay_type']}</span>
			</li>
			</if>
			<!--li class="clr on">
				<div class="clr"><i class="fl">平台余额抵扣</i> <em class="fr">￥108.00</em></div>
				<div class="clr"><i class="fl">付款方式</i> <em class="fr">余额支付</em></div>
			</li-->
		</ul>
	</div>
</section>
<div style="padding-bottom: 65px;"></div>
 <!--  新增html -->
<div class="Total clr" <if condition="$is_pay eq 0"> style="display:none"</if>>
	<div class="Total_left">总计<span>￥<i>{pigcms{$price|floatval}<if condition="$config.open_extra_price AND $extra_price GT 0">+{pigcms{$extra_price}{pigcms{$config.extra_price_alias_name}</if></i></span></div>
	<a href="{pigcms{:U('Foodshop/pay', array('order_id' => $order['order_id']))}" class="Check">去买单</a>
</div>
<if condition="$order['status'] eq 3">
<div class="Total clr">
	<div class="Total_left">已买单</div>
	<a href="{pigcms{:U('My/foodshop_feedback', array('order_id' => $order['order_id']))}" class="Check">去评价</a>
</div>
</if>
<script>
var timeout = 0;
var height= parseInt($(window).height())*0.4;
var height1=$(".Cartbot_list").height();
 if(height1>height){
  $(".Cartbot_list").css("height":height+'px')
 }

$(function(){
	var notice = false;
	$('.notice').click(function(){
		if (notice) return false;
		notice = true;
		$.post("{pigcms{:U('Foodshop/call_store', array('order_id' => $order['order_id']))}", {'note':$('#note').val()}, function(response){
			if (response.err_code) {
				layer.open({content:response.msg,skin: 'msg',time: 2});
			} else {
				location.reload();
				timeout = setInterval(check_status, 10000);
				layer.open({content:response.msg,skin: 'msg',time: 2});
				$('.Serving,.Total').hide();
			}
		}, 'json');
	});
    var call = false;
    $('.call').click(function(){
        if (call) return false;
        call = true;
        $.post("{pigcms{:U('Foodshop/callServer', array('order_id' => $order['order_id']))}", function(response){
            if (response.err_code) {
                layer.open({content:response.msg,skin: 'msg',time: 2});
            } else {
                layer.open({content:response.msg,skin: 'msg',time: 2});
            }
            call = false;
        }, 'json');
    });
});
timeout = setInterval(check_status, 10000);
function check_status()
{
	$.get("{pigcms{:U('Foodshop/check_status', array('order_id' => $order['order_id']))}", function(response){
		if (response.err_code) {
		} else {
			clearInterval(timeout);
			location.reload();
// 			$('.Serving, .Total, .add').show();
// 			$('.notice').hide();
		}
	}, 'json');
}
</script>


<!-- 新增js -->

<script type="text/javascript">
	// 点击展开影藏
		$(".Package_end dl").each(function(){
			var height=$(this).height();
			if(height>80)
			{$(this).css({"height":"80px","overflow":"hidden"})}
			else{ $(this).siblings("a.more").hide()}
		})
		$(".Package_end .more").click(function(){
			$(this).hide();
			$(this).siblings(".Package_end dl").css("height","auto");
		})
</script>
</body>
</html>
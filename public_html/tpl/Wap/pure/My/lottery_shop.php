<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>抽奖</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	 <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/lottery_shop.css?2151"/>
	<script>
		var award = '{pigcms{$rid}';
		var order_id = '{pigcms{$_GET['order_id']}';
		var type = '{pigcms{$_GET['type']}';
		var ajax_check_lottery = '{pigcms{:U('ajax_check_lottery')}';
	</script>
	
</head>
 <body>
        <section class="luck">
            <div class="luck_top">
                <div class="title">{pigcms{$lottery.lottery_msg}</div>
            </div>
            <div class="luck_mid">
                <div class="luck_h2">您只可抽奖<i>1</i>次哟</div>
                <div class="dbj">
                    <div class="ul" id="lottery">
                        <table class="clr">
                            <tr>
								<if condition="$prize_arr">
								<for start = "0" end = "4">
								
									<td class="lottery-unit lottery-unit-{pigcms{$i}" data-is_win="{pigcms{$prize_arr[$i]['is_win']}">
										<div class="con">
											
											<php>if($prize_arr[$i]['image_url']!=''){</php>
												<div class="img">
													<img src="{pigcms{$prize_arr[$i]['image_url']}">
												</div>
											<php>}</php>
											<p>{pigcms{$prize_arr[$i]['title']}</p>
										</div>
									</td>
								</for>
								<tr>
									<td class="lottery-unit lottery-unit-9" data-is_win="{pigcms{$prize_arr[9]['is_win']}">
										<div class="con">
											<php>if($prize_arr[9]['image_url']!=''){</php>
												<div class="img">
													<img src="{pigcms{$prize_arr[9]['image_url']}">
												</div>
											<php>}</php>
											<p>{pigcms{$prize_arr[9]['title']}</p>
										</div>
									</td>
									<td class="click" colspan="2">
										<div class="con">点击抽奖</div>
									</td>
									<td class="lottery-unit lottery-unit-4" data-is_win="{pigcms{$prize_arr[4]['is_win']}">
										<div class="con">
											<php>if($prize_arr[4]['image_url']!=''){</php>
												<div class="img">
													<img src="{pigcms{$prize_arr[4]['image_url']}">
												</div>
											<php>}</php>
											<p>{pigcms{$prize_arr[4]['title']}</p>
										</div>
									</td>
								</tr>
								<for start = "8" end = "4" step="-1" comparison="gt">
								
									<td class="lottery-unit lottery-unit-{pigcms{$i}" data-is_win="{pigcms{$prize_arr[$i]['is_win']}" >
										<div class="con">
											
											<php>if($prize_arr[$i]['image_url']!=''){</php>
												<div class="img">
													<img src="{pigcms{$prize_arr[$i]['image_url']}">
												</div>
											<php>}</php>
											<p>{pigcms{$prize_arr[$i]['title']}</p>
										</div>
									</td>
								</for>
								
							 
                            </tr>
                           
                        </table>
                        
                    </div>
                </div>
            </div>
            <div class="luck_bot"> 
                <div class="con">
                    <div class="h2">抽奖规则</div>
                    <div class="p">
						{pigcms{$lottery.lottery_rule}
                    </div>
                </div>
            </div>
        </section>
		<style>
			.popup p{
				padding:15px 0;
			}
		</style>
        <section class="popup">
            <h2>恭喜您，中奖啦</h2><!-- 很抱歉，没有中奖 -->
            <p>恭喜您中了一张平台优惠券， 已存入您的账户</p><!-- 很抱歉，没有中奖 -->
            <div class="buton">查看记录</div><!-- 返回抽奖 -->

        </section>

        <div class="mask"></div>
    </body>

<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}js/lottery_shop.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>


</html>